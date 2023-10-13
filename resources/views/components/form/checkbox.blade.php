@php
    $computed  = $attributes->whereStartsWith('wire:model')->first();
    $error     = $errors->has($computed);
    $customize = tallstackui_personalization('form.checkbox', $customization());
    $internal  = $internals();
@endphp

<x-wrapper.radio :$computed :$error :$label :$position :$id>
    <input @if ($id) id="{{ $id }}" @endif type="checkbox" {{ $attributes->class([
            $customize['input'],
            $internal['input.color'],
            $customize['error'] => $error
    ]) }} @checked($checked)>
</x-wrapper.radio>
