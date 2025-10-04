    @php
        $languages = ['en' => 'English', 'ar' => 'Arabic'];
    @endphp
    <div class="modal modal-blur fade" id="create-product-image" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data"
                        action="{{ route('admin.products.images.store', ['product' => $productId]) }}"
                        id="create-product-image-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" name="image[]" id="image" class="form-control" multiple>
                                    <input type="hidden" name="product_id" id="product_id" value="{{ $productId }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="is_primary" class="form-label">Is primary</label>
                                    <select name="is_primary" id="is_parimary" class="form-control">
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="alt_text" class="form-label">Alt text</label>
                                    <input type="text" name="alt_text" id="alt_text" class="form-control"
                                        placeholder="Enter alt text">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="alt_text" class="form-label">sort_order</label>
                                    <input type="text" name="alt_text" id="alt_text" class="form-control"
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
                        id="store-product-image">
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
