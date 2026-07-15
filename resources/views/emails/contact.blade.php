{{-- Plain and unstyled on purpose: this lands in an inbox, not a browser, and
     the reply-to carries the visitor's address. --}}
Sent from the contact form on {{ config('app.url') }}

Name:  {{ $data['name'] }}
Email: {{ $data['email'] }}

Message:

{{ $data['message'] }}
