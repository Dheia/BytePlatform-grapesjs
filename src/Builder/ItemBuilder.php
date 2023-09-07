<?php

namespace BytePlatform\Builder;

use BytePlatform\Builder\Form\FieldItem;
use BytePlatform\Builder\Table\ColumnItem;
use Illuminate\Support\Traits\Macroable;

class ItemBuilder
{
    use Macroable;
    private $callbackBeforeSetup = null;
    public function beforeRender()
    {
        if ($this->callbackBeforeSetup && is_callable($this->callbackBeforeSetup)) {
            $this->callbackBeforeSetup($this);
        }
    }
    public function beforeSetup($callback)
    {
        $this->callbackBeforeSetup = $callback;
    }
    public static function Column($field)
    {
        return new ColumnItem($field);
    }
    public static function Field($field)
    {
        return new FieldItem($field);
    }
}
