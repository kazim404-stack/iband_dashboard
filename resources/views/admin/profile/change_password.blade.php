@extends('admin.layouts.admin_master')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card box-shadow-sm">
                    <div class="card-header">
                        <h4>Change password</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.changePassword.update') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label required">Current password</label>
                                <div>
                                    <input type="password" name="current_password" value="{{ @old('password') }}"
                                        class="form-control @error('current_password') is-invalid
                                    @enderror"
                                        aria-describedby="emailHelp" placeholder="Enter Current password">
                                    @error('current_password')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">New Password</label>
                                <div>
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid

                                    @enderror"
                                        placeholder="Password">
                                    @error('password')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Confirm password</label>
                                <div>
                                    <input type="password"
                                        class="form-control @error('password_confirmation') is-invalid

                                    @enderror"
                                        placeholder="Password" name="password_confirmation">
                                    @error('password_confirmation')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
