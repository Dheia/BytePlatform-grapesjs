<?php

namespace BytePlatform\Livewire;

use BytePlatform\Component;
use BytePlatform\ItemManager;
use BytePlatform\Tables\WithTableData;

class Table extends Component
{
    use WithTableData;
    public ItemManager $manager;
    protected function ItemManager()
    {
        return $this->manager;
    }
}
