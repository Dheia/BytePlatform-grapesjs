<div
    style="min-height: {{ isset($WidgetSetting['minHeight']) && $WidgetSetting['minHeight'] != '' ? $WidgetSetting['minHeight'] : '100' }}px;">
    <livewire:sokeio::widget-form :widgetId="$widgetId" wire:key='form-widget-{{ time() }}' />
</div>