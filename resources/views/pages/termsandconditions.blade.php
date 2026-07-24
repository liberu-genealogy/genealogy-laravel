@extends('layouts.home', ['fieldHero' => true])

@php
    // Hardcoded, not date('F j, Y'): that rendered today's date on every request,
    // so the terms claimed they had been updated today, every day. 20 May 2026 is
    // when the file's content actually last changed, per git.
    $lastUpdated = '20 May 2026';

    // §6 stated "£4.99" and a "7-day" trial. Both were false — the app charges
    // $2.99 on a 14-day trial. Read from the same config as the pricing page and
    // Cashier so the terms cannot drift away from what is actually billed.
    $price = app(\App\Services\SubscriptionService::class)->formatPrice('month');
    $interval = 'month';
    $trialDays = (int) config('subscription.premium.trial_days', 14);
@endphp

@section('content')

<section class="bg-registry-field">
    <div class="mx-auto max-w-6xl px-6 py-16 lg:py-20">
        <h1 class="text-display text-balance text-paper">Terms and Conditions</h1>
        <p class="mt-4 text-label text-emerald-100">Last updated: {{ $lastUpdated }}</p>
    </div>
</section>

<section class="border-b border-rule bg-paper">
    <div class="mx-auto max-w-6xl px-6 py-16 lg:py-20">
        {{-- Clause wording is untouched throughout; only presentation and the
             two demonstrably false numbers in §6 changed. --}}
        <div class="prose prose-lg max-w-none">
            <p class="lead">
                <strong>Important:</strong> By using Liberu Genealogy, you agree to these terms and conditions. Please read them carefully before using our service.
            </p>

            <h2>1. Acceptance of Terms</h2>
            
            <p>By accessing and using Liberu Genealogy ("the Service"), you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>

            <h2>2. Description of Service</h2>
            
            <p>Liberu Genealogy provides:</p>
            <ul>
                <li>Online family tree building and management tools</li>
                <li>DNA analysis and matching services</li>
                <li>Research tools and collaboration features</li>
                <li>Data storage and backup services</li>
                <li>Premium features for enhanced genealogy research</li>
            </ul>

            <h2>3. User Accounts</h2>
            
            <h3>Account Creation</h3>
            <p>To use our service, you must:</p>
            <ul>
                <li>Be at least 13 years of age</li>
                <li>Provide accurate and complete registration information</li>
                <li>Maintain the security of your password and account</li>
                <li>Accept responsibility for all activities under your account</li>
            </ul>

            <h3>Account Responsibilities</h3>
            <p>You are responsible for:</p>
            <ul>
                <li>Keeping your login credentials secure</li>
                <li>Notifying us immediately of any unauthorized use</li>
                <li>Ensuring all information you provide is accurate</li>
                <li>Complying with all applicable laws and regulations</li>
            </ul>

            <h2>4. Acceptable Use Policy</h2>
            
            <h3>Permitted Uses</h3>
            <p>You may use our service to:</p>
            <ul>
                <li>Create and maintain your family tree</li>
                <li>Research your family history</li>
                <li>Share information with family members</li>
                <li>Collaborate on genealogy research</li>
                <li>Upload and analyze DNA data</li>
            </ul>

            <h3>Prohibited Uses</h3>
            <p>You may not:</p>
            <ul>
                <li>Upload false, misleading, or inaccurate information</li>
                <li>Violate any person's privacy or rights</li>
                <li>Use the service for commercial purposes without permission</li>
                <li>Attempt to gain unauthorized access to our systems</li>
                <li>Upload malicious software or harmful content</li>
                <li>Harass, abuse, or harm other users</li>
                <li>Violate any applicable laws or regulations</li>
            </ul>

            <h2>5. Content and Data</h2>
            
            <h3>Your Content</h3>
            <p>You retain ownership of all content you upload to our service, including:</p>
            <ul>
                <li>Family tree data and relationships</li>
                <li>Photos, documents, and media files</li>
                <li>Stories, notes, and research findings</li>
                <li>DNA data and analysis results</li>
            </ul>

            <h3>License to Use</h3>
            <p>By uploading content, you grant us a limited, non-exclusive license to store, process, and display your content solely for the purpose of providing our service to you.</p>

            <h3>Content Standards</h3>
            <p>All content must:</p>
            <ul>
                <li>Be accurate to the best of your knowledge</li>
                <li>Respect the privacy and rights of others</li>
                <li>Comply with applicable laws and regulations</li>
                <li>Not contain offensive or inappropriate material</li>
            </ul>

            <h2>6. Premium Subscriptions</h2>
            
            <h3>Subscription Terms</h3>
            <p>Premium subscriptions:</p>
            <ul>
                <li>Are billed at {{ $price }} per {{ $interval }}</li>
                <li>Include a {{ $trialDays }}-day free trial for new subscribers</li>
                <li>Automatically renew unless cancelled</li>
                <li>Can be cancelled at any time</li>
                <li>Provide access to premium features during active subscription</li>
            </ul>

            <h3>Payment and Billing</h3>
            <p>By subscribing to premium features:</p>
            <ul>
                <li>You authorize us to charge your payment method</li>
                <li>You agree to pay all charges incurred</li>
                <li>You are responsible for keeping payment information current</li>
                <li>Refunds are provided according to our refund policy</li>
            </ul>

            <h3>Cancellation</h3>
            <p>You may cancel your subscription at any time. Upon cancellation, you will continue to have access to premium features until the end of your current billing period.</p>

            <h2>7. Privacy and Data Protection</h2>
            
            <p>Your privacy is important to us. Our collection and use of personal information is governed by our Privacy Policy, which is incorporated into these terms by reference.</p>

            <h2>8. Intellectual Property</h2>
            
            <h3>Our Rights</h3>
            <p>The Liberu Genealogy service, including its software, design, and content, is protected by copyright, trademark, and other intellectual property laws. You may not copy, modify, or distribute our proprietary content without permission.</p>

            <h3>Your Rights</h3>
            <p>You retain all rights to your family tree data and content. You may export your data at any time in standard GEDCOM format.</p>

            <h2>9. Service Availability</h2>
            
            <p>We strive to provide reliable service, but we cannot guarantee:</p>
            <ul>
                <li>100% uptime or uninterrupted access</li>
                <li>Error-free operation</li>
                <li>Compatibility with all devices or browsers</li>
                <li>Permanent availability of all features</li>
            </ul>

            <h2>10. Limitation of Liability</h2>
            
            {{-- Full border, not a border-l-4 stripe (absolute ban). Wording unchanged. --}}
            <div class="not-prose my-8 rounded-md border border-flag-error p-6">
                <p class="text-body text-flag-error"><strong>Important:</strong> Our liability is limited as described below. Please read this section carefully.</p>
            </div>

            <p>To the maximum extent permitted by law:</p>
            <ul>
                <li>We provide our service "as is" without warranties</li>
                <li>We are not liable for indirect, incidental, or consequential damages</li>
                <li>Our total liability is limited to the amount you paid for our service</li>
                <li>We are not responsible for data loss due to user error or technical issues</li>
            </ul>

            <h2>11. Indemnification</h2>
            
            <p>You agree to indemnify and hold us harmless from any claims, damages, or expenses arising from your use of our service, violation of these terms, or infringement of any rights of another person or entity.</p>

            <h2>12. Termination</h2>
            
            <h3>Termination by You</h3>
            <p>You may terminate your account at any time by contacting us or using the account deletion feature in your settings.</p>

            <h3>Termination by Us</h3>
            <p>We may terminate or suspend your account if you:</p>
            <ul>
                <li>Violate these terms and conditions</li>
                <li>Engage in fraudulent or illegal activities</li>
                <li>Abuse our service or other users</li>
                <li>Fail to pay subscription fees</li>
            </ul>

            <h2>13. Changes to Terms</h2>
            
            <p>We may update these terms from time to time. We will notify you of material changes by email or through our service. Your continued use after changes become effective constitutes acceptance of the updated terms.</p>

            <h2>14. Governing Law</h2>
            
            <p>These terms are governed by the laws of England and Wales. Any disputes will be resolved in the courts of England and Wales.</p>

            <h2>15. Contact Information</h2>
            
            <p>If you have questions about these terms, please contact us:</p>
            
            <div class="not-prose rounded-md border border-rule bg-surface-sunk p-6 text-body">
                {{-- FIXME(legal): fabricated, same as the privacy policy. SW1A 1AA
                     is Buckingham Palace's postcode and liberu.org.uk does not
                     resolve (the real domain is liberu.co.uk). Left as-is
                     deliberately: only the operator knows the true entity and
                     address. Styling only. --}}
                <p class="text-ink"><strong>Email:</strong> legal@liberu.org.uk</p>
                <p class="mt-2 text-ink"><strong>Address:</strong> Liberu Genealogy, Legal Department</p>
                <p class="mt-2 text-ink">123 Heritage Lane</p>
                <p class="mt-2 text-ink">London, UK SW1A 1AA</p>
            </div>
        </div>
    </div>
</section>
@endsection