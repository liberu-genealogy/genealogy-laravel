@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Privacy Policy</h1>
            <p class="text-lg text-gray-600">Last updated: {{ date('F j, Y') }}</p>
        </div>

        <div class="prose prose-lg max-w-none">
            <div class="bg-blue-50 border-l-4 border-blue-400 p-6 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Your Privacy Matters:</strong> We are committed to protecting your personal information and family data. This policy explains how we collect, use, and safeguard your information.
                        </p>
                    </div>
                </div>
            </div>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">1. Information We Collect</h2>
            
            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Personal Information</h3>
            <p class="text-gray-700 mb-4">When you create an account with Liberu Genealogy, we collect:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>Name and email address</li>
                <li>Password (encrypted and securely stored)</li>
                <li>Profile information you choose to provide</li>
                <li>Payment information for premium subscriptions (processed securely through Stripe)</li>
            </ul>

            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Family Tree Data</h3>
            <p class="text-gray-700 mb-4">As you build your family tree, we store:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>Names, dates, and places for family members</li>
                <li>Relationships between family members</li>
                <li>Photos, documents, and stories you upload</li>
                <li>Source citations and research notes</li>
                <li>DNA data you choose to upload</li>
            </ul>

            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Usage Information</h3>
            <p class="text-gray-700 mb-4">We automatically collect certain information about how you use our service:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>Log data (IP address, browser type, pages visited)</li>
                <li>Device information</li>
                <li>Usage patterns and preferences</li>
                <li>Cookies and similar tracking technologies</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">2. How We Use Your Information</h2>
            
            <p class="text-gray-700 mb-4">We use your information to:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li><strong>Provide our services:</strong> Enable you to create and manage your family tree</li>
                <li><strong>Improve our platform:</strong> Analyze usage patterns to enhance user experience</li>
                <li><strong>Communicate with you:</strong> Send important updates, newsletters, and support messages</li>
                <li><strong>Process payments:</strong> Handle subscription billing and premium features</li>
                <li><strong>Ensure security:</strong> Protect against fraud and unauthorized access</li>
                <li><strong>Legal compliance:</strong> Meet our legal obligations and protect our rights</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">3. Information Sharing and Disclosure</h2>
            
            <div class="bg-green-50 border-l-4 border-green-400 p-6 mb-6">
                <p class="text-green-700"><strong>We do not sell your personal information or family tree data to third parties.</strong></p>
            </div>

            <p class="text-gray-700 mb-4">We may share your information only in these limited circumstances:</p>
            
            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">With Your Consent</h3>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>When you choose to share your family tree with other users</li>
                <li>When you collaborate on research with family members</li>
                <li>When you export data to other genealogy platforms</li>
            </ul>

            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Service Providers</h3>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>Cloud hosting providers (for secure data storage)</li>
                <li>Payment processors (Stripe for subscription billing)</li>
                <li>Email service providers (for communications)</li>
                <li>Analytics providers (for service improvement)</li>
            </ul>

            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Legal Requirements</h3>
            <p class="text-gray-700 mb-6">We may disclose information when required by law, court order, or to protect our rights and the safety of our users.</p>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">4. Data Security</h2>
            
            <p class="text-gray-700 mb-4">We implement industry-standard security measures to protect your information:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li><strong>Encryption:</strong> All data is encrypted in transit and at rest</li>
                <li><strong>Access controls:</strong> Strict access controls limit who can view your data</li>
                <li><strong>Regular audits:</strong> We regularly review and update our security practices</li>
                <li><strong>Secure infrastructure:</strong> Our servers are hosted in secure, certified data centers</li>
                <li><strong>Backup systems:</strong> Regular backups ensure your data is protected</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">5. Your Rights and Choices</h2>
            
            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Access and Control</h3>
            <p class="text-gray-700 mb-4">You have the right to:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>Access your personal information and family tree data</li>
                <li>Update or correct your information</li>
                <li>Delete your account and associated data</li>
                <li>Export your family tree data in GEDCOM format</li>
                <li>Control who can view your family tree</li>
            </ul>

            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Communication Preferences</h3>
            <p class="text-gray-700 mb-6">You can opt out of marketing emails at any time by clicking the unsubscribe link or updating your account preferences.</p>

            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Cookies</h3>
            <p class="text-gray-700 mb-6">You can control cookies through your browser settings, though some features may not work properly if cookies are disabled.</p>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">6. Data Retention</h2>
            
            <p class="text-gray-700 mb-4">We retain your information for as long as:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>Your account remains active</li>
                <li>Needed to provide our services</li>
                <li>Required by law or for legitimate business purposes</li>
            </ul>
            
            <p class="text-gray-700 mb-6">When you delete your account, we will delete your personal information and family tree data within 30 days, except where retention is required by law.</p>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">7. International Data Transfers</h2>
            
            <p class="text-gray-700 mb-6">Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place to protect your information in accordance with this privacy policy.</p>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">8. Children's Privacy</h2>
            
            <p class="text-gray-700 mb-6">Our service is not intended for children under 13. We do not knowingly collect personal information from children under 13. If you believe we have collected information from a child under 13, please contact us immediately.</p>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">9. Changes to This Policy</h2>
            
            <p class="text-gray-700 mb-6">We may update this privacy policy from time to time. We will notify you of any material changes by email or through our service. Your continued use of our service after changes become effective constitutes acceptance of the updated policy.</p>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">10. Contact Us</h2>
            
            <p class="text-gray-700 mb-4">If you have questions about this privacy policy or our data practices, please contact us:</p>
            
            <div class="bg-gray-50 p-6 rounded-lg">
                <p class="text-gray-700 mb-2"><strong>Email:</strong> privacy@liberu.org.uk</p>
                <p class="text-gray-700 mb-2"><strong>Address:</strong> Liberu Genealogy, Privacy Officer</p>
                <p class="text-gray-700 mb-2">123 Heritage Lane</p>
                <p class="text-gray-700">London, UK SW1A 1AA</p>
            </div>

            <div class="mt-12 p-6 bg-blue-50 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">Your Trust is Our Priority</h3>
                <p class="text-blue-800">We understand that your family history is deeply personal and important to you. We are committed to maintaining the highest standards of privacy and security to protect your information and preserve your family's legacy for future generations.</p>
            </div>
        </div>
    </div>
</div>
@endsection