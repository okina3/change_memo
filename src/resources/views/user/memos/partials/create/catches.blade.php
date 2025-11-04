<div class="mb-8">
   <div class="md:gap-8 md:flex-row md:flex-wrap flex flex-col items-start gap-6">
      {{-- 釣果の入力 --}}
      <div class="">
         <h2 class="sub_heading mb-1">釣果</h2>
         @php
            // 初期表示行数（最低1、最大5）
            $initialRows = max(1, min(count(old('fish_entries', [])), 5));
         @endphp
         <div class="flex items-start gap-6">
            <div id="catches-container" class="space-y-2 flex-1">
               @for ($i = 0; $i < $initialRows; $i++)
                  @php
                     $entry = old('fish_entries', [])[$i] ?? ['fish_name_id' => '', 'count' => '', 'length' => ''];
                  @endphp
                  <div class="flex flex-wrap items-center gap-3 catch-row">
                     {{-- 魚種の選択 --}}
                     <div class="md:w-auto w-full">
                        <select class="w-60 rounded" name="fish_entries[{{ $i }}][fish_name_id]">
                           <option value="">魚種を選択してください</option>
                           @foreach ($all_fish_names as $fish)
                              <option value="{{ $fish->id }}" @selected(($entry['fish_name_id'] ?? '') == $fish->id)>{{ $fish->name }}
                              </option>
                           @endforeach
                        </select>
                     </div>
                     {{-- 釣果（匹） --}}
                     <div class="flex items-center gap-2">
                        <input class="md:w-24 w-20 rounded text-right" type="number"
                           name="fish_entries[{{ $i }}][count]" value="{{ $entry['count'] ?? '' }}"
                           placeholder="0" inputmode="numeric" min="0" step="1" />
                        <span class="text-gray-600">匹</span>
                     </div>
                     {{-- サイズ（cm） --}}
                     <div class="flex items-center gap-2">
                        <input class="md:w-24 w-20 rounded text-right" type="number"
                           name="fish_entries[{{ $i }}][length]" value="{{ $entry['length'] ?? '' }}"
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
         <div class="mt-2">
            <button type="button" id="add-catch-row" class="text-sm text-blue-700 hover:underline">
               ＋釣果入力エリア追加（最大5件）
            </button>
         </div>
      </div>
      {{-- 新規魚名を入力 --}}
      <div class="lg:flex gap-6">
         <div class="">
            <h2 class="mt-2 mb-1 text-sm text-gray-700">（新規魚名を入力）</h2>
            <input class="rounded w-60" type="text" name="new_fish" value="{{ old('new_fish') }}"
               placeholder="例: ヤマメ">
            {{-- エラーメッセージ（新規魚名を入力） --}}
            <x-input-error class="mt-2" :messages="$errors->get('new_fish')" />
         </div>
         {{-- 釣果合計 --}}
         <div id="catch-total" class="lg:mt-8 mt-3 p-1 w-32 border rounded self-center">
            <div class="flex items-baseline justify-center gap-3">
               <div class="text-sm text-gray-600">合計</div>
               <div id="catch-total-number" class="text-2xl font-semibold">0</div>
               <div class="text-sm text-gray-600">匹</div>
            </div>
         </div>
      </div>
   </div>

   {{-- エラーメッセージ（釣果の内訳） --}}
   <x-input-error class="mt-2" :messages="$errors->get('fish_entries.*.fish_name_id')" />
   <x-input-error class="mt-2" :messages="$errors->get('fish_entries.*.count')" />
   <x-input-error class="mt-2" :messages="$errors->get('fish_entries.*.length')" />
</div>

<script>
   'use strict'
   // === 釣果入力エリア（最大5件） =====================================
   // 定数・要素参照
   const catchesContainer = document.getElementById('catches-container');
   const addCatchRowBtn = document.getElementById('add-catch-row');
   const MAX_CATCH_ROWS = 5;

   // 行の取得
   const getCatchRows = () => Array.from(catchesContainer?.querySelectorAll('.catch-row') || []);

   // 再インデックスとUI更新
   function updateCatchControls() {
      const rows = getCatchRows();
      rows.forEach((row, idx) => {
         row.querySelectorAll('select[name^="fish_entries["], input[name^="fish_entries["]').forEach(el => {
            const name = el.getAttribute('name') || '';
            const newName = name.replace(/fish_entries\[\d+\]/, `fish_entries[${idx}]`);
            el.setAttribute('name', newName);
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
      computeTotalCatches();
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
      attachCountListeners(clone);
      computeTotalCatches();
   }

   // 追加ボタン
   addCatchRowBtn?.addEventListener('click', addCatchRow);

   // 入力イベント（匹）
   function attachCountListeners(scope) {
      (scope || document).querySelectorAll('#catches-container input[name$="[count]"]').forEach(input => {
         if (!input._countBound) {
            input.addEventListener('input', computeTotalCatches);
            input._countBound = true;
         }
      });
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

   // 初期化
   if (catchesContainer) {
      updateCatchControls();
      attachCountListeners(catchesContainer);
      computeTotalCatches();
   }
</script>
