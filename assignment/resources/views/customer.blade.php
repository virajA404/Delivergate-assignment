<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form method="post" action="{{ route('addcustomers') }}">
        @csrf
        <input type="text" name="customer_name" placeholder="Enter Name">
        <input type="email" name="email" placeholder="Enter Email">
        <input type="text" name="phone" placeholder="Enter Phone">
        <button type="submit">Add</button>
    </form>
</body>
</html>