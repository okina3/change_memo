<div class="mb-8">
   <div class="md:flex-row md:flex-wrap md:gap-8 lg:gap-12 flex flex-col items-start gap-6">
      {{-- 釣行日・釣行時間 --}}
      <div class="sm:flex-row sm:gap-6 md:gap-8 flex flex-col items-start gap-4">
         {{-- 釣行日 --}}
         <div>
            <h2 class="sub_heading mb-1">釣行日</h2>
            <input class="sm:w-44 md:w-44 w-full rounded" type="date" name="fishing_date"
               value="{{ old('fishing_date') }}" max="{{ now()->toDateString() }}" />
            {{-- エラーメッセージ（釣行日） --}}
            <x-input-error class="mt-2" :messages="$errors->get('fishing_date')" />
         </div>
         {{-- 釣行時間 --}}
         <div>
            <h2 class="sub_heading mb-1">釣行時間</h2>
            <div class="flex items-center w-full">
               <input class="w-28 rounded text-center" type="time" name="start_time" value="{{ old('start_time') }}"
                  step="60" />
               <span class="my-0 mx-1 text-gray-600">〜</span>
               <input class="w-28 rounded text-center" type="time" name="end_time" value="{{ old('end_time') }}"
                  step="60" />
            </div>
            {{-- エラーメッセージ（釣行時間） --}}
            <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
            <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
         </div>
      </div>
      {{-- 釣り場所 --}}
      <div class="sm:flex-row sm:gap-6 md:gap-6 flex flex-col items-start gap-4">
         {{-- スポット --}}
         <div>
            <h2 class="sub_heading mb-1">スポット</h2>
            <select name="fishing_spot" class="sm:w-56 md:w-56 w-full rounded">
               <option value="" @selected(old('fishing_spot', '') === '')>
                  スポットを選択
               </option>
               @foreach ($all_spots as $spot)
                  <option value="{{ $spot->id }}" @selected(old('fishing_spot') === $spot->id)>
                     {{ $spot->name }}
                  </option>
               @endforeach
            </select>
            {{-- エラーメッセージ（スポット） --}}
            <x-input-error class="mt-2" :messages="$errors->get('fishing_spot')" />
         </div>
         {{-- 追加スポット --}}
         <div>
            <h2 class="mb-1 mt-2 text-sm text-gray-700">（スポット名を追加）</h2>
            <input class="sm:w-56 md:w-56 w-full rounded" type="text" name="new_spot" value="{{ old('new_spot') }}"
               placeholder="相模川上流">
            {{-- エラーメッセージ（追加スポット） --}}
            <x-input-error class="mt-2" :messages="$errors->get('new_spot')" />
         </div>
      </div>
   </div>
</div>
