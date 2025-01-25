<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alugue uma moto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <style>
        input,
        select {
            color: #FFFFFF !important;
        }

        body {
            background-color: #242424;
            font-family: "Roboto", serif;
            color: #FFFFFF;
        }

        .content {
            height: 90dvh;
            padding-top: 30px;
            overflow-y: auto;
            margin-bottom: 2dvh;
        }

        .menu {
            height: 8dvh;
            background-color: #1f1f1f;
            border-top: 1px solid #303030;
            position: fixed;
            width: 100dvw;
        }
    </style>
    @yield('head')
</head>

<body>
    <div class="content">
        @yield('content')
    </div>
    @yield('options-button')
    <div class="menu bottom-0 rounded-top-5 pt-2">
        <div class="row justify-content-evenly justify-content-lg-center g-0 align-items-end gap-lg-5">
            <a href="{{ route('home') }}" class="col-auto text-center text-white text-decoration-none">
                <div style="height: 36px;" class="d-flex align-items-end">
                    <img src="{{ asset('assets/svg/home.svg') }}" alt="" class="d-block mx-auto mb-1 h-auto" style="width: 28px;">
                </div>
                <small>Home</small>
            </a>
            
            <a href="{{ route('vehicle.index') }}" class="col-auto text-center text-white text-decoration-none">
                <div style="height: 36px;" class="d-flex align-items-end">
                    <img src="{{ asset('assets/svg/bike.svg') }}" alt="" class="d-block mx-auto h-auto" style="width: 36px;">
                </div>
                <small>Motocicletas</small>
            </a>
            
            <a href="{{ route('rental.index') }}" class="col-auto text-center text-white text-decoration-none">
                <div style="height: 36px;" class="d-flex align-items-end">
                    <img src="{{ asset('assets/svg/book.svg') }}" alt="" class="d-block mx-auto mb-1 img-fluid" style="width: 26px;">
                </div>
                <small>Locações</small>
            </a>
            
            <a href="{{ route('home') }}" class="col-auto text-center text-white text-decoration-none">
                <div style="height: 36px;" class="d-flex align-items-end">
                    <img src="{{ asset('assets/svg/notification.svg') }}" alt="" class="d-block mx-auto mb-1 img-fluid" style="width: 26px;">
                </div>
                <small>Lembretes</small>
            </a>
            
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    @yield('scripts')
</body>

</html>
