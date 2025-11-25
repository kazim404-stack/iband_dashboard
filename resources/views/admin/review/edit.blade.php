    @php
        $languages = ['en' => 'English', 'ar' => 'Arabic'];
    @endphp
    <div class="modal modal-blur fade" id="edit-coupon" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form  enctype="multipart/form-data"
                        id="edit-coupon-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="qty" class="form-label">Name</label>
                                    <input type="text" placeholder="Enter name" class="form-control" name="name"
                                        id="name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="discount" class="form-label">Discount</label>
                                    <input type="number" placeholder="Enter discount" class="form-control"
                                        name="discount" id="discount">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="valid_until" class="form-label">Valid until</label>
                                    <input type="datetime-local" class="form-control"
                                        name="valid_until" id="valid_until">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Cancel
                    </a>
                    <button type="button" class="btn btn-primary ms-auto" data-bs-dismiss="modal" id="update-coupon">
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
