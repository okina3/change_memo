<div class="mb-8">
   {{-- 基本情報 --}}
   <h2 class="sub_heading mb-1">基本情報</h2>
   <div class="md:flex-row md:flex-wrap md:gap-8 lg:gap-12 flex flex-col items-start gap-6">
      <div class="sm:flex-row sm:gap-6 md:gap-8 flex flex-col items-start gap-4">
         {{-- 釣行日 --}}
         <div>
            <label class="mb-1 block text-sm text-gray-700">釣行日</label>
            <input class="sm:w-44 md:w-44 w-full rounded" type="date" name="fishing_date"
               value="{{ old('fishing_date') }}" max="{{ now()->toDateString() }}" />
            {{-- エラーメッセージ（釣行日） --}}
            <x-input-error class="mt-2" :messages="$errors->get('fishing_date')" />
         </div>
         {{-- 釣行時間 --}}
         <div>
            <label class="mb-1 block text-sm text-gray-700">釣行時間</label>
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
      <div class="sm:flex-row sm:gap-6 md:gap-6 flex flex-col items-start gap-4">
         {{-- 釣り場所 --}}
         <div>
            <label class="mb-1 block text-sm text-gray-700">場所</label>
            <select name="fishing_spot" class="sm:w-56 md:w-56 w-full rounded">
               <option value="" @selected(old('fishing_spot', '') === '')>
                  場所を選択
               </option>
               @foreach ($all_spots as $spot)
                  <option value="{{ $spot->id }}" @selected(old('fishing_spot') === $spot->id)>
                     {{ $spot->name }}
                  </option>
               @endforeach
            </select>
            {{-- エラーメッセージ（場所） --}}
            <x-input-error class="mt-2" :messages="$errors->get('fishing_spot')" />
         </div>
         {{-- 釣り場所登録作成ボタン --}}
         <button class="btn-2 btn-bk sm:mt-6 bg-yellow-500 hover:bg-yellow-400"
            onclick="location.href='{{ route('user.create') }}'">
            場所の新規登録
         </button>
      </div>
   </div>
</div>
