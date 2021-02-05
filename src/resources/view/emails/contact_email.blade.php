@component("mail::message")

# Contact Form Submission

Contact form submitted and the following details mentioned below.

@component('mail::table')
| Key        | Value                        |
|:-----------|:-----------------------------|
@foreach(request()->all() as $key => $value)
| {{ $key }} | {{ $value }}                 |
@endforeach
@endcomponent

Thank you, <br>
{{ config('app.name') }}

@endcomponent
