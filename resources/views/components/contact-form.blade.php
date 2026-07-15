{{-- Fields use the design system directly rather than site.css's .form-input
     (gray-300 border at 1.48:1, below the 3:1 WCAG 1.4.11 needs for a control
     boundary) and .form-textarea, which was never defined at all. --}}
@php
    $field = 'w-full rounded-sm border border-ink-faint bg-paper px-3 py-2.5 text-body text-ink transition-colors duration-150 ease-out-quart placeholder:text-ink-faint hover:border-ink-muted focus-visible:border-registry-green focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green';
    $fieldError = 'w-full rounded-sm border border-flag-error bg-paper px-3 py-2.5 text-body text-ink transition-colors duration-150 ease-out-quart placeholder:text-ink-faint focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-flag-error';
@endphp

<div>
    {{-- The controller has always flashed these; nothing rendered them, so a
         visitor submitting the form saw no response at all. --}}
    @if (session('success'))
        <p role="status" class="mb-6 rounded-md border border-registry-green bg-registry-tint px-4 py-3 text-body text-registry-green-deep">
            {{ session('success') }}
        </p>
    @endif

    @if (session('error'))
        <p role="alert" class="mb-6 rounded-md border border-flag-error bg-paper px-4 py-3 text-body text-flag-error">
            {{ session('error') }}
        </p>
    @endif

    <form action="{{ route('contact.send') }}" method="POST" class="flex flex-col gap-5">
        @csrf

        <div>
            <label for="name" class="block text-label text-ink">Your name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                   autocomplete="name"
                   @class([$field => ! $errors->has('name'), $fieldError => $errors->has('name')])
                   @if ($errors->has('name')) aria-invalid="true" aria-describedby="name-error" @endif>
            @error('name')
                {{-- Message below the field, not a red border alone: colour is
                     never the only carrier. --}}
                <p id="name-error" class="mt-1.5 text-label text-flag-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-label text-ink">Your email</label>
            <p class="mt-0.5 text-label text-ink-muted">So we can reply. Nothing else.</p>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                   autocomplete="email"
                   @class(['mt-1.5', $field => ! $errors->has('email'), $fieldError => $errors->has('email')])
                   @if ($errors->has('email')) aria-invalid="true" aria-describedby="email-error" @endif>
            @error('email')
                <p id="email-error" class="mt-1.5 text-label text-flag-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="message" class="block text-label text-ink">Message</label>
            <textarea id="message" name="message" rows="6" required maxlength="5000"
                      @class([$field => ! $errors->has('message'), $fieldError => $errors->has('message')])
                      @if ($errors->has('message')) aria-invalid="true" aria-describedby="message-error" @endif>{{ old('message') }}</textarea>
            @error('message')
                <p id="message-error" class="mt-1.5 text-label text-flag-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit"
                    class="inline-flex min-h-11 items-center rounded-md bg-registry-green px-5 py-3 text-label text-paper transition-colors duration-150 ease-out-quart hover:bg-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                Send message
            </button>
        </div>
    </form>
</div>
