@extends('admin.login')

@section('admin-auth-content')
<div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="row w-100">
        <div class="col-md-4 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <input type="email" name="email" placeholder="Enter your admin email" required class="form-control">
                        <button type="submit" class="btn btn-primary btn-sm my-2">Send Reset Link</button>
                    </form>

                    @if (session('status'))
                        <div class="alert alert-success mt-2">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mt-2">{{ $errors->first() }}</div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
