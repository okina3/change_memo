<div class="mb-8">
   <div class="md:gap-8 md:flex-row md:flex-wrap lg:gap-12 flex flex-col items-start gap-6">
      {{-- 釣果の入力 --}}
      <div class="">
         <h2 class="sub_heading mb-1">釣果</h2>
         @php
            // 初期表示行数（最低1、最大5）
            // 編集画面では old() 優先、なければ $select_memo に紐づくピボットデータを使う
            $oldResults = old('fishing_results');
            if (is_array($oldResults)) {
                $existingResults = $oldResults;
            } else {
                $existingResults = [];
                if (isset($select_memo) && $select_memo->fish_names->isNotEmpty()) {
                    foreach ($select_memo->fish_names as $fn) {
                        $existingResults[] = [
                            'fish_name' => $fn->id,
                            'count' => $fn->pivot->count ?? '',
                            'length' => $fn->pivot->length ?? '',
                        ];
                    }
                }
            }
            $initialRows = max(1, min(count($existingResults), 5));
         @endphp
         <div class="flex items-start">
            <div id="catches-container" class="space-y-2 flex-1">
               @for ($i = 0; $i < $initialRows; $i++)
                  @php
                     $entry = $existingResults[$i] ?? ['fish_name' => '', 'count' => '', 'length' => ''];
                  @endphp
                  <div class="lg:gap-6 flex flex-wrap items-center gap-3 catch-row">
                     {{-- 魚名の選択 --}}
                     <div class="md:w-auto w-full">
                        <select class="w-60 rounded" name="fishing_results[{{ $i }}][fish_name]">
                           <option value="">魚名を選択してください</option>
                           @foreach ($all_fish_names as $fish)
                              <option value="{{ $fish->id }}" @selected(($entry['fish_name'] ?? '') == $fish->id)>{{ $fish->name }}
                              </option>
                           @endforeach
                        </select>
                     </div>
                     {{-- 釣果（匹） --}}
                     <div class="flex items-center gap-2">
                        <input class="md:w-24 w-20 rounded text-right" type="number"
                           name="fishing_results[{{ $i }}][count]" value="{{ $entry['count'] ?? '' }}"
                           placeholder="0" inputmode="numeric" min="0" step="1" />
                        <span class="text-gray-600">匹</span>
                     </div>
                     {{-- サイズ（cm） --}}
                     <div class="flex items-center gap-2">
                        <input class="md:w-24 w-20 rounded text-right" type="number"
                           name="fishing_results[{{ $i }}][length]" value="{{ $entry['length'] ?? '' }}"
                           placeholder="0" inputmode="numeric" min="0" step="1" />
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
         {{-- エラーメッセージ（釣果の内訳） --}}
         <x-input-error class="mt-2" :messages="$errors->get('fishing_results.*.fish_name')" />
         <x-input-error class="mt-2" :messages="$errors->get('fishing_results.*.count')" />
         <x-input-error class="mt-2" :messages="$errors->get('fishing_results.*.length')" />
         <div class="mt-2">
            <button type="button" id="add-catch-row" class="text-sm text-blue-700 hover:underline">
               ＋釣果入力エリア追加（最大5件）
            </button>
         </div>
      </div>
      {{-- 魚名の追加 --}}
      <div>
         <h2 class="mt-2 mb-1 block text-sm text-gray-700">（魚名を選択肢に追加）</h2>
         <div class="flex gap-2 items-center">
            <input id="new_fish_input" class="w-60 rounded" type="text" name="new_fish_name"
               value="{{ old('new_fish_name') }}" placeholder="例: ヤマメ">
            <button type="button" id="add_fish_btn" data-url="{{ route('user.fish-name.store') }}"
               class="btn-2 btn-bk bg-yellow-500 hover:bg-yellow-400">
               追加
            </button>
         </div>
         {{-- エラーメッセージ（魚名の追加） --}}
         <x-input-error class="mt-2" :messages="$errors->get('new_fish_name')" />
         {{-- AJAX 用メッセージ表示領域 --}}
         <div id="fish_message" class="mt-2 text-sm" aria-live="polite"></div>
      </div>
   </div>
</div>
{{-- 固有の JavaScript の読み込み（魚名を追加ボタンの AJAX 処理と UI 表示を実装） --}}
@vite(['resources/js/user/memos/new-fish-name-add.js'])
<script>
   'use strict'
   // === 釣果入力エリア（最大5件） =====================================
   // 定数・要素参照
   const catchesContainer = document.getElementById('catches-container');
   const addCatchRowBtn = document.getElementById('add-catch-row');
   const MAX_CATCH_ROWS = 5;

   // 行の取得
   const getCatchRows = () => Array.from(catchesContainer?.querySelectorAll('.catch-row') || []);

   // 再インデックスとUI更新（簡潔版）
   function updateCatchControls() {
      const rows = getCatchRows();
      rows.forEach((row, idx) => {
         // 各行内の select/input の name を必要なら更新
         row.querySelectorAll('select, input').forEach(el => {
            const name = el.getAttribute('name') || '';
            if (name.startsWith('fishing_results[')) {
               const newName = name.replace(/^fishing_results\[\d+\]/, `fishing_results[${idx}]`);
               el.setAttribute('name', newName);
            }
         });
         const del = row.querySelector('.remove-catch-row');
         if (del) del.classList.toggle('hidden', idx === 0);
      });
      const disabled = rows.length >= MAX_CATCH_ROWS;
      if (addCatchRowBtn) {
         addCatchRowBtn.disabled = disabled;
         addCatchRowBtn.classList.toggle('opacity-50', disabled);
         addCatchRowBtn.classList.toggle('cursor-not-allowed', disabled);
      }
   }

   // 削除（イベント委譲）
   catchesContainer?.addEventListener('click', (e) => {
      const btn = e.target.closest?.('.remove-catch-row');
      if (!btn) return;
      const row = btn.closest('.catch-row');
      if (!row) return;
      // 最低1行を維持
      if (getCatchRows().length <= 1) return;
      row.remove();
      updateCatchControls();
   });

   // 行の追加（最初の行をクローンして値をクリア）
   function addCatchRow() {
      const rows = getCatchRows();
      if (rows.length >= MAX_CATCH_ROWS) return;
      const base = rows[0];
      if (!base) return;
      const clone = base.cloneNode(true);
      clone.querySelectorAll('select, input').forEach(el => {
         if (el.tagName === 'SELECT') el.selectedIndex = 0;
         else el.value = '';
      });
      // 1行目以外は削除ボタンを表示
      const del = clone.querySelector('.remove-catch-row');
      if (del) del.classList.remove('hidden');
      catchesContainer.appendChild(clone);
      updateCatchControls();
   }

   // 追加ボタン
   addCatchRowBtn?.addEventListener('click', addCatchRow);

   // 初期化
   if (catchesContainer) {
      updateCatchControls();
   }
</script>
