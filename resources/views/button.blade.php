@php
    $buttonAtrr = $button->getAttribute() ?? '';
    $buttonClass = $button->getClass() ?? '';
    if ($url = $button->getModalUrl()) {
        $buttonAtrr .= ' byteplatform:modal="' . $url . '" ';
        if ($size = $button->getModalSize()) {
            $buttonAtrr .= ' byteplatform:modal-size="' . $size . '" ';
        }
        if ($title = $button->getModalTitle()) {
            $buttonAtrr .= ' byteplatform:modal-title="' . $title . '" ';
        }
    }
    
    if ($confirm = $button->getConfirm()) {
        $buttonAtrr .= ' byteplatform:confirm="' . $confirm . '" ';
        if ($confirmYes = $button->getConfirmYes()) {
            $buttonAtrr .= ' byteplatform:confirm-yes="' . $confirmYes . '" ';
        }
        if ($confirmNo = $button->getConfirmNo()) {
            $buttonAtrr .= ' byteplatform:confirm-no="' . $confirmNo . '" ';
        }
        if ($title = $button->getConfirmTitle()) {
            $buttonAtrr .= ' byteplatform:confirm-title="' . $title . '" ';
        }
    }
    
    if ($wireClick = $button->getWireClick()) {
        $buttonAtrr .= ' wire:click="' . $wireClick . '" ';
    }
    if ($buttonType = $button->getButtonType()) {
        $buttonClass .= ' btn-' . $buttonType . ' ';
    }
    
    if ($buttonSize = $button->getButtonSize()) {
        $buttonClass .= ' btn-' . $buttonSize . ' ';
    }
    
@endphp
<button class="btn {{ $buttonClass }}" {!! $buttonAtrr !!}>
    {{ $button->getTitle() }}
</button>
