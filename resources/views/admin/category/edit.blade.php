       @php
           $languages = ['en' => 'English', 'ar' => 'Arabic'];
       @endphp
       <div class="modal modal-blur fade" id="edit-category" tabindex="-1" role="dialog" aria-hidden="true">
           <div class="modal-dialog modal-lg" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title">Edit</h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                   </div>
                   <div class="modal-body">
                       <form enctype="multipart/form-data" id="edit-category-form">
                           @csrf
                           <div class="row">
                               @foreach ($languages as $local => $label)
                                   <div class="col-lg-6">
                                       <div class="mb-3">
                                           <label for="name" class="form-label">Name</label>
                                           <input type="text" placeholder="Enter name in {{ $label }}"
                                               class="form-control" name="name[{{ $local }}]"
                                               id="name[{{ $local }}]">
                                       </div>
                                   </div>
                               @endforeach
                               <div class="col-lg-6">
                                   <div>
                                       <label for="parent_id" class="form-label">Category Level</label>
                                       <span id="parent-edit-select-wrapper">
                                           <select name="parent_id" id="parent_id" class="form-control select2">
                                               <option value="0">Main</option>
                                           </select>
                                       </span>


                                   </div>
                               </div>
                               <div class="col-lg-6">
                                   <div class="mb-3">
                                       <label for="logo" class="form-label">Image</label>
                                       <input type="file" class="form-control" name="image" id="image">
                                       <img id="category-image" alt="category-image" width="100" height="100"
                                           class="img-thumbnail">
                                   </div>
                               </div>

                               @foreach ($languages as $local => $label)
                                   <div class="col-lg-6">
                                       <div class="mb-3">
                                           <label for="name" class="form-label">Description</label>
                                           <textarea name="description[{{ $local }}]" class="form-control" id="description[{{ $local }}]"
                                               placeholder="Enter description in {{ $label }}"></textarea>
                                       </div>
                                   </div>
                               @endforeach
                               <div class="col-lg-6">
                                   <div class="mb-3">
                                       <label for="slug" class="form-label">Slug</label>
                                       <input type="text" class="form-control" name="slug" id="slug"
                                           placeholder="Enter slug">
                                   </div>
                               </div>
                               <div class="col-lg-6">
                                   <div class="mb-3">
                                       <label for="slug" class="form-label">Sort order</label>
                                       <input type="text" class="form-control" name="sort_order" id="sort_order"
                                           placeholder="Enter sort order">
                                   </div>
                               </div>
                               <div class="col-lg-6">
                                   <div>
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
                       <button type="button" class="btn btn-primary ms-auto" data-bs-dismiss="modal"
                           id="update-category">
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
