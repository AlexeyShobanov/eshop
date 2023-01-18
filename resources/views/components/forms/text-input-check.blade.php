@props([
'name' => '',
'type' => 'text',
'placeholder' => '',
'isError' => false,
'value' => ''
])

<input name="{{ $name }}" type="{{ $type }}" value="{{ $value }}" placeholder="{{ $placeholder }}"
       {{  $attributes
        ->class([
            '_is-error' => $isError,
            'w-full h-16 px-4 rounded-lg border border-body/10 focus:border-pink focus:shadow-[0_0_0_3px_#EC4176] bg-white/5 text-white text-xs shadow-transparent outline-0 transition'
        ]) }}
       required>