Hello, {{$user->name}}
Thanks for creating an account with us. Please verify it using the following link

{{ route('verify', $user->verification_token) }}
