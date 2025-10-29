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
                  <div class="md:flex-row md:flex-wrap md:gap-8 lg:gap-12 flex flex-col items-start gap-6">
                     <div class="sm:flex-row sm:gap-6 md:gap-8 flex flex-col items-start gap-4">
                        <div>
                           <h2 class="sub_heading mb-1">釣行日</h2>
                           <input class="sm:w-44 md:w-44 w-full rounded" type="date" name="fishing_date"
                              value="{{ old('fishing_date') }}" max="{{ now()->toDateString() }}" />
                           {{-- エラーメッセージ（釣行日） --}}
                           <x-input-error class="mt-2" :messages="$errors->get('fishing_date')" />
                        </div>
                        <div>
                           <h2 class="sub_heading mb-1">釣行時間</h2>
                           <div class="flex items-center w-full">
                              <input class="w-28 rounded text-center" type="time" name="start_time"
                                 value="{{ old('start_time') }}" step="60" />
                              <span class="my-0 mx-1 text-gray-600">〜</span>
                              <input class="w-28 rounded text-center" type="time" name="end_time"
                                 value="{{ old('end_time') }}" step="60" />
                           </div>
                           {{-- エラーメッセージ（釣行時間） --}}
                           <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
                           <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
                        </div>
                     </div>
                     <div class="sm:flex-row sm:gap-6 md:gap-6 flex flex-col items-start gap-4">
                        <div>
                           <h2 class="sub_heading mb-1">スポット</h2>
                           <select name="fishing_spot" class="sm:w-56 md:w-56 w-full rounded">
                              <option value="" @selected(old('fishing_spot', '') === '')>
                                 スポットを選択
                              </option>
                              @foreach ($all_spots as $spot)
                                 <option value="{{ $spot->name }}" @selected(old('fishing_spot') === $spot->name)>
                                    {{ $spot->name }}
                                 </option>
                              @endforeach
                           </select>
                           {{-- エラーメッセージ（スポット） --}}
                           <x-input-error class="mt-2" :messages="$errors->get('fishing_spot')" />
                        </div>
                        {{-- 新規スポット入力 --}}
                        <div>
                           <h2 class="mb-1 mt-2 text-sm text-gray-700">（スポット名を追加）</h2>
                           <input class="sm:w-56 md:w-56 w-full rounded" type="text" name="new_spot"
                              value="{{ old('new_spot') }}" placeholder="相模川上流">
                           {{-- エラーメッセージ（スポット追加） --}}
                           <x-input-error class="mt-2" :messages="$errors->get('new_spot')" />
                        </div>
                     </div>
                  </div>
               </div>

               {{-- 気象状態 --}}
               <div class="mb-8">
                  <h2 class="sub_heading mb-1">気象状態</h2>
                  <div class="sm:flex-row sm:flex-wrap sm:gap-6 md:gap-8 lg:gap-12 flex flex-col items-start gap-6">
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
                     <div>
                        <label class="mb-1 block text-sm text-gray-700">気温</label>
                        <div class="flex items-center gap-2">
                           <input class="w-24 rounded text-right" type="number" name="air_temp"
                              value="{{ old('air_temp') }}" placeholder="10" inputmode="numeric" step="1"
                              min="0" max="60" />
                           <span class="text-gray-600">℃</span>
                        </div>
                        {{-- エラーメッセージ（気温） --}}
                        <x-input-error class="mt-2" :messages="$errors->get('air_temp')" />
                     </div>
                     <div class="">
                        <label class="mb-1 block text-sm text-gray-700">最大風速</label>
                        <div class="flex items-center gap-2">
                           <input class="w-24 rounded text-right" type="number" name="max_wind"
                              value="{{ old('max_wind') }}" placeholder="1" inputmode="decimal" step="0.1"
                              min="0" max="99.9" />
                           <span class="text-gray-600">m/s</span>
                        </div>
                        {{-- エラーメッセージ（風速） --}}
                        <x-input-error class="mt-2" :messages="$errors->get('max_wind')" />
                     </div>
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

               {{-- 川の状態 --}}
               <div class="mb-8">
                  <h2 class="sub_heading mb-1">川の状態</h2>
                  <div class="sm:flex-row sm:flex-wrap sm:gap-6 md:gap-8 lg:gap-12 flex flex-col items-start gap-6">
                     <div class="">
                        <label class="mb-1 block text-sm text-gray-700">川の流れ</label>
                        <select name="river_flow" class="w-44 rounded">
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
                     <div class="">
                        <label class="mb-1 block text-sm text-gray-700">濁り</label>
                        <select name="turbidity" class="w-44 rounded">
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
                     <div class="">
                        <label class="mb-1 block text-sm text-gray-700">水中のゴミ</label>
                        <select name="debris" class="w-44 rounded">
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
                     <div class="">
                        <label class="mb-1 block text-sm text-gray-700">水位</label>
                        <div class="flex items-center gap-2">
                           <input class="w-24 rounded text-right" type="number" name="water_level"
                              value="{{ old('water_level') }}" placeholder="0.0" inputmode="decimal" step="0.1"
                              min="0" max="999.9" />
                           <span class="text-gray-600">m</span>
                        </div>
                        {{-- エラーメッセージ（水位） --}}
                        <x-input-error class="mt-2" :messages="$errors->get('water_level')" />
                     </div>
                     <div class="">
                        <label class="mb-1 block text-sm text-gray-700">水温</label>
                        <div class="flex items-center gap-2">
                           <input class="w-24 rounded text-right" type="number" name="water_temp"
                              value="{{ old('water_temp') }}" placeholder="10" inputmode="numeric" step="1"
                              min="0" max="99" />
                           <span class="text-gray-600">℃</span>
                        </div>
                        {{-- エラーメッセージ（水温） --}}
                        <x-input-error class="mt-2" :messages="$errors->get('water_temp')" />
                     </div>
                  </div>
               </div>

               {{-- エサの入力 --}}
               <div class="mb-8">
                  <div class="md:flex-row md:gap-8 lg:gap-12 flex flex-col items-start gap-6">
                     <div class="">
                        <h2 class="sub_heading mb-1">エサ</h2>
                        @php
                           // 初期表示行数（最低1、最大5）
                           $initialRows = max(1, min(count(old('baits', [])), 5));
                        @endphp
                        <div>
                           <div id="baits-container" class="space-y-2">
                              @for ($i = 0; $i < $initialRows; $i++)
                                 <div class="flex items-center gap-3 bait-row">
                                    <select class="rounded w-60" name="baits[{{ $i }}]">
                                       <option value="">選択してください</option>
                                       @foreach ($all_baits as $bait)
                                          <option value="{{ $bait->name }}" @selected((old('baits', [])[$i] ?? '') === $bait->name)>
                                             {{ $bait->name }}</option>
                                       @endforeach
                                    </select>
                                    <button type="button"
                                       class="text-xs text-red-600 hover:underline remove-bait-row {{ $i === 0 ? 'hidden' : '' }}">
                                       削除
                                    </button>
                                 </div>
                              @endfor
                           </div>
                           <div class="mt-2">
                              <button type="button" id="add-bait-row" class="text-sm text-blue-700 hover:underline">
                                 ＋ エサ入力エリアを追加（最大5件）
                              </button>
                           </div>
                           {{-- エラーメッセージ（エサ配列） --}}
                           <x-input-error class="mt-2" :messages="$errors->get('baits.*')" />
                        </div>
                     </div>
                     {{-- 新規エサ入力 --}}
                     <div class="">
                        <h2 class="mt-2 mb-1 text-sm text-gray-700">（エサ名を追加）</h2>
                        <input class="w-60 rounded" type="text" name="new_bait" value="{{ old('new_bait') }}"
                           placeholder="例: アオイソメ">
                        {{-- エラーメッセージ（エサ追加） --}}
                        <x-input-error class="mt-2" :messages="$errors->get('new_bait')" />
                     </div>
                  </div>
               </div>

               {{-- 釣果の入力 --}}
               <div class="mb-8">
                  <div class="md:gap-8 md:flex-row md:flex-wrap lg:gap-12 flex flex-col items-start gap-6 ">
                     <div class="">
                        <h2 class="sub_heading mb-1">釣果</h2>
                        @php
                           // 初期表示行数（最低1、最大5）
                           $initialRows = max(1, min(count(old('catches', [])), 5));
                        @endphp
                        <div class="flex items-start gap-6">
                           <div id="catches-container" class="space-y-2 flex-1">
                              @for ($i = 0; $i < $initialRows; $i++)
                                 @php
                                    $catch = old('catches', [])[$i] ?? ['name' => '', 'count' => '', 'length_cm' => ''];
                                 @endphp
                                 <div class="flex flex-wrap items-center gap-3 catch-row">
                                    <div class="md:w-auto w-full">
                                       <select class="md:w-60 w-full rounded"
                                          name="catches[{{ $i }}][name]">
                                          <option value="">魚種を選択</option>
                                          @foreach ($all_fish_names as $fish)
                                             <option value="{{ $fish->name }}" @selected(($catch['name'] ?? '') === $fish->name)>
                                                {{ $fish->name }}</option>
                                          @endforeach
                                       </select>
                                    </div>
                                    {{-- 釣果（匹） --}}
                                    <div class="flex items-center gap-2">
                                       <input class="md:w-24 w-20 rounded text-right" type="number"
                                          name="catches[{{ $i }}][count]"
                                          value="{{ $catch['count'] ?? '' }}" placeholder="0" inputmode="numeric"
                                          min="0" step="1" />
                                       <span class="text-gray-600">匹</span>
                                    </div>
                                    {{-- サイズ（cm） --}}
                                    <div class="flex items-center gap-2">
                                       <input class="md:w-24 w-20 rounded text-right" type="number"
                                          name="catches[{{ $i }}][length_cm]"
                                          value="{{ $catch['length_cm'] ?? '' }}" placeholder="0"
                                          inputmode="numeric" min="0" step="1" />
                                       <span class="text-gray-600">cm</span>
                                    </div>
                                    <button type="button"
                                       class="text-xs text-red-600 hover:underline remove-catch-row {{ $i === 0 ? 'hidden' : '' }}">
                                       削除
                                    </button>
                                 </div>
                              @endfor
                           </div>
                        </div>
                        <div class="mt-2">
                           <button type="button" id="add-catch-row" class="text-sm text-blue-700 hover:underline">
                              ＋釣果入力エリア追加（最大5件）
                           </button>
                        </div>
                     </div>
                     <div class="lg:flex gap-6">
                        <div class="">
                           <h2 class="mt-2 mb-1 text-sm text-gray-700">（魚名を追加）</h2>
                           <input class="rounded w-60" type="text" name="new_fish" value="{{ old('new_fish') }}"
                              placeholder="例: ヤマメ">
                           {{-- エラーメッセージ（魚名の追加） --}}
                           <x-input-error class="mt-2" :messages="$errors->get('new_fish')" />
                        </div>
                        {{-- 釣果合計 --}}
                        <div id="catch-total" class="mt-3 p-1 lg:mt-0 w-32 border rounded self-center">
                           <div class="flex items-baseline justify-center gap-3">
                              <div class="text-sm text-gray-600">合計</div>
                              <div id="catch-total-number" class="text-2xl font-semibold">0</div>
                              <div class="text-sm text-gray-600">匹</div>
                           </div>
                        </div>
                     </div>
                  </div>
                  {{-- エラーメッセージ（釣果の内訳） --}}
                  <x-input-error class="mt-2" :messages="$errors->get('catches.*.name')" />
                  <x-input-error class="mt-2" :messages="$errors->get('catches.*.count')" />
                  <x-input-error class="mt-2" :messages="$errors->get('catches.*.length_cm')" />
               </div>

               {{-- メモの備考入力 --}}
               <div class="mb-5">
                  <h2 class="sub_heading mb-1">備考</h2>
                  <textarea class="w-full rounded" name="content" rows="7" placeholder="ここに入力">{{ old('content') }}</textarea>
                  {{-- エラーメッセージ（メモの備考） --}}
                  <x-input-error class="mt-2" :messages="$errors->get('content')" />
               </div>
               {{-- 既存タグの選択 --}}
               <div class="mb-10">
                  <h2 class="sub_heading mb-1">既存タグの選択</h2>
                  <div class="flex flex-wrap gap-3">
                     @foreach ($all_tags as $tag)
                        <div class="flex items-center gap-1 hover:font-semibold">
                           <input class="mb-1 rounded" type="checkbox" name="tags[]" id="{{ $tag->id }}"
                              value="{{ $tag->id }}" @checked(in_array($tag->id, old('tags', []))) />
                           <label for="{{ $tag->id }}">{{ $tag->name }}</label>
                        </div>
                     @endforeach
                  </div>
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
            // name 属性を持つ要素すべてのインデックスを書き換え（select や input に対応）
            row.querySelectorAll('[name^="catches["]').forEach(el => {
               el.name = el.name.replace(/catches\[\d+\]/, `catches[${idx}]`);
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
                     computeTotalCatches();
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
         // 値をクリア（input と select に対応）
         clone.querySelectorAll('input, select').forEach(el => {
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
            else el.value = '';
         });
         // 削除ボタンを表示（1行目以外）
         const delBtn = clone.querySelector('.remove-catch-row');
         if (delBtn) delBtn.classList.remove('hidden');
         catchesContainer.appendChild(clone);
         attachDeleteHandlers(clone);
         attachCountListeners(clone);
         reindexCatchRows();
         computeTotalCatches();
      }

      if (addCatchRowBtn && catchesContainer) {
         addCatchRowBtn.addEventListener('click', addCatchRow);
         attachDeleteHandlers(catchesContainer);
         attachCountListeners(catchesContainer);
         reindexCatchRows();
         computeTotalCatches();
      }

      // 合計を計算して表示
      function computeTotalCatches() {
         const rows = getCatchRows();
         let total = 0;
         rows.forEach(row => {
            const input = row.querySelector('input[name$="[count]"]');
            if (!input) return;
            const v = parseInt(input.value, 10);
            if (!Number.isNaN(v)) total += v;
         });
         const totalNumberEl = document.getElementById('catch-total-number');
         if (totalNumberEl) totalNumberEl.textContent = String(total);
      }

      // カウント入力のイベントを行単位に追加（委譲ではなく個別バインド）
      function attachCountListeners(scope) {
         (scope || document).querySelectorAll('input[name$="[count]"]').forEach(input => {
            if (!input._countBound) {
               input.addEventListener('input', () => {
                  computeTotalCatches();
               });
               input._countBound = true;
            }
         });
      }

      // --- エサの行 追加/削除（最大5件） ---
      const baitsContainer = document.getElementById('baits-container');
      const addBaitRowBtn = document.getElementById('add-bait-row');
      const MAX_BAIT_ROWS = 5;

      const getBaitRows = () => Array.from(baitsContainer?.querySelectorAll('.bait-row') || []);

      // 再インデックス＆UI更新
      function updateBaitControls() {
         const rows = getBaitRows();
         rows.forEach((row, idx) => {
            row.querySelectorAll('select[name^="baits["], input[name^="baits["]').forEach(el => {
               el.name = el.name.replace(/baits\[\d+\]/, `baits[${idx}]`);
            });
            const del = row.querySelector('.remove-bait-row');
            if (del) del.classList.toggle('hidden', idx === 0);
         });
         const disabled = rows.length >= MAX_BAIT_ROWS;
         if (addBaitRowBtn) {
            addBaitRowBtn.disabled = disabled;
            addBaitRowBtn.classList.toggle('opacity-50', disabled);
            addBaitRowBtn.classList.toggle('cursor-not-allowed', disabled);
         }
      }

      // イベント委譲で削除を処理（後から追加された要素も自動で扱える）
      baitsContainer?.addEventListener('click', (e) => {
         const btn = e.target.closest?.('.remove-bait-row');
         if (!btn) return;
         const row = btn.closest('.bait-row');
         if (!row) return;
         if (getBaitRows().length <= 1) return; // 最低1行を維持
         row.remove();
         updateBaitControls();
      });

      // 行の追加（最初の行をクローンして値をクリア）
      function addBaitRow() {
         const rows = getBaitRows();
         if (rows.length >= MAX_BAIT_ROWS) return;
         const base = rows[0];
         if (!base) return;
         const clone = base.cloneNode(true);
         clone.querySelectorAll('select, input').forEach(el => {
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
            else el.value = '';
         });
         baitsContainer.appendChild(clone);
         updateBaitControls();
      }

      addBaitRowBtn?.addEventListener('click', addBaitRow);
      // 初期化
      updateBaitControls();
   </script>
</x-app-layout>
