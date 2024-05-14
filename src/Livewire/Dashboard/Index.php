<?php

namespace Sokeio\Livewire\Dashboard;

use Sokeio\Component;
use Sokeio\Facades\Assets;
use Sokeio\Facades\Dashboard as FacadesDashboard;

class Index extends Component
{
    public function mount()
    {
        Assets::setTitle(__('Dashboard'));
    }
    public function render()
    {
        return view('sokeio::dashboard.index', [
            'widgets' => FacadesDashboard::getWidgetInDashboard('dashboard-default'),
            'positions' => FacadesDashboard::getPosition()
        ]);
    }
}