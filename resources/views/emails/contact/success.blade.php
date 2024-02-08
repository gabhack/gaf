@component('mail::message')

<b>Nombre:</b> {{ $contact->firstName }} <br>
<b>Apellido:</b> {{ $contact->lastName }} <br>
<b>Email:</b> {{ $contact->email }} <br>
<b>Teléfono:</b> {{ $contact->phone }} <br>
<b>Empresa:</b> {{ $contact->company }} <br>
<b>Cargo:</b> {{ $contact->position }} <br>
<b>Mensaje:</b> {{ $contact->comment }} <br>

@endcomponent
