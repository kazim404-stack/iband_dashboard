    @php
        $languages = ['en' => 'English', 'ar' => 'Arabic'];
    @endphp
    <div class="modal modal-blur fade" id="edit-contact" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form  enctype="multipart/form-data"
                        id="edit-contact-form">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="site_name" class="form-label">General setting</label>
                                    <select name="general_setting_id" id="general_setting_id" class="form-control">
                                        <option value="{{ $generalSetting->id }}">{{ $generalSetting->site_name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            @foreach ($languages as $local => $label)
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="state{{ $local }}" class="form-label">State</label>
                                        <input type="text" placeholder="Enter state in {{ $label }}"
                                            class="form-control" name="state[{{ $local }}]"
                                            id="state[{{ $local }}]">
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-lg-6">
                                <div>
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" placeholder="Enter email address" class="form-control"
                                        name="email" id="email">
                                </div>
                            </div>
                            @foreach ($languages as $local => $label)
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="address{{ $local }}" class="form-label">Address</label>
                                        <textarea placeholder="Enter address in {{ $label }}" class="form-control" name="address[{{ $local }}]"
                                            id="address[{{ $local }}]"></textarea>

                                    </div>
                                </div>
                            @endforeach
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="is_primary" class="form-label">Is primary</label>
                                    <select name="is_primary" id="is_primary" class="form-control">
                                        <option value="1">True</option>
                                        <option value="0">False</option>
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
                    <button type="button" class="btn btn-primary ms-auto" data-bs-dismiss="modal" id="update-contact">
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
