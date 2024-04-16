<?php

namespace Sokeio\Components\Field\Concerns;


trait WithSearchFn
{
    public function searchFn($searchFn): static
    {
        return $this->setKeyValue('searchFn', $searchFn);
    }
    public function getSearchFn()
    {
        return $this->getValue('searchFn');
    }
    public function querySearchFn($fnQuery, $name = null)
    {
        if (!$fnQuery || !is_callable($fnQuery)) {
            return $this;
        }
        if (!$name) {
            $name = 'searchFn_' . $this->getFieldText();
        }
        $this->searchFn($name);
        $this->actionUI($name, $fnQuery);
        $this->dataSource(function ($field) use ($fnQuery) {
            return call_user_func($fnQuery, $field->getManager(), '');
        });
        return $this;
    }
    public function querySearchWithModel($model, $name)
    {
        $this->querySearchFn(function ($component, $text, $currentId = null) use ($model) {
            $component->skipRender();

            $rs = ($model)::query()
                ->when($text != "", function ($query) use ($text) {
                    $query->where('name', 'like', '%' . $text . '%');
                })
                ->limit(20)->get(['id', 'name']);
            if ($currentId && $text == '') {
                $currentItem = ($model)::find($currentId);
                if ($currentItem) {
                    return [
                        [
                            'id' => $currentItem->id,
                            'name' => $currentItem->name
                        ],
                        ...$rs->toArray(),
                    ];
                }
            }
            return $rs;
        }, $name);
    }
}
