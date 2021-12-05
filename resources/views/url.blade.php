<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Played Cards</title>

    <style>
    </style>
</head>
<body>
<h1>Enter the game URL</h1>
<div>
    <form action="/cards" method="GET" enctype="multipart/form-data">
        @csrf
        URL: <input type="text" name="url"><br>
        <input type="submit">
        @if(isset($error))
            <div>{{$error}}</div>
        @endif
    </form>
</div>
</body>
</html>
