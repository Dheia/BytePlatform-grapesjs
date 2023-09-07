@php
    $dataOptions = $item->getDataOption();
    $modelField = $item->getModelField();
    $titleField = $item->getTitle() ?? $item->getField();
@endphp
<div {!! $item->getAttributeContent() !!}>
    @if (!$item->getManager()->IsTable())
        <label class="form-label">{{ $titleField }}</label>
    @endif

    <button {!! $item->getAttribute() ?? '' !!} byteplatform:modal-choose="{{ $dataOptions['modal-choose'] ?? '' }}"
        byteplatform:modal="{{ $dataOptions['modal'] ?? '' }}" byteplatform:modal-title="{{ $dataOptions['modal-title'] ?? '' }}"
        byteplatform:modal-size="{{ $dataOptions['modal-size'] ?? '' }}" byteplatform:model="{{ $modelField }}" class="btn btn-blue">
        <span class="dz-default dz-message">Choose {{ $titleField }}</span>

    </button>
    <div wire:ignore x-show="$wire.{{ $modelField }}" x-data="{
        dataItems: function() {
            return $wire.{{ $modelField }};
        }
    }">
        <div>
            <template x-for="item in dataItems()">
                <span x-text="item"></span>
            </template>
            {!! $item->getDataText() !!}
        </div>
    </div>
    @error($modelField)
        <div>
            <span class="error">{{ $message }}</span>
        </div>
    @enderror
</div>
