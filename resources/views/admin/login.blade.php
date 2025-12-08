<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Admin Login</title>
</head>

<body class="bg-light">
    <div class="container">
        @if (request()->routeIs('admin.login'))
            <div class="row my-5">
                <div class="col-md-6 mx-auto">
                    @session('error')
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endsession
                    <div class="card shadow-sm p-5">
                        <div class="card-header bg-white text-center">
                            <h3 class="mt-2">
                                Login
                            </h3>

                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.auth') }}" method="post">
                                @csrf
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email">
                                    <label for="email">Email address*</label>
                                    @error('email')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password">
                                    <label for="password">Password*</label>
                                    @error('password')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="my-2">
                                    <div class="d-flex justify-content-between">
                                        <button type="submit" class="btn btn-dark">
                                            Sign in
                                        </button>
                                        <a href="{{ route('password.request') }}"
                                            class="text-decoration-none text-dark">forgot password</a>

                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
        @else
            @yield('admin-auth-content')
        @endif


    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>


</body>

</html>
