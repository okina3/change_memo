<div class="mb-8">
   {{-- 川の状態 --}}
   <h2 class="sub_heading mb-1">川の状態</h2>
   <div class="sm:flex-row sm:flex-wrap sm:gap-6 md:gap-8 lg:gap-12 flex flex-col items-start gap-6">
      {{-- 川の流れ --}}
      <div class="">
         <label class="mb-1 block text-sm text-gray-700">川の流れ</label>
         <select name="river_flow" class="w-32 rounded">
            <option value="" @selected(old('river_flow', '') === '')>
               未選択
            </option>
            <option value="flow" @selected(old('river_flow') === 'flow')>
               流れあり
            </option>
            <option value="no_flow" @selected(old('river_flow') === 'no_flow')>
               流れなし
            </option>
         </select>
         {{-- エラーメッセージ（川の流れ） --}}
         <x-input-error class="mt-2" :messages="$errors->get('river_flow')" />
      </div>
      {{-- 濁り --}}
      <div class="">
         <label class="mb-1 block text-sm text-gray-700">濁り</label>
         <select name="turbidity" class="w-32 rounded">
            <option value="" @selected(old('turbidity', '') === '')>
               未選択
            </option>
            <option value="clear" @selected(old('turbidity') === 'clear')>
               クリア
            </option>
            <option value="slightly" @selected(old('turbidity') === 'slightly')>
               やや濁り
            </option>
            <option value="turbid" @selected(old('turbidity') === 'turbid')>
               濁り
            </option>
            <option value="very_turbid" @selected(old('turbidity') === 'very_turbid')>
               強い濁り
            </option>
         </select>
         {{-- エラーメッセージ（濁り） --}}
         <x-input-error class="mt-2" :messages="$errors->get('turbidity')" />
      </div>
      {{-- 水中のゴミ --}}
      <div class="">
         <label class="mb-1 block text-sm text-gray-700">水中のゴミ</label>
         <select name="debris" class="w-32 rounded">
            <option value="" @selected(old('debris', '') === '')>
               未選択
            </option>
            <option value="none" @selected(old('debris') === 'none')>
               なし
            </option>
            <option value="slightly" @selected(old('debris') === 'slightly')>
               ややあり
            </option>
            <option value="present" @selected(old('debris') === 'present')>
               あり
            </option>
         </select>
         {{-- エラーメッセージ（水中のゴミ） --}}
         <x-input-error class="mt-2" :messages="$errors->get('debris')" />
      </div>
      {{-- 水位 --}}
      <div class="">
         <label class="mb-1 block text-sm text-gray-700">水位</label>
         <div class="flex items-center gap-2">
            <input class="w-24 rounded text-right" type="number" name="water_level" value="{{ old('water_level') }}"
               placeholder="0.0" inputmode="decimal" step="0.1" min="0" max="999.9" />
            <span class="text-gray-600">m</span>
         </div>
         {{-- エラーメッセージ（水位） --}}
         <x-input-error class="mt-2" :messages="$errors->get('water_level')" />
      </div>
      {{-- 水温 --}}
      <div class="">
         <label class="mb-1 block text-sm text-gray-700">水温</label>
         <div class="flex items-center gap-2">
            <input class="w-24 rounded text-right" type="number" name="water_temp" value="{{ old('water_temp') }}"
               placeholder="10" inputmode="numeric" step="1" min="0" max="99" />
            <span class="text-gray-600">℃</span>
         </div>
         {{-- エラーメッセージ（水温） --}}
         <x-input-error class="mt-2" :messages="$errors->get('water_temp')" />
      </div>
   </div>
</div>
