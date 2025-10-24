<x-app-layout>
   <div class="px-2 py-2 bg-slate-200">
      <section class="min-h-[45vh] text-gray-600 border border-gray-400 rounded-lg bg-white overflow-hidden">
         {{-- メモの新規作成ページのタイトル --}}
         <h1 class="heading heading_bg">新規メモ作成</h1>
         {{-- メモを新規作成するエリア --}}
         <div class="p-3">
            <form action="{{ route('user.store') }}" method="post">
               @csrf
               {{-- 釣行日・釣行時間・釣り場所 --}}
               <div class="mb-8">
                  <div class="flex flex-wrap items-start gap-20">
                     {{-- 釣行日 --}}
                     <div class="flex flex-col">
                        <h2 class="sub_heading mb-1">釣行日</h2>
                        <input class="rounded" type="date" name="fishing_date" value="{{ old('fishing_date') }}"
                           max="{{ now()->toDateString() }}" />
                        {{-- エラーメッセージ（釣行日） --}}
                        <x-input-error class="mt-2" :messages="$errors->get('fishing_date')" />
                     </div>
                     {{-- 釣行時間 --}}
                     <div class="flex flex-col">
                        <h2 class="sub_heading mb-1">釣行時間</h2>
                        <div class="flex items-center gap-2">
                           <input class="rounded text-center" type="time" name="fishing_time_start"
                              value="{{ old('fishing_time_start') }}" step="60" />
                           <span class="text-gray-600">〜</span>
                           <input class="rounded text-center" type="time" name="fishing_time_end"
                              value="{{ old('fishing_time_end') }}" step="60" />
                        </div>
                        {{-- エラーメッセージ（釣行時間） --}}
                        <x-input-error class="mt-2" :messages="$errors->get('fishing_time_start')" />
                        <x-input-error class="mt-2" :messages="$errors->get('fishing_time_end')" />
                     </div>
                     {{-- 釣り場所 --}}
                     <div class="flex flex-col">
                        <h2 class="sub_heading mb-1">釣り場所</h2>
                        <input class="w-60 rounded" type="text" name="fishing_spot" value="{{ old('fishing_spot') }}"
                           placeholder="例: ○○港 防波堤" />
                        {{-- エラーメッセージ（釣り場所） --}}
                        <x-input-error class="mt-2" :messages="$errors->get('fishing_spot')" />
                     </div>
                  </div>
               </div>

               <div class="flex flex-col md:flex-row md:items-start md:gap-20">
                  {{-- 天気の入力 --}}
                  <div class="mb-8">
                     <h2 class="sub_heading mb-1">天気</h2>
                     <div>
                        <select id="weather" name="weather" class="rounded w-56">
                           <option value="" @selected(old('weather', '') === '')>未選択</option>
                           <option value="sunny" @selected(old('weather') === 'sunny')>晴れ</option>
                           <option value="cloudy" @selected(old('weather') === 'cloudy')>曇り</option>
                           <option value="rain" @selected(old('weather') === 'rain')>雨</option>
                           <option value="sunny_cloudy" @selected(old('weather') === 'sunny_cloudy')>晴れ → 曇り</option>
                           <option value="sunny_rain" @selected(old('weather') === 'sunny_rain')>晴れ → 雨</option>
                           <option value="cloudy_sunny" @selected(old('weather') === 'cloudy_sunny')>曇り → 晴れ</option>
                           <option value="cloudy_rain" @selected(old('weather') === 'cloudy_rain')>曇り → 雨</option>
                           <option value="rain_sunny" @selected(old('weather') === 'rain_sunny')>雨 → 晴れ</option>
                           <option value="rain_cloudy" @selected(old('weather') === 'rain_cloudy')>雨 → 曇り</option>
                           <option value="other" @selected(old('weather') === 'other')>その他</option>
                        </select>
                     </div>
                     {{-- エラーメッセージ（天気） --}}
                     <x-input-error class="mt-2" :messages="$errors->get('weather')" />
                  </div>
                  {{-- 風（風速・風向） --}}
                  <div class="mb-8">
                     <div class="flex flex-wrap items-start gap-6">
                        {{-- 風速 --}}
                        <div class="flex flex-col">
                           <h2 class="sub_heading mb-1">風速</h2>
                           <div class="flex items-center gap-2">
                              <input class="rounded text-center" type="number" name="wind_speed_min"
                                 value="{{ old('wind_speed_min') }}" placeholder="1" inputmode="numeric" step="1"
                                 min="1" max="9" />
                              <span class="text-gray-600">〜</span>
                              <input class="rounded text-center" type="number" name="wind_speed_max"
                                 value="{{ old('wind_speed_max') }}" placeholder="2" inputmode="numeric" step="1"
                                 min="1" max="9" />
                              <span class="text-gray-600">m/s</span>
                           </div>
                           {{-- エラーメッセージ（風速） --}}
                           <x-input-error class="mt-2" :messages="$errors->get('wind_speed_min')" />
                           <x-input-error class="mt-2" :messages="$errors->get('wind_speed_max')" />
                        </div>
                        {{-- 風向 --}}
                        <div class="flex flex-col">
                           <h2 class="sub_heading mb-1">風向</h2>
                           <select name="wind_direction" class="rounded">
                              <option value="" @selected(old('wind_direction', '') === '')>未選択</option>
                              <option value="N" @selected(old('wind_direction') === 'N')>北</option>
                              <option value="NE" @selected(old('wind_direction') === 'NE')>北東</option>
                              <option value="E" @selected(old('wind_direction') === 'E')>東</option>
                              <option value="SE" @selected(old('wind_direction') === 'SE')>南東</option>
                              <option value="S" @selected(old('wind_direction') === 'S')>南</option>
                              <option value="SW" @selected(old('wind_direction') === 'SW')>南西</option>
                              <option value="W" @selected(old('wind_direction') === 'W')>西</option>
                              <option value="NW" @selected(old('wind_direction') === 'NW')>北西</option>
                           </select>
                           {{-- エラーメッセージ（風向） --}}
                           <x-input-error class="mt-2" :messages="$errors->get('wind_direction')" />
                        </div>
                     </div>
                  </div>
               </div>

               {{-- 川の状態 --}}
               <div class="mb-8">
                  <h2 class="sub_heading mb-1">川の状態</h2>
                  <div class="flex flex-wrap gap-12">
                     <div>
                        <label class="block text-sm text-gray-700 mb-1">川の流れ</label>
                        <select name="has_flow" class="rounded">
                           <option value="" @selected(old('has_flow', '') === '')>
                              未選択
                           </option>
                           <option value="1" @selected(old('has_flow') === '1')>
                              流れあり
                           </option>
                           <option value="0" @selected(old('has_flow') === '0')>
                              流れなし
                           </option>
                        </select>
                        {{-- エラーメッセージ（川の流れ） --}}
                        <x-input-error class="mt-2" :messages="$errors->get('has_flow')" />
                     </div>
                     <div>
                        <label class="block text-sm text-gray-700 mb-1">濁り</label>
                        <select name="water_clarity" class="rounded">
                           <option value="" @selected(old('water_clarity', '') === '')>
                              未選択
                           </option>
                           <option value="clear" @selected(old('water_clarity') === 'clear')>
                              クリア
                           </option>
                           <option value="slightly" @selected(old('water_clarity') === 'slightly')>
                              やや濁り
                           </option>
                           <option value="turbid" @selected(old('water_clarity') === 'turbid')>
                              濁り
                           </option>
                           <option value="very_turbid" @selected(old('water_clarity') === 'very_turbid')>
                              強い濁り
                           </option>
                        </select>
                        {{-- エラーメッセージ（濁り） --}}
                        <x-input-error class="mt-2" :messages="$errors->get('water_clarity')" />
                     </div>
                     <div>
                        <label class="block text-sm text-gray-700 mb-1">水中のゴミ</label>
                        <select name="underwater_debris" class="rounded">
                           <option value="" @selected(old('underwater_debris', '') === '')>
                              未選択
                           </option>
                           <option value="none" @selected(old('underwater_debris') === 'none')>
                              なし
                           </option>
                           <option value="slightly" @selected(old('underwater_debris') === 'slightly')>
                              ややあり
                           </option>
                           <option value="present" @selected(old('underwater_debris') === 'present')>
                              あり
                           </option>
                        </select>
                        {{-- エラーメッセージ（水中のゴミ） --}}
                        <x-input-error class="mt-2" :messages="$errors->get('underwater_debris')" />
                     </div>
                     <div>
                        <label class="block text-sm text-gray-700 mb-1">水位</label>
                        <div class="flex items-center gap-2">
                           <input class="w-24 rounded text-right" type="text" name="water_level"
                              value="{{ old('water_level') }}" placeholder="-0.5" inputmode="decimal"
                              pattern="^-?\d*(\.\d+)?$" />
                           <span class="text-gray-600">cm</span>
                        </div>
                        {{-- エラーメッセージ（水位） --}}
                        <x-input-error class="mt-2" :messages="$errors->get('water_level')" />
                     </div>
                     <div>
                        <label class="block text-sm text-gray-700 mb-1">水温</label>
                        <div class="flex items-center gap-2">
                           <input class="w-24 rounded text-right" type="number" name="water_temp"
                              value="{{ old('water_temp') }}" placeholder="10" inputmode="numeric" step="1"
                              min="0" max="35" />
                           <span class="text-gray-600">℃</span>
                        </div>
                        {{-- エラーメッセージ（水温） --}}
                        <x-input-error class="mt-2" :messages="$errors->get('water_temp')" />
                     </div>
                  </div>
               </div>

               <div class="flex flex-col md:flex-row md:items-start md:gap-24">
                  {{-- エサの入力 --}}
                  <div class="mb-8">
                     <h2 class="sub_heading mb-1">エサ</h2>
                     @php
                        $oldBaits = old('baits', []);
                        $legacyBait = old('bait'); // 互換: 以前の単一セレクト値がある場合に復元
                        if (empty($oldBaits) && !empty($legacyBait)) {
                            $oldBaits = [$legacyBait];
                        }
                        $initialBaitCount = max(1, min(count($oldBaits), 5));
                     @endphp
                     <div id="baits-container" class="space-y-2">
                        @for ($i = 0; $i < $initialBaitCount; $i++)
                           <div class="flex items-center gap-2 bait-row">
                              <input class="w-60 rounded" type="text" name="baits[{{ $i }}]"
                                 value="{{ $oldBaits[$i] ?? '' }}" placeholder="例: ミミズ " />
                              <button type="button"
                                 class="text-xs text-red-600 hover:underline remove-bait-row {{ $i === 0 ? 'hidden' : '' }}">
                                 削除
                              </button>
                           </div>
                        @endfor
                     </div>
                     <div class="mt-2">
                        <button type="button" id="add-bait-row" class="text-sm text-blue-700 hover:underline">
                           ＋ エサを追加（最大5件）
                        </button>
                     </div>
                     {{-- エラーメッセージ（エサ配列） --}}
                     <x-input-error class="mt-2" :messages="$errors->get('baits.*')" />
                  </div>
                  {{-- 釣果の入力 --}}
                  <div class="mb-8">
                     <h2 class="sub_heading mb-1">釣果</h2>
                     @php
                        $oldCatches = old('catches', []);
                        $initialCount = max(1, min(count($oldCatches), 5));
                     @endphp
                     <div id="catches-container" class="space-y-2">
                        @for ($i = 0; $i < $initialCount; $i++)
                           @php
                              $catch = $oldCatches[$i] ?? ['name' => '', 'count' => '', 'length_cm' => ''];
                           @endphp
                           <div class="flex flex-wrap items-center gap-3 catch-row">
                              <div class="w-full sm:w-auto">
                                 <input class="w-full sm:w-48 rounded" type="text"
                                    name="catches[{{ $i }}][name]" value="{{ $catch['name'] ?? '' }}"
                                    placeholder="魚種名（例: ヤマメ）" />
                              </div>
                              <div class="flex items-center gap-2">
                                 <input class="w-20 sm:w-24 rounded text-right" type="number"
                                    name="catches[{{ $i }}][count]" value="{{ $catch['count'] ?? '' }}"
                                    placeholder="0" inputmode="numeric" min="0" step="1" />
                                 <span class="text-gray-600 hidden sm:inline">匹</span>
                              </div>
                              <div class="flex items-center gap-2">
                                 <input class="w-20 sm:w-24 rounded text-right" type="number"
                                    name="catches[{{ $i }}][length_cm]"
                                    value="{{ $catch['length_cm'] ?? '' }}" placeholder="0" inputmode="numeric"
                                    min="0" step="1" />
                                 <span class="text-gray-600 hidden sm:inline">cm</span>
                              </div>
                              <button type="button"
                                 class="text-xs text-red-600 hover:underline remove-catch-row {{ $i === 0 ? 'hidden' : '' }}">
                                 削除
                              </button>
                           </div>
                        @endfor
                     </div>
                     <div class="mt-2">
                        <button type="button" id="add-catch-row" class="text-sm text-blue-700 hover:underline">
                           ＋釣果を追加（最大5件）
                        </button>
                     </div>
                     {{-- エラーメッセージ（釣果の内訳） --}}
                     <x-input-error class="mt-2" :messages="$errors->get('catches.*.name')" />
                     <x-input-error class="mt-2" :messages="$errors->get('catches.*.count')" />
                     <x-input-error class="mt-2" :messages="$errors->get('catches.*.length_cm')" />
                  </div>
               </div>

               {{-- メモの内容入力 --}}
               <div class="mb-5">
                  <h2 class="sub_heading mb-1">内容</h2>
                  <textarea class="w-full rounded" name="content" rows="7" placeholder="ここにメモを入力">{{ old('content') }}</textarea>
                  {{-- エラーメッセージ（メモの内容） --}}
                  <x-input-error class="mt-2" :messages="$errors->get('content')" />
               </div>
               {{-- 既存タグの選択 --}}
               <div class="mb-10">
                  <h2 class="sub_heading mb-1">既存タグの選択</h2>
                  @foreach ($all_tags as $tag)
                     <div class="inline mr-3 hover:font-semibold">
                        <input class="mb-1 rounded" type="checkbox" name="tags[]" id="{{ $tag->id }}"
                           value="{{ $tag->id }}" @checked(in_array($tag->id, old('tags', []))) />
                        <label for="{{ $tag->id }}">{{ $tag->name }}</label>
                     </div>
                  @endforeach
               </div>
               {{-- 新規タグ入力 --}}
               <div class="mb-10">
                  <h2 class="sub_heading mb-1">新規タグの追加</h2>
                  <input class="w-60 rounded" type="text" name="new_tag" value="{{ old('new_tag') }}"
                     placeholder="ここに新規タグを入力" />
                  {{-- エラーメッセージ（新規タグ） --}}
                  <x-input-error class="mt-2" :messages="$errors->get('new_tag')" />
               </div>
               {{-- 画像の選択 --}}
               <div class="mb-10">
                  <h2 class="sub_heading">画像の選択</h2>
                  {{-- モーダルウィンドウ --}}
                  <x-common.list-select-image :allImages='$all_images' />
               </div>
               {{-- メモの保存ボタン --}}
               <div class="mb-5">
                  <button class="btn bg-blue-800 hover:bg-blue-700" type="submit">保存する</button>
               </div>
            </form>
            {{-- 戻るボタン --}}
            <div class="flex justify-end">
               <button class="btn bg-gray-800 hover:bg-gray-700" onclick="location.href='{{ route('user.index') }}'">
                  戻る
               </button>
            </div>
         </div>
      </section>
   </div>
   <script>
      'use strict'
      // 画像の上限数の設定
      const maxCount = 4;
      // チェックボックスをクラス名で取得
      const checkBoxClassName = "imageCheckbox";

      // チェックボックスが変更されたときに呼び出されるメソッド
      function handleCheckboxChange() {
         const checkBoxes = document.getElementsByClassName(checkBoxClassName);
         let checked_count = 0;

         // 画像の枚数を、チェックする
         for (let i = 0; i < checkBoxes.length; i++) {
            if (checkBoxes[i].checked) {
               checked_count++;
            }
         }
         // 画像の上限枚数に、達したら選択をキャンセル
         if (checked_count > maxCount) {
            alert("画像は " + maxCount + " 枚までにしてください。");
            this.checked = false;
         }
      }

      // チェックボックスの変更イベントにメソッドを紐付ける
      const checkBoxes = document.getElementsByClassName(checkBoxClassName);
      for (let i = 0; i < checkBoxes.length; i++) {
         checkBoxes[i].addEventListener('change', handleCheckboxChange);
      }

      // 画像要素を取得
      const images = document.querySelectorAll('.image');
      // サムネイルコンテナ要素を取得
      const thumbnailArea = document.getElementById('thumbnail-area');
      // ユーザーが選択した画像を保持する配列
      const selectedImages = [];

      //各画像にクリックイベントリスナーを追加
      images.forEach(image => {
         image.addEventListener('click', function(e) {
            // クリックされた画像のデータ属性を取得
            const imageId = Number(e.target.dataset.id);
            const imageFile = e.target.dataset.file;
            const imagePath = e.target.dataset.path;

            // 画像がすでに選択されているか確認
            const isSelecte = selectedImages.some(img => img.id === imageId);
            if (!isSelecte && selectedImages.length < maxCount) {
               // 上限数に達していない場合、ユーザーが選択した画像を配列に追加
               selectedImages.push({
                  id: imageId,
                  file: imageFile,
                  path: imagePath
               });
               // サムネイルエリアに選択した画像を表示
               const thumbnailImage = document.createElement('img');
               thumbnailImage.src = imagePath + '/' + imageFile;
               thumbnailImage.classList.add('mr-2', 'mb-2', 'border', 'rounded-md', 'p-1', 'w-[22.7%]',
                  'sm:w-[23%]');
               thumbnailArea.appendChild(thumbnailImage);
            } else if (isSelecte) {
               // すでに選択されている場合、配列から削除
               const imageArrayExistsDelete = selectedImages.findIndex(img => img.id === imageId);
               selectedImages.splice(imageArrayExistsDelete, 1);
               // 続けて、サムネイルエリアに選択されている画像を削除
               const thumbnailDelete = thumbnailArea.querySelector(
                  `[src="${imagePath}/${imageFile}"]`);
               if (thumbnailDelete) {
                  thumbnailArea.removeChild(thumbnailDelete);
               }
            }
         });
      });

      // --- 釣果の行 追加/削除（最大5件） ---
      const catchesContainer = document.getElementById('catches-container');
      const addCatchRowBtn = document.getElementById('add-catch-row');
      const MAX_CATCH_ROWS = 5;

      function getCatchRows() {
         return Array.from(catchesContainer?.querySelectorAll('.catch-row') || []);
      }

      function reindexCatchRows() {
         const rows = getCatchRows();
         rows.forEach((row, idx) => {
            // 入力name内のインデックスを書き換え
            row.querySelectorAll('input[name^="catches["]').forEach(input => {
               input.name = input.name.replace(/catches\[\d+\]/, `catches[${idx}]`);
            });
            // 1行目は削除ボタンを非表示、それ以外は表示
            const delBtn = row.querySelector('.remove-catch-row');
            if (delBtn) delBtn.classList.toggle('hidden', idx === 0);
         });
         // 追加ボタンの有効/無効
         const disabled = rows.length >= MAX_CATCH_ROWS;
         if (addCatchRowBtn) {
            addCatchRowBtn.disabled = disabled;
            addCatchRowBtn.classList.toggle('opacity-50', disabled);
            addCatchRowBtn.classList.toggle('cursor-not-allowed', disabled);
         }
      }

      function attachDeleteHandlers(scope) {
         (scope || document).querySelectorAll('.remove-catch-row').forEach(btn => {
            if (!btn._bound) {
               btn.addEventListener('click', () => {
                  const row = btn.closest('.catch-row');
                  if (row && getCatchRows().length > 1) {
                     row.remove();
                     reindexCatchRows();
                  }
               });
               btn._bound = true;
            }
         });
      }

      function addCatchRow() {
         const rows = getCatchRows();
         if (rows.length >= MAX_CATCH_ROWS) return;
         const base = rows[0];
         if (!base) return;
         const clone = base.cloneNode(true);
         // 値をクリア
         clone.querySelectorAll('input').forEach(input => {
            input.value = '';
         });
         // 削除ボタンを表示（1行目以外）
         const delBtn = clone.querySelector('.remove-catch-row');
         if (delBtn) delBtn.classList.remove('hidden');
         catchesContainer.appendChild(clone);
         attachDeleteHandlers(clone);
         reindexCatchRows();
      }

      if (addCatchRowBtn && catchesContainer) {
         addCatchRowBtn.addEventListener('click', addCatchRow);
         attachDeleteHandlers(catchesContainer);
         reindexCatchRows();
      }

      // --- エサの行 追加/削除（最大5件） ---
      const baitsContainer = document.getElementById('baits-container');
      const addBaitRowBtn = document.getElementById('add-bait-row');
      const MAX_BAIT_ROWS = 5;

      function getBaitRows() {
         return Array.from(baitsContainer?.querySelectorAll('.bait-row') || []);
      }

      function reindexBaitRows() {
         const rows = getBaitRows();
         rows.forEach((row, idx) => {
            row.querySelectorAll('input[name^="baits["]').forEach(input => {
               input.name = input.name.replace(/baits\[\d+\]/, `baits[${idx}]`);
            });
            const delBtn = row.querySelector('.remove-bait-row');
            if (delBtn) delBtn.classList.toggle('hidden', idx === 0);
         });
         const disabled = rows.length >= MAX_BAIT_ROWS;
         if (addBaitRowBtn) {
            addBaitRowBtn.disabled = disabled;
            addBaitRowBtn.classList.toggle('opacity-50', disabled);
            addBaitRowBtn.classList.toggle('cursor-not-allowed', disabled);
         }
      }

      function attachBaitDeleteHandlers(scope) {
         (scope || document).querySelectorAll('.remove-bait-row').forEach(btn => {
            if (!btn._bound) {
               btn.addEventListener('click', () => {
                  const row = btn.closest('.bait-row');
                  if (row && getBaitRows().length > 1) {
                     row.remove();
                     reindexBaitRows();
                  }
               });
               btn._bound = true;
            }
         });
      }

      function addBaitRow() {
         const rows = getBaitRows();
         if (rows.length >= MAX_BAIT_ROWS) return;
         const base = rows[0];
         if (!base) return;
         const clone = base.cloneNode(true);
         const input = clone.querySelector('input');
         if (input) input.value = '';
         const delBtn = clone.querySelector('.remove-bait-row');
         if (delBtn) delBtn.classList.remove('hidden');
         baitsContainer.appendChild(clone);
         attachBaitDeleteHandlers(clone);
         reindexBaitRows();
      }

      if (addBaitRowBtn && baitsContainer) {
         addBaitRowBtn.addEventListener('click', addBaitRow);
         attachBaitDeleteHandlers(baitsContainer);
         reindexBaitRows();
      }
   </script>
</x-app-layout>
