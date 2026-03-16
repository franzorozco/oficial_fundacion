<x-guest-layout>

<div style="max-width:400px;margin:auto">

<h2>Verificación en dos pasos</h2>

<p>Revisa tu correo electrónico e ingresa el código.</p>

<form method="POST" action="{{ route('2fa.check') }}">
@csrf

<div>
<label>Código</label>
<input type="text" name="code" required autofocus>
</div>

@if ($errors->any())
<div style="color:red">
{{ $errors->first() }}
</div>
@endif

<button type="submit">
Verificar
</button>

</form>

</div>

</x-guest-layout>