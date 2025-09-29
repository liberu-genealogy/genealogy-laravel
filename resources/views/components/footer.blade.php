@php
    $settings = app(\App\Settings\GeneralSettings::class);
@endphp
<footer class="mt-auto pb-5 pt-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center flex-wrap">
            <nav class="order-1 lg:order-2 mb-3 lg:mb-0">
                <ul class="flex flex-wrap space-x-4">
                    <li><a href="/about" class="hover:text-gray-300">About Us</a></li>
                    <li><a href="/privacy" class="hover:text-gray-300">Privacy</a></li>
                    <li><a href="/terms-and-conditions" class="hover:text-gray-300">Terms &amp; Conditions</a></li>
                    <li><a href="https://wa.me/442080505865" class="hover:text-gray-300">Contact on WhatsApp</a></li>
                </ul>
            </nav>
            <div class="flex items-center flex-wrap w-full lg:w-auto order-2 lg:order-1 lg:pt-3">
                <div class="mr-6">
                    <a href="/" class="text-lg font-semibold">{{ $settings->site_name }}</a>
                </div>

                <div>
                    <p>{{ $settings->footer_copyright }}</p>
                </div>
            </div>

        </div>

    </div>
</footer>
