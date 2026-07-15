@php
    $settings = app(\App\Settings\GeneralSettings::class);
@endphp
<footer class="mt-auto bg-ink text-paper">
    <div class="mx-auto max-w-6xl px-6 py-12">
        <div class="flex flex-col gap-8 md:flex-row md:items-start md:justify-between">
            <div class="flex flex-col gap-2">
                <a href="/"
                   class="w-fit rounded-sm text-title text-paper transition-colors duration-150 ease-out-quart hover:text-registry-tint focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint">
                    {{ $settings->site_name }}
                </a>
                <p class="max-w-[42ch] text-label text-slate-400">
                    Free and open source, MIT licensed. Your data leaves whole.
                </p>
            </div>

            <nav aria-label="Footer">
                <ul class="flex flex-wrap gap-x-8 gap-y-3 text-label">
                    <li><a href="/about" class="rounded-sm text-slate-300 transition-colors duration-150 ease-out-quart hover:text-paper focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint">About</a></li>
                    <li><a href="/subscription" class="rounded-sm text-slate-300 transition-colors duration-150 ease-out-quart hover:text-paper focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint">Pricing</a></li>
                    <li><a href="/contact" class="rounded-sm text-slate-300 transition-colors duration-150 ease-out-quart hover:text-paper focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint">Contact</a></li>
                    <li><a href="/privacy" class="rounded-sm text-slate-300 transition-colors duration-150 ease-out-quart hover:text-paper focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint">Privacy</a></li>
                    <li><a href="/terms-and-conditions" class="rounded-sm text-slate-300 transition-colors duration-150 ease-out-quart hover:text-paper focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint">Terms</a></li>
                    <li>
                        <a href="https://github.com/liberu-genealogy/genealogy-laravel"
                           class="rounded-sm text-slate-300 transition-colors duration-150 ease-out-quart hover:text-paper focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint">
                            Source
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <p class="mt-10 border-t border-slate-700 pt-6 text-label text-slate-400">
            {{ $settings->footer_copyright }}
        </p>
    </div>
</footer>
