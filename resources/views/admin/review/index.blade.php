@extends('admin.layouts.admin_master')
@section('content')
    <di class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Reviews</h3>
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
