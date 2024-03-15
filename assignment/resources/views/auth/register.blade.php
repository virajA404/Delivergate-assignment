<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
</head>
<body>
    {{-- @foreach($errors->all() as $error)
    <div class="alert alert-danger" role="alert">
        {{ $error }}
    </div>
    @endforeach --}}

    <form method="post" action="{{ route('saveRegister') }}">
    @csrf
        <div>
            <label for="name">Name:</label>
            <input type="text" name="name">
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label for="type">Choose Your Role:</label> 
            <select name="type" id="type"> 
                <option name="type" value="0">Owner</option> 
                <option name="type" value="1">Manager</option> 
                <option name="type" value="2">Cashier</option> 
            </select>
            @error('type')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email">
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password">
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label for="confirm-password">Confirm Password:</label>
            <input type="password" name="password_confirmation">
            @error('confirm_password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit">Register</button>
        <br>
        <a href="{{ route('login') }}">Already have an account? Login</a>
    </form>
</body>
</html>