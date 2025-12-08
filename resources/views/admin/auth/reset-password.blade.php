@extends('admin.login')

@section('admin-auth-content')
    <div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: 100vh">
        <div class="row w-100">
            <div class="col-md-4 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="email" name="email" placeholder="Your email" required class="form-control mb-1">
                            <input type="password" name="password" placeholder="New password" required class="form-control mb-1">
                            <input type="password" name="password_confirmation" placeholder="Confirm password" required class="form-control mb-1">
                            <button type="submit" class="btn btn-primary btn-sm">Reset Password</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>

    @if ($errors->any())
        <div>{{ $errors->first() }}</div>
    @endif
@endsection
