<?php

namespace BytePlatform;

use Illuminate\Support\Facades\File;
use BytePlatform\Laravel\JsonData;
use BytePlatform\Events\PlatformStatusChanged;
use Illuminate\Support\Str;
use BytePlatform\Facades\Platform;

class DataInfo extends JsonData
{
    const Active = 1;
    const UnActive = 0;
    public function __construct($path, $parent)
    {
        parent::__construct([], $parent);
        $this['path'] = $path;
        $this['fileInfo'] =  $parent->FileInfoJson();
        $this['public'] =  $parent->PublicFolder();
        $this['base_type'] = $parent->getName();
        $this->loadAll($path);
        $this->ReLoad();
    }
    public function loadAll($path)
    {
        Platform::Load($path);
    }
    public function ReLoad()
    {
        $temp = $this->CloneData();
        $this->loadJsonFromFile($temp['path'] . '/' .  $temp['fileInfo']);
        $this['path'] = $temp['path'];
        $this['fileInfo'] = $temp['fileInfo'];
        $this['public'] = $temp['public'];
        $this['base_type'] = $temp['base_type'];
        $this['key'] = basename($temp['path'], ".php");
    }
    public function __toString()
    {
        return $this->getName();
    }
    public function checkKeyValue($key, $value)
    {
        return $this[$key] == $value;
    }

    public function getPath($_path = '')
    {
        return $this->path . ($_path ? ('/' . $_path) : '');
    }
    public function getPublic($_path = '')
    {
        return $this->public . ($_path ? ('/' . $_path) : '');
    }
    public function getPathPublic($path)
    {
        return $this->getPath('public/' . $path);
    }
    public function url($_path = '')
    {
        return url(byteplatform_path($this->base_type, $this->getName() . ($_path ? ('/' . $_path) : '')));
    }
    public function getFiles()
    {
        return $this['files'] ?? [];
    }
    public function getProviders()
    {
        return $this['providers'] ?? [];
    }
    public function getId()
    {
        return $this['id'];
    }
    public function getName()
    {
        return $this['name'];
    }
    public function getTitle()
    {
        return $this['title'] ?? $this['name'];
    }
    public function getDescription()
    {
        return $this['description'];
    }
    public function getOptionHook(): OptionHook
    {
        if ($this['optionHook']) return $this['optionHook'];
        if (File::exists($this->getPath('OptionHook.php')) && ($optionHook = include_once $this->getPath('OptionHook.php')))
            return $this['optionHook'] = $optionHook;
        $this['optionHook'] = OptionHook::Create()->Options(function () {
            return FormCollection::Create()->Register(function (\BytePlatform\ItemManager $form) {
                return $form;
            });
        });
        return $this['optionHook'];
    }
    public function getOptionDataHook(): FormCollection
    {
        return $this->getOptionHook()->getOptions($this);
    }
    protected function getKeyOption($key)
    {
        return trim(Str::lower("option_datainfo_" . $this['base_type'] . '_' . $this->getId() . '_' . $key . '_value'));
    }
    public function getOption($key, $default = null)
    {
        return setting($this->getKeyOption($key), $default);
    }
    public function setOption($key, $value)
    {
        return set_setting($this->getKeyOption($key), $value);
    }
    public function getStatusData()
    {
        return ArrayStatus::Key($this['base_type'])->Check($this->getId()) || $this['active'];
    }
    private $manifestData = null;
    public function getManifestData()
    {
        if (File::exists($this->getPath('public/build/manifest.json')))
            return $this->manifestData ?? ($this->manifestData = self::getJsonFromFile($this->getPath('public/build/manifest.json')));
        return null;
    }
    public function setStatusData($value)
    {
        $this->getOptionHook()->changeStatus($this, $value);
        if ($value === self::Active) {
            ArrayStatus::Key($this['base_type'])->Active($this->getId());
        } else {
            ArrayStatus::Key($this['base_type'])->UnActive($this->getId());
        }
        PlatformStatusChanged::dispatch($this);
        Platform::makeLink();
    }
    public function getModels()
    {
        $arr = [];
        if ($files =  glob($this->getPath('src/Models') . '/*.*')) {
            foreach ($files as $itemFile) {
                $fileName =  preg_replace("/\.[^.]+$/", "", basename($itemFile));
                $arr[$this->getNamespaceInfo() . '\\Models\\' . $fileName] = $fileName;
            }
        }
        return $arr;
    }
    public function isVendor()
    {
        return !Str::contains($this->getPath(), byteplatform_path($this['base_type']), true);
    }
    public function isActive()
    {
        return $this->status == self::Active;
    }
    public function isActiveOrVendor()
    {
        return $this->isActive() || $this->isVendor();
    }
    public function Active()
    {
        $this->status = self::Active;
    }
    public function UnActive()
    {
        $this->status = self::UnActive;
    }
    public function checkComposer()
    {
        return file_exists($this->getPath('composer.json'));
    }
    public function checkDump()
    {
        return file_exists($this->getPath('vendor/autoload.php'));
    }
    public function Dump()
    {
        run_cmd($this->getPath(), 'composer dump -o -n -q');
    }
    public function update()
    {
    }
    public function CheckName($name)
    {
        return Str::lower($this->getId())  ==  Str::lower($name) || Str::lower($this->name) == Str::lower($name);
    }
    public function getStudlyName()
    {
        return Str::studly($this->getName());
    }
    public function getLowerName()
    {
        return Str::lower($this->getName());
    }
    public function getNamespaceInfo()
    {
        return $this['namespace'];
    }
    public function delete()
    {
        File::deleteDirectory($this->getPath());
    }
    public function loadRoute()
    {
        RouteEx::Load($this->getPath('routes'));
    }
    public function DoRegister()
    {
        $this->providers = Platform::registerComposer($this->getPath(), true);
    }
    public function DoBoot()
    {
        foreach ($this->providers as $item) {
            $item->boot();
        }
    }
}
