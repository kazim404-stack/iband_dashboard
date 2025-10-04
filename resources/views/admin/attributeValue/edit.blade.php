    @php
        $languages = ['en' => 'English', 'ar' => 'Arabic'];
    @endphp
    <div class="modal modal-blur fade" id="edit-attribute-value" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.attribute-values.store') }}" id="edit-attribute-value-form">
                        @csrf
                        <div class="row">

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="attribute_id" class="form-label">Select attribute</label>
                                    <select name="attribute_id" id="attribute_id" class="form-control select2">

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        placeholder="Enter slug">
                                </div>
                            </div>
                            @foreach ($languages as $local => $lable)
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="value[{{ $local }}]" class="form-label">Value</label>
                                        <input type="text" name="value[{{ $local }}]"
                                            id="value[{{ $local }}]" class="form-control"
                                            placeholder="Enter value in {{ $lable }}">
                                    </div>
                                </div>
                            @endforeach

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort order</label>
                                    <input type="text" name="sort_order" id="sort_order" class="form-control"
                                        placeholder="Enter sort order">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Cancel
                    </a>
                    <button type="button" class="btn btn-primary ms-auto" data-bs-dismiss="modal"
                        id="update-attribute-value">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Update
                    </button>
                </div>
            </div>
        </div>
    </div>
