<p class="mx-3">check your email for verification</p>
<form method="post" action="{{ route("verification.send") }}">
    @csrf
       
    <button type="submit" class="btn btn-dark">Resend verification link</button>
    @if (session('message'))
    <p>{{ session('message') }}</p>
@endif


</form>

