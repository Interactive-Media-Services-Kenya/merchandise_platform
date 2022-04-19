@component('mail::message')
# {{ $details['title'] }}
{{ $details['body'] }}
{{-- @component('mail::button', ['url' => $maildata['url']])
Verify
@endcomponent --}}
Thanks,<br>
{{ config('app.name') }}
@endcomponent
