       <select name="attribute_value_ids[]" id="attribute_value_ids" class="form-control select2" multiple>
           @foreach ($attributeValues as $attributeValue)
               <option value="{{ $attributeValue->id }}"
                   {{ in_array($attributeValue->id, $productVariant->attributeValues->pluck('id')->toArray()) ? 'selected' : '' }}>
                   {{ $attributeValue->getTranslation('value', 'en') }}
               </option>
           @endforeach
       </select>
