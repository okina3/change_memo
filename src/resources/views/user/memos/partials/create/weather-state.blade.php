<div class="mb-8">
   {{-- 気象状態 --}}
   <h2 class="sub_heading mb-1">気象状態</h2>
   <div class="sm:flex-row sm:flex-wrap sm:gap-6 md:gap-8 lg:gap-12 flex flex-col items-start gap-6">
      {{-- 天気 --}}
      <div class="">
         <label class="mb-1 block text-sm text-gray-700">天気</label>
         <select name="weather" class="lg:w-60 w-56 rounded">
            <option value="" @selected(old('weather', '') === '')>未選択</option>
            <option value="sunny" @selected(old('weather') === 'sunny')>晴れ</option>
            <option value="cloudy" @selected(old('weather') === 'cloudy')>曇り</option>
            <option value="rain" @selected(old('weather') === 'rain')>雨</option>
            <option value="other" @selected(old('weather') === 'other')>その他</option>
         </select>
         {{-- エラーメッセージ（天気） --}}
         <x-input-error class="mt-2" :messages="$errors->get('weather')" />
      </div>
      {{-- 気温 --}}
      <div>
         <label class="mb-1 block text-sm text-gray-700">気温</label>
         <div class="flex items-center gap-2">
            <input class="w-24 rounded text-right" type="number" name="air_temp" value="{{ old('air_temp') }}"
               placeholder="10" inputmode="numeric" step="1" min="0" max="60" />
            <span class="text-gray-600">℃</span>
         </div>
         {{-- エラーメッセージ（気温） --}}
         <x-input-error class="mt-2" :messages="$errors->get('air_temp')" />
      </div>
      {{-- 最大風速 --}}
      <div class="">
         <label class="mb-1 block text-sm text-gray-700">最大風速</label>
         <div class="flex items-center gap-2">
            <input class="w-24 rounded text-right" type="number" name="max_wind" value="{{ old('max_wind') }}"
               placeholder="1" inputmode="numeric" step="1" min="0" max="99" />
            <span class="text-gray-600">m/s</span>
         </div>
         {{-- エラーメッセージ（風速） --}}
         <x-input-error class="mt-2" :messages="$errors->get('max_wind')" />
      </div>
      {{-- 風向 --}}
      <div class="">
         <label class="mb-1 block text-sm text-gray-700">風向</label>
         <select name="wind_dir" class="lg:w-40 w-36 rounded">
            <option value="" @selected(old('wind_dir', '') === '')>未選択</option>
            <option value="N" @selected(old('wind_dir') === 'N')>北</option>
            <option value="NE" @selected(old('wind_dir') === 'NE')>北東</option>
            <option value="E" @selected(old('wind_dir') === 'E')>東</option>
            <option value="SE" @selected(old('wind_dir') === 'SE')>南東</option>
            <option value="S" @selected(old('wind_dir') === 'S')>南</option>
            <option value="SW" @selected(old('wind_dir') === 'SW')>南西</option>
            <option value="W" @selected(old('wind_dir') === 'W')>西</option>
            <option value="NW" @selected(old('wind_dir') === 'NW')>北西</option>
         </select>
         {{-- エラーメッセージ（風向） --}}
         <x-input-error class="mt-2" :messages="$errors->get('wind_dir')" />
      </div>
   </div>
</div>
