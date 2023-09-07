<?php

namespace BytePlatform\Builder\Table;

use BytePlatform\Builder\HtmlBuilder;

class TableBuilder extends HtmlBuilder
{
    private $columns = [];
    public function Columns($columns)
    {
        $this->columns = $columns ?? [];
    }
    public function getColumns()
    {
        return $this->columns ?? [];
    }
    protected function render()
    {
        if (!apply_filters(PLATFORM_FORM_RENDER, false, $this)) {
            echo view_scope('byteplatform::builder.table', [
                'attr' => $this->getAttribute(),
                'form' => $this
            ])->render();
        }
    }
}
