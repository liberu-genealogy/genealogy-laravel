@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Terms and Conditions</h1>
            <p class="text-lg text-gray-600">Last updated: {{ date('F j, Y') }}</p>
        </div>

        <div class="prose prose-lg max-w-none">
            <div class="bg-amber-50 border-l-4 border-amber-400 p-6 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-amber-700">
                            <strong>Important:</strong> By using Liberu Genealogy, you agree to these terms and conditions. Please read them carefully before using our service.
                        </p>
                    </div>
                </div>
            </div>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">1. Acceptance of Terms</h2>
            
            <p class="text-gray-700 mb-6">By accessing and using Liberu Genealogy ("the Service"), you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">2. Description of Service</h2>
            
            <p class="text-gray-700 mb-4">Liberu Genealogy provides:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>Online family tree building and management tools</li>
                <li>DNA analysis and matching services</li>
                <li>Research tools and collaboration features</li>
                <li>Data storage and backup services</li>
                <li>Premium features for enhanced genealogy research</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">3. User Accounts</h2>
            
            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Account Creation</h3>
            <p class="text-gray-700 mb-4">To use our service, you must:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>Be at least 13 years of age</li>
                <li>Provide accurate and complete registration information</li>
                <li>Maintain the security of your password and account</li>
                <li>Accept responsibility for all activities under your account</li>
            </ul>

            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Account Responsibilities</h3>
            <p class="text-gray-700 mb-4">You are responsible for:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>Keeping your login credentials secure</li>
                <li>Notifying us immediately of any unauthorized use</li>
                <li>Ensuring all information you provide is accurate</li>
                <li>Complying with all applicable laws and regulations</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">4. Acceptable Use Policy</h2>
            
            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Permitted Uses</h3>
            <p class="text-gray-700 mb-4">You may use our service to:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>Create and maintain your family tree</li>
                <li>Research your family history</li>
                <li>Share information with family members</li>
                <li>Collaborate on genealogy research</li>
                <li>Upload and analyze DNA data</li>
            </ul>

            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Prohibited Uses</h3>
            <p class="text-gray-700 mb-4">You may not:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>Upload false, misleading, or inaccurate information</li>
                <li>Violate any person's privacy or rights</li>
                <li>Use the service for commercial purposes without permission</li>
                <li>Attempt to gain unauthorized access to our systems</li>
                <li>Upload malicious software or harmful content</li>
                <li>Harass, abuse, or harm other users</li>
                <li>Violate any applicable laws or regulations</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">5. Content and Data</h2>
            
            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Your Content</h3>
            <p class="text-gray-700 mb-4">You retain ownership of all content you upload to our service, including:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>Family tree data and relationships</li>
                <li>Photos, documents, and media files</li>
                <li>Stories, notes, and research findings</li>
                <li>DNA data and analysis results</li>
            </ul>

            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">License to Use</h3>
            <p class="text-gray-700 mb-6">By uploading content, you grant us a limited, non-exclusive license to store, process, and display your content solely for the purpose of providing our service to you.</p>

            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Content Standards</h3>
            <p class="text-gray-700 mb-4">All content must:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>Be accurate to the best of your knowledge</li>
                <li>Respect the privacy and rights of others</li>
                <li>Comply with applicable laws and regulations</li>
                <li>Not contain offensive or inappropriate material</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">6. Premium Subscriptions</h2>
            
            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Subscription Terms</h3>
            <p class="text-gray-700 mb-4">Premium subscriptions:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>Are billed monthly at £4.99 per month</li>
                <li>Include a 7-day free trial for new subscribers</li>
                <li>Automatically renew unless cancelled</li>
                <li>Can be cancelled at any time</li>
                <li>Provide access to premium features during active subscription</li>
            </ul>

            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Payment and Billing</h3>
            <p class="text-gray-700 mb-4">By subscribing to premium features:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>You authorize us to charge your payment method</li>
                <li>You agree to pay all charges incurred</li>
                <li>You are responsible for keeping payment information current</li>
                <li>Refunds are provided according to our refund policy</li>
            </ul>

            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Cancellation</h3>
            <p class="text-gray-700 mb-6">You may cancel your subscription at any time. Upon cancellation, you will continue to have access to premium features until the end of your current billing period.</p>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">7. Privacy and Data Protection</h2>
            
            <p class="text-gray-700 mb-6">Your privacy is important to us. Our collection and use of personal information is governed by our Privacy Policy, which is incorporated into these terms by reference.</p>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">8. Intellectual Property</h2>
            
            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Our Rights</h3>
            <p class="text-gray-700 mb-6">The Liberu Genealogy service, including its software, design, and content, is protected by copyright, trademark, and other intellectual property laws. You may not copy, modify, or distribute our proprietary content without permission.</p>

            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Your Rights</h3>
            <p class="text-gray-700 mb-6">You retain all rights to your family tree data and content. You may export your data at any time in standard GEDCOM format.</p>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">9. Service Availability</h2>
            
            <p class="text-gray-700 mb-4">We strive to provide reliable service, but we cannot guarantee:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>100% uptime or uninterrupted access</li>
                <li>Error-free operation</li>
                <li>Compatibility with all devices or browsers</li>
                <li>Permanent availability of all features</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">10. Limitation of Liability</h2>
            
            <div class="bg-red-50 border-l-4 border-red-400 p-6 mb-6">
                <p class="text-red-700"><strong>Important:</strong> Our liability is limited as described below. Please read this section carefully.</p>
            </div>

            <p class="text-gray-700 mb-4">To the maximum extent permitted by law:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>We provide our service "as is" without warranties</li>
                <li>We are not liable for indirect, incidental, or consequential damages</li>
                <li>Our total liability is limited to the amount you paid for our service</li>
                <li>We are not responsible for data loss due to user error or technical issues</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">11. Indemnification</h2>
            
            <p class="text-gray-700 mb-6">You agree to indemnify and hold us harmless from any claims, damages, or expenses arising from your use of our service, violation of these terms, or infringement of any rights of another person or entity.</p>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">12. Termination</h2>
            
            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Termination by You</h3>
            <p class="text-gray-700 mb-6">You may terminate your account at any time by contacting us or using the account deletion feature in your settings.</p>

            <h3 class="text-xl font-medium text-gray-800 mt-6 mb-3">Termination by Us</h3>
            <p class="text-gray-700 mb-4">We may terminate or suspend your account if you:</p>
            <ul class="list-disc pl-6 mb-6 text-gray-700">
                <li>Violate these terms and conditions</li>
                <li>Engage in fraudulent or illegal activities</li>
                <li>Abuse our service or other users</li>
                <li>Fail to pay subscription fees</li>
            </ul>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">13. Changes to Terms</h2>
            
            <p class="text-gray-700 mb-6">We may update these terms from time to time. We will notify you of material changes by email or through our service. Your continued use after changes become effective constitutes acceptance of the updated terms.</p>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">14. Governing Law</h2>
            
            <p class="text-gray-700 mb-6">These terms are governed by the laws of England and Wales. Any disputes will be resolved in the courts of England and Wales.</p>

            <h2 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">15. Contact Information</h2>
            
            <p class="text-gray-700 mb-4">If you have questions about these terms, please contact us:</p>
            
            <div class="bg-gray-50 p-6 rounded-lg">
                <p class="text-gray-700 mb-2"><strong>Email:</strong> legal@liberu.org.uk</p>
                <p class="text-gray-700 mb-2"><strong>Address:</strong> Liberu Genealogy, Legal Department</p>
                <p class="text-gray-700 mb-2">123 Heritage Lane</p>
                <p class="text-gray-700">London, UK SW1A 1AA</p>
            </div>

            <div class="mt-12 p-6 bg-emerald-50 rounded-lg">
                <h3 class="text-lg font-semibold text-emerald-900 mb-3">Thank You for Choosing Liberu Genealogy</h3>
                <p class="text-emerald-800">We appreciate your trust in our service to help preserve and explore your family history. These terms help ensure a safe and positive experience for all our users.</p>
            </div>
        </div>
    </div>
</div>
@endsection