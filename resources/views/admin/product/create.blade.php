    @php
        $languages = ['en' => 'English', 'ar' => 'Arabic'];
    @endphp
    <div class="modal modal-blur fade" id="create-product" tabindex="-1" role="dialog" aria-hidden="true"
        data-load-category-url="{{ route('admin.load.category.to.product') }}">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.products.store') }}"
                        id="create-product-form">
                        @csrf
                        <div class="row">
                            @foreach ($languages as $local => $label)
                                <div class="col-lg-4">
                                    <div>
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" placeholder="Enter name in {{ $label }}"
                                            class="form-control" name="name[{{ $local }}]"
                                            id="name[{{ $local }}]">
                                    </div>
                                </div>
                            @endforeach

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Select category</label>
                                    <span id="load-category">
                                        <select name="category_id" id="category_id" class="form-control select2">

                                        </select>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="sku" class="form-label">Sku</label>
                                    <input type="text" name="sku" id="sku" class="form-control"
                                        placeholder="Enter sku">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Product type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="simple">Simple</option>
                                        <option value="variable">Variable</option>
                                        <option value="service">Service</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div>
                                    <label for="weight" class="form-label">Weight</label>
                                    <input type="text" placeholder="Enter weight" class="form-control" name="weight"
                                        id="weight">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div>
                                    <label for="length" class="form-label">Length</label>
                                    <input type="text" placeholder="Enter length" class="form-control" name="length"
                                        id="length">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div>
                                    <label for="length" class="form-label">Width</label>
                                    <input type="text" placeholder="Enter width" class="form-control" name="width"
                                        id="width">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div>
                                    <label for="height" class="form-label">Height</label>
                                    <input type="text" placeholder="Enter height" class="form-control" name="height"
                                        id="height">
                                </div>
                            </div>
                            @foreach ($languages as $local => $label)
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="short-description{{ $local }}" class="form-label">Short
                                            description</label>
                                        <textarea type="text" placeholder="Enter short description in {{ $label }}" class="form-control"
                                            name="short_description[{{ $local }}]" id="short_description[{{ $local }}]" rows="1"></textarea>
                                    </div>
                                </div>
                            @endforeach
                            @foreach ($languages as $local => $label)
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="long_description{{ $local }}" class="form-label">Logn
                                            description</label>

                                        <textarea type="text" placeholder="Enter long description in {{ $label }}" class="form-control"
                                            name="long_description[{{ $local }}]" id="long_description[{{ $local }}]" rows="1"></textarea>
                                    </div>
                                </div>
                            @endforeach

                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="2">Inactive</option>
                                    </select>
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
                        id="store-product">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Store
                    </button>
                </div>
            </div>
        </div>
    </div>
