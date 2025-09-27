@extends('admin.layouts.admin_master')
@section('content')
    @include('admin.phone.create')
    @include('admin.phone.edit')
    <di class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Phones</h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#create-phone"><i class="fa fa-plus font fs-2">&nbsp;</i>Create</button>
                </div>
                <div class="card-body">
                    <table>
                        <tbody class="table-responsive">

                            {{ $dataTable->table(['class' => 'table table-striped table-hover table-responsive w-100']) }}

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </di>
@endsection
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
