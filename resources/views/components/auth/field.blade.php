{{--
    Label + input + error, as one unit, so all seven auth screens agree.

    Deliberately not Jetstream's <x-input>: that ships border-gray-300 at 1.48:1
    against paper, below the 3:1 WCAG 1.4.11 requires for a control boundary.
    Those primitives are left alone because profile/ and teams/ depend on them —
    re-pointing them is its own change.
--}}
@props([
    'name',
    'label',
    'type' => 'text',
    'hint' => null,
    'value' => null,
    'autocomplete' => null,
    'autofocus' => false,
    'required' => true,
    'inputmode' => null,
])

@php
    $hasError = $errors->has($name);
    $describedBy = collect([
        $hint ? $name.'-hint' : null,
        $hasError ? $name.'-error' : null,
    ])->filter()->implode(' ');
@endphp

<div>
    {{-- `action` is the optional right-aligned link on the label row (the
         "Forgot password?" case), so the label is never rendered twice. --}}
    <div class="flex items-baseline justify-between gap-4">
        <label for="{{ $name }}" class="block text-label text-ink">{{ $label }}</label>
        @isset($action)
            <span class="text-label">{{ $action }}</span>
        @endisset
    </div>

    @if ($hint)
        <p id="{{ $name }}-hint" class="mt-0.5 text-label text-ink-muted">{{ $hint }}</p>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ $value }}"
        @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if ($inputmode) inputmode="{{ $inputmode }}" @endif
        @if ($autofocus) autofocus @endif
        @if ($required) required @endif
        @if ($hasError) aria-invalid="true" @endif
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
        {{ $attributes->class([
            'mt-1.5 w-full rounded-sm bg-paper px-3 py-2.5 text-body text-ink transition-colors duration-150 ease-out-quart placeholder:text-ink-faint focus-visible:outline-2 focus-visible:outline-offset-2',
            'border border-ink-faint hover:border-ink-muted focus-visible:border-registry-green focus-visible:outline-registry-green' => ! $hasError,
            'border border-flag-error focus-visible:outline-flag-error' => $hasError,
        ]) }}
    >

    {{-- The message, not a red border alone: colour is never the only carrier. --}}
    @error($name)
        <p id="{{ $name }}-error" class="mt-1.5 text-label text-flag-error">{{ $message }}</p>
    @enderror
</div>
