
                <select name="category_id" id="category_id" class="form-control select2">
                    <option value="0" {{ $category->parent_id == 0 ? 'selected' : '' }}>Main</option>
                    @foreach ($getCategories as $cat)
                        <option value="{{ $cat->id }}" {{ $cat->id == $category->id ? 'selected' : '' }}>
                            {{ $cat->getTranslation('name', 'en') }}
                        </option>
                        @if ($cat['subCategories'])
                            @foreach ($cat['subCategories'] as $subCategory)
                                <option value="{{ $subCategory->id }}"
                                    {{ $category->id == $subCategory->id ? 'selected' : '' }}>
                                    &nbsp;&nbsp;&nbsp;&raquo; {{ $subCategory->getTranslation('name', 'en') }}
                                </option>
                                @if ($subCategory['subCategories'])
                                    @foreach ($subCategory['subCategories'] as $subCat)
                                        <option value="{{ $subCat->id }}"
                                            {{ $category->id == $subCat->id ? 'selected' : '' }}>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;
                                            {{ $subCat->getTranslation('name', 'en') }}
                                        </option>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </select>
