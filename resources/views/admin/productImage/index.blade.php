@extends('admin.layouts.admin_master')
@section('content')
    @include('admin.productImage.create')

    <di class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Images</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#create-product-image"><i class="fa fa-plus font fs-2">&nbsp;</i>Create</button>
                </div>
                <div class="card-body">
                    <tbody class="table-responsive">
                        <div class="row">

                            @foreach ($productImages as $productImage)
                                <div class="col-md-3 text-center" id="image-{{ $productImage->id }}">
                                    <div class="slider-image position-relative">
                                        <img class="rounded-2" src="{{ asset($productImage->image) }}"
                                            alt="slider-image-{{ $productImage->id }}" width="200">

                                        <a href="{{ route('admin.products.images.destroy', ['product' => $productId, 'image' => $productImage->id]) }}"
                                            class="product-image-delete position-absolute"
                                            data-image-id="{{ $productImage->id }}"
                                            style="left: 60px; top:3px; border: none;">
                                            <i class="fas fa-trash text-danger"></i>
                                        </a>

                                        <span class="position-absolute" style="top: 3px; right: 60px;">
                                            {!! $productImage->is_primary == 1
                                                ? '<span class="badge bg-success text-white">Yes</span>'
                                                : '<span class="badge bg-warning text-white">No</span>' !!}
                                        </span>
                                    </div>
                                </div>
                            @endforeach


                        </div>

                    </tbody>

                </div>
            </div>
        </div>
    </di>
@endsection
