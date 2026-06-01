@php
    $settings = app(\App\Settings\GeneralSettings::class);
@endphp
<footer class="mt-auto">
    <div class="border-t border-green-800 bg-green-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex flex-col gap-1">
                    <a href="/" class="text-lg font-semibold text-white hover:text-emerald-300 transition-colors">
                        {{ $settings->site_name }}
                    </a>
                    <p class="text-sm text-green-300">{{ $settings->footer_copyright }}</p>
                </div>

                <nav aria-label="Footer navigation">
                    <ul class="flex flex-wrap gap-x-6 gap-y-2 text-sm">
                        <li><a href="/about" class="text-green-200 hover:text-white transition-colors">About Us</a></li>
                        <li><a href="/privacy" class="text-green-200 hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="/terms-and-conditions" class="text-green-200 hover:text-white transition-colors">Terms &amp; Conditions</a></li>
                        <li><a href="/contact" class="text-green-200 hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</footer>
