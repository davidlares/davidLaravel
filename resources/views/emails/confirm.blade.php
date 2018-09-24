Hello, {{$user->name}}
We detect that you changed your email address. Please verify it using the following link

{{ route('verify', $user->verification_token) }}
