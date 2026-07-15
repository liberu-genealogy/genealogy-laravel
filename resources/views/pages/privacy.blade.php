@extends('layouts.home', ['fieldHero' => true])

@php
    // Hardcoded, not date('F j, Y'): that rendered today's date on every request,
    // so the policy claimed it had been updated today, every day, forever — which
    // defeats the only purpose a "last updated" date has. 20 May 2026 is when the
    // file's content actually last changed, per git.
    $lastUpdated = '20 May 2026';
@endphp

@section('content')

<section class="bg-registry-field">
    <div class="mx-auto max-w-6xl px-6 py-16 lg:py-20">
        <h1 class="text-display text-balance text-paper">Privacy Policy</h1>
        <p class="mt-4 text-label text-emerald-100">Last updated: {{ $lastUpdated }}</p>
    </div>
</section>

<section class="border-b border-rule bg-paper">
    <div class="mx-auto max-w-6xl px-6 py-16 lg:py-20">
        {{-- prose now compiles: the typography plugin lives in app.css rather
             than the ignored tailwind.config.js. Clause wording is untouched. --}}
        <div class="prose prose-lg max-w-none">
            <p class="lead">
                <strong>Your privacy matters:</strong> We are committed to protecting your personal information and family data. This policy explains how we collect, use, and safeguard your information.
            </p>

            <h2>1. Information We Collect</h2>
            
            <h3>Personal Information</h3>
            <p>When you create an account with Liberu Genealogy, we collect:</p>
            <ul>
                <li>Name and email address</li>
                <li>Password (encrypted and securely stored)</li>
                <li>Profile information you choose to provide</li>
                <li>Payment information for premium subscriptions (processed securely through Stripe)</li>
            </ul>

            <h3>Family Tree Data</h3>
            <p>As you build your family tree, we store:</p>
            <ul>
                <li>Names, dates, and places for family members</li>
                <li>Relationships between family members</li>
                <li>Photos, documents, and stories you upload</li>
                <li>Source citations and research notes</li>
                <li>DNA data you choose to upload</li>
            </ul>

            <h3>Usage Information</h3>
            <p>We automatically collect certain information about how you use our service:</p>
            <ul>
                <li>Log data (IP address, browser type, pages visited)</li>
                <li>Device information</li>
                <li>Usage patterns and preferences</li>
                <li>Cookies and similar tracking technologies</li>
            </ul>

            <h2>2. How We Use Your Information</h2>
            
            <p>We use your information to:</p>
            <ul>
                <li><strong>Provide our services:</strong> Enable you to create and manage your family tree</li>
                <li><strong>Improve our platform:</strong> Analyze usage patterns to enhance user experience</li>
                <li><strong>Communicate with you:</strong> Send important updates, newsletters, and support messages</li>
                <li><strong>Process payments:</strong> Handle subscription billing and premium features</li>
                <li><strong>Ensure security:</strong> Protect against fraud and unauthorized access</li>
                <li><strong>Legal compliance:</strong> Meet our legal obligations and protect our rights</li>
            </ul>

            <h2>3. Information Sharing and Disclosure</h2>
            
            {{-- Full border + tint, not a border-l-4 stripe (absolute ban). The
                 wording is unchanged; this is the policy's strongest sentence and
                 it keeps its emphasis. --}}
            <div class="not-prose my-8 rounded-md border border-registry-green bg-registry-tint p-6">
                <p class="text-body font-semibold text-registry-green-deep">We do not sell your personal information or family tree data to third parties.</p>
            </div>

            <p>We may share your information only in these limited circumstances:</p>
            
            <h3>With Your Consent</h3>
            <ul>
                <li>When you choose to share your family tree with other users</li>
                <li>When you collaborate on research with family members</li>
                <li>When you export data to other genealogy platforms</li>
            </ul>

            <h3>Service Providers</h3>
            <ul>
                <li>Cloud hosting providers (for secure data storage)</li>
                <li>Payment processors (Stripe for subscription billing)</li>
                <li>Email service providers (for communications)</li>
                <li>Analytics providers (for service improvement)</li>
            </ul>

            <h3>Legal Requirements</h3>
            <p>We may disclose information when required by law, court order, or to protect our rights and the safety of our users.</p>

            <h2>4. Data Security</h2>
            
            <p>We implement industry-standard security measures to protect your information:</p>
            <ul>
                <li><strong>Encryption:</strong> All data is encrypted in transit and at rest</li>
                <li><strong>Access controls:</strong> Strict access controls limit who can view your data</li>
                <li><strong>Regular audits:</strong> We regularly review and update our security practices</li>
                <li><strong>Secure infrastructure:</strong> Our servers are hosted in secure, certified data centers</li>
                <li><strong>Backup systems:</strong> Regular backups ensure your data is protected</li>
            </ul>

            <h2>5. Your Rights and Choices</h2>
            
            <h3>Access and Control</h3>
            <p>You have the right to:</p>
            <ul>
                <li>Access your personal information and family tree data</li>
                <li>Update or correct your information</li>
                <li>Delete your account and associated data</li>
                <li>Export your family tree data in GEDCOM format</li>
                <li>Control who can view your family tree</li>
            </ul>

            <h3>Communication Preferences</h3>
            <p>You can opt out of marketing emails at any time by clicking the unsubscribe link or updating your account preferences.</p>

            <h3>Cookies</h3>
            <p>You can control cookies through your browser settings, though some features may not work properly if cookies are disabled.</p>

            <h2>6. Data Retention</h2>
            
            <p>We retain your information for as long as:</p>
            <ul>
                <li>Your account remains active</li>
                <li>Needed to provide our services</li>
                <li>Required by law or for legitimate business purposes</li>
            </ul>
            
            <p>When you delete your account, we will delete your personal information and family tree data within 30 days, except where retention is required by law.</p>

            <h2>7. International Data Transfers</h2>
            
            <p>Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place to protect your information in accordance with this privacy policy.</p>

            <h2>8. Children's Privacy</h2>
            
            <p>Our service is not intended for children under 13. We do not knowingly collect personal information from children under 13. If you believe we have collected information from a child under 13, please contact us immediately.</p>

            <h2>9. Changes to This Policy</h2>
            
            <p>We may update this privacy policy from time to time. We will notify you of any material changes by email or through our service. Your continued use of our service after changes become effective constitutes acceptance of the updated policy.</p>

            <h2>10. Contact Us</h2>
            
            <p>If you have questions about this privacy policy or our data practices, please contact us:</p>
            
            {{-- FIXME(legal): this address and email are fabricated. SW1A 1AA is
                 Buckingham Palace's postcode and liberu.org.uk does not resolve —
                 the real domain is liberu.co.uk. A UK privacy policy must give a
                 real, reachable controller contact; a GDPR request sent here
                 reaches nobody. Left as-is deliberately: only the operator knows
                 the true entity and address. Styling only below. --}}
            <div class="not-prose rounded-md border border-rule bg-surface-sunk p-6 text-body">
                <p class="text-ink"><strong>Email:</strong> privacy@liberu.org.uk</p>
                <p class="mt-2 text-ink"><strong>Address:</strong> Liberu Genealogy, Privacy Officer</p>
                <p class="mt-2 text-ink">123 Heritage Lane</p>
                <p class="mt-2 text-ink">London, UK SW1A 1AA</p>
            </div>
        </div>
    </div>
</section>
@endsection