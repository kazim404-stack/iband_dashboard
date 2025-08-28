    <div class="modal modal-blur fade" id="edit-general-setting" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form
                     enctype="multipart/form-data" id="edit-general-setting-form">
                     @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="site_name" class="form-label">Site Name</label>
                                    <input type="text" placeholder="Enter site name" class="form-control"
                                        name="site_name" id="site_name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Logo</label>
                                    <input type="file" class="form-control" name="logo" id="logo">
                                    <img  alt="logo" id="show-logo">

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="facebook" class="form-label">Facebook</label>
                                    <input type="text" placeholder="Enter facebook url" class="form-control"
                                        name="facebook" id="facebook">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="instagram" class="form-label">Instagram</label>
                                    <input type="text" placeholder="Enter instagram url" class="form-control"
                                        name="instagram" id="instagram">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="whatsapp" class="form-label">whatsapp</label>
                                    <input type="text" placeholder="Enter whatsapp url" class="form-control"
                                        name="whatsapp" id="whatsapp">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="telegram" class="form-label">Telegram</label>
                                    <input type="text" placeholder="Enter telegram url" class="form-control"
                                        name="telegram" id="telegram">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="telegram" class="form-label">X</label>
                                    <input type="text" placeholder="Enter x url" class="form-control" name="x"
                                        id="x">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="telegram" class="form-label">Linkdin</label>
                                    <input type="text" placeholder="Enter linkedin url" class="form-control"
                                        name="linkedin" id="linkedin">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label for="youtube" class="form-label">Youtube</label>
                                    <input type="text" placeholder="Enter youtube url" class="form-control"
                                        name="youtube" id="youtube">
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
                        id="update-general-setting">
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
