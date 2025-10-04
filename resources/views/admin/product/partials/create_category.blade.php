                   <select name="category_id" id="category_id" class="form-control select2">
                       <option value="0">Main Category</option>
                       @foreach ($getCategories as $category)
                           <option value="{{ $category->id }}">{{ $category->name }}</option>
                           @if ($category['subCategories'])
                               @foreach ($category['subCategories'] as $subCategory)
                                   <option value="{{ $subCategory->id }}">
                                       &nbsp;&nbsp;&nbsp;&nbsp;&raquo;{{ $subCategory->name }}</option>
                                   @if ($subCategory['subCategories'])
                                       @foreach ($subCategory['subCategories'] as $subCat)
                                           <option value="{{ $subCat->id }}">
                                               &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;{{ $subCat->name }}
                                           </option>
                                       @endforeach
                                   @endif
                               @endforeach
                           @endif
                       @endforeach
                   </select>
