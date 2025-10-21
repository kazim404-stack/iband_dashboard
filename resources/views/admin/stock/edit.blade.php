    @php
        $languages = ['en' => 'English', 'ar' => 'Arabic'];
    @endphp
    <div class="modal modal-blur fade" id="edit-stock" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-stock-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="site_name" class="form-label">Select product</label>
                                    <select name="product_variant_id" id="product_variant_id"
                                        class="form-control select2">


                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="currency_id" class="form-label">Select currency</label>
                                    <select name="currency_id" id="currency_id" class="form-control">
                                        {{-- @if ($currencies->count() > 0)
                                            @foreach ($currencies as $currency)
                                                <option value="{{ $currency->id }}">
                                                    {{ $currency->code }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="">No currency found</option>
                                        @endif --}}
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="qty" class="form-label">Quantity</label>
                                    <input type="number" placeholder="Enter quantity" class="form-control"
                                        name="qty" id="qty">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div>
                                    <label for="email" class="form-label">Stock status</label>
                                    <select name="stock_status" id="stock_status" class="form-control">
                                        <option value="in_stock">in_stock</option>
                                        <option value="out_of_stock">out_of_stock</option>
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
                    <button type="button" class="btn btn-primary ms-auto" data-bs-dismiss="modal" id="update-stock">
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
