    @php
        $languages = ['en' => 'English', 'ar' => 'Arabic'];
    @endphp
    <div class="modal modal-blur fade" id="create-product-variant" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.product-variants.store') }}" id="create-product-variant-form">
                        @csrf
                        <div class="row">

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="product_id" class="form-label">Select product</label>
                                    <select name="product_id" id="product_id" class="form-control select2">
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->getTranslation('name', 'en') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="attribute_value_ids" class="form-label">Select attribute value</label>
                                    <select name="attribute_value_ids[]" id="attribute_value_ids"
                                        class="form-control select2" multiple>
                                        @foreach ($attributeValues as $attributeValue)
                                            <option value="{{ $attributeValue->id }}">
                                                {{ $attributeValue->getTranslation('value', 'en') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="slug" class="form-label">SKU</label>
                                    <input type="text" name="sku" id="sku" class="form-control"
                                        placeholder="Enter sku">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" name="price" id="price" class="form-control"
                                        placeholder="Enter price">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="barcode" class="form-label">Barcode</label>
                                    <input type="text" name="barcode" id="barcode" class="form-control"
                                        placeholder="Enter barcode">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="compare_price" class="form-label">Compare price</label>
                                    <input type="number" name="compare_price" id="compare_price" class="form-control"
                                        placeholder="Enter compare price">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="cost_price" class="form-label">Cost price</label>
                                    <input type="number" name="cost_price" id="cost_price" class="form-control"
                                        placeholder="Enter cost price">
                                </div>
                            </div>
                            {{-- <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="compare_price" class="form-label">Quantity</label>
                                    <input type="number" name="qty" id="qty" class="form-control"
                                        placeholder="Enter quantity">
                                </div>
                            </div> --}}
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="min_order_qty" class="form-label">Min order quantity</label>
                                    <input type="number" name="min_order_qty" id="min_order_qty" class="form-control"
                                        placeholder="Enter min order quantity">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="max_order_price" class="form-label">Max order quantity</label>
                                    <input type="number" name="max_order_qty" id="max_order_qty" class="form-control"
                                        placeholder="Enter max order quantity">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="compare_price" class="form-label">Is track stock</label>
                                    <label for="is_track_stock" class="form-label">
                                        <select name="is_track_stock" id="is_track_stock" class="form-control">
                                            <option value="1">True</option>
                                            <option value="0">false</option>
                                        </select>
                                    </label>

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
                        id="store-product-variant">
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
