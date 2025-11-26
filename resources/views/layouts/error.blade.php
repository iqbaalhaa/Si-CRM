<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admindash/assets/compiled/css/app.css') }}">

    <style>
        body {
            font-family: 'Nunito Sans', sans-serif;
            background: #f8f9fa;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .error-container {
            text-align: center;
        }

        .error-code {
            font-size: 120px;
            font-weight: 800;
            color: #ff9c00;
            line-height: 1;
        }

        .error-message {
            font-size: 22px;
            margin-top: 10px;
            color: #555;
        }

        .btn-back {
            margin-top: 25px;
        }
    </style>

</head>

<body>

    <div class="error-container">
        @yield('content')
    </div>

</body>

</html>
