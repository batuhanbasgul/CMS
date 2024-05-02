<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $content->title }}</title>
    <meta name="author" content="Fenrir Software">
    <meta name="description" content="{{ $content->title }}">
    <meta name="keywords" content="Fenrir Software">
	<link rel="icon" href="{{ asset('uploads/media/constant/icon.png') }}" type="image/x-icon" />
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Staatliches&display=swap');
    </style>

    <style>
        .box {
            color: whitesmoke;
            font-family: 'Staatliches', cursive;
            text-align: center;
            font-size: 32px;

            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            position: fixed;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            overflow: auto;
            margin: auto;
        }
    </style>
</head>
<body style="background-color: {{ $content->color }}; background-image:url({{ asset($content->image) }});">
    <div class="box">
        <h1>{{ $content->title }}</h1>
        <p>{{ $content->description }}</p>
        <p>{{ $content->short_description }}</p>
        <h1>{{ $content->start_date }}</h1>
    </div>
</body>
</html>
