<?php

namespace BytePlatform\Livewire\Extentions;

use BytePlatform\Component;

class CreateFile extends Component
{
    public $ExtentionType;
    public function render()
    {
        return view('byteplatform::extentions.create-file');
    }
}
