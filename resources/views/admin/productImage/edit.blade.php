    @php
        $languages = ['en' => 'English', 'ar' => 'Arabic'];
    @endphp
    <div class="modal modal-blur fade" id="edit-slider" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form
                        id="edit-slider-form">
                        @csrf
                        <div class="row">
                            @foreach ($languages as $local => $label)
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="title{{ $local }}" class="form-label">Title</label>
                                        <input type="text" placeholder="Enter state in {{ $label }}"
                                            class="form-control" name="title[{{ $local }}]"
                                            id="title[{{ $local }}]">
                                    </div>
                                </div>
                            @endforeach
                            @foreach ($languages as $local => $label)
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="descripiton{{ $local }}"
                                            class="form-label">Description</label>
                                        <textarea placeholder="Enter description in {{ $label }}" class="form-control"
                                            name="description[{{ $local }}]" id="description[{{ $local }}]"></textarea>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control">
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
                    <button type="button" class="btn btn-primary ms-auto" data-bs-dismiss="modal" id="update-slider">
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
