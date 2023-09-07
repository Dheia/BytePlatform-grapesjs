<?php

namespace BytePlatform\Builder\Form;

use BytePlatform\Builder\HtmlBuilder;

class FormBuilder extends HtmlBuilder
{
    public function __construct(private FormItem $formItem)
    {
    }
    protected function render()
    {
        $this->formItem->beforeRender();
        if (!apply_filters(PLATFORM_FORM_RENDER, false, $this->formItem)) {
            echo view_scope('byteplatform::builder.form', [
                'form' => $this->formItem
            ])->render();
        }
    }
}
