<?php

namespace BytePlatform\Forms;

trait WithFormPageData
{
    use WithFormData;
    public function render()
    {
        page_title($this->getItemManager()->getTitle());
        return view('byteplatform::forms.page', [
            'itemManager' => $this->getItemManager()
        ]);
    }
}
