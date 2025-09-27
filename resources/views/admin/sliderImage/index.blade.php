@extends('admin.layouts.admin_master')
@section('content')
    @include('admin.sliderImage.create')
    @include('admin.sliderImage.edit')
    <di class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Slider images</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#create-slider-image"><i class="fa fa-plus font fs-2">&nbsp;</i>Create</button>
                </div>
                <div class="card-body">
                    <table>
                        <tbody class="table-responsive">
                            <div class="row">
                                @foreach ($sliderImages as $sliderImage)
                                    <div class="col-md-3 text-center">
                                        <div class="slider-image position-relative">
                                            <img class="rounded-2" src="{{ asset($sliderImage->image) }}"
                                                alt="slider-image-{{ $sliderImage->id }}" width="200">
                                            <form action="{{ route('admin.slider-images.destroy', $sliderImage->id) }}"
                                                method="post">
                                                @method('delete')
                                                @csrf
                                                <button type="submit" class="position-absolute"
                                                    onclick="alert('Are you sure!')"
                                                    style="left: 60px;top:3px; border: none;"><i
                                                        class="fas fa-trash text-danger"></i></button>
                                            </form>
                                            <span class="position-absolute badge bage bg-primary text-light"
                                                style="top: 3px; right: 60px;">{{ $sliderImage->type }}</span>
                                        </div>


                                    </div>
                                @endforeach

                            </div>

                        </tbody>
                    </table>



                </div>
            </div>
        </div>
    </di>
@endsection
