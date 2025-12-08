@extends('admin.layouts.admin_master')
@section('content')
    <div class="container">
        <div class="col-8 col-md-8 d-flex flex-column mx-auto">
            <div class="card box-shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.changeProfile.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12 col-md-12 d-flex flex-column">

                            <h2 class="mb-4">My Account</h2>
                            <h3 class="card-title">Profile Details</h3>
                            <div class="row align-items-center">
                                @php $admin = Auth::guard('admin')->user(); @endphp
                                <div class="col-auto">
                                    <span class="avatar avatar-xl"
                                        style="background-image: url('{{ $admin->image ? asset($admin->image) : asset('backend/assets/images/logo.png') }}');">
                                    </span>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-label">Name</div>
                                    <input type="text"
                                        class="form-control @error('name') is-invalid

                                    @enderror"
                                        name="name" value="{{ Auth::guard('admin')->user()->name }}">
                                    @error('name')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-label">Email</div>
                                    <input type="email"
                                        class="form-control @error('email') is-invalid

                                    @enderror"
                                        name="email" value="{{ Auth::guard('admin')->user()->email }}">
                                    @error('email')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-label">Image</div>
                                    <input type="file"
                                        class="form-control @error('image') is-invalid
                                    @enderror"
                                        name="image">
                                    @error('image')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="card-footer bg-transparent mt-auto">
                                <div class="btn-list justify-content-end">
                                    <a href="{{ route('admin.dashboard') }}" class="btn">
                                        Back
                                    </a>
                                    <button type="submit" class="btn btn-primary">Change Profle</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection
