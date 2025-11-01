<div class="mb-8">
   {{-- エサの入力 --}}
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
                     <select class="rounded w-60" name="baits[]">
                        <option value="">選択してください</option>
                        @foreach ($all_baits as $bait)
                           <option value="{{ $bait->id }}" @selected((old('baits', [])[$i] ?? '') == $bait->id)>
                              {{ $bait->name }}
                           </option>
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
      {{-- <div class="">
         <h2 class="mt-2 mb-1 text-sm text-gray-700">（エサ名を追加）</h2>
         <input class="w-60 rounded" type="text" name="new_bait" value="{{ old('new_bait') }}"
            placeholder="例: アオイソメ"> --}}
         {{-- エラーメッセージ（エサ追加） --}}
         {{-- <x-input-error class="mt-2" :messages="$errors->get('new_bait')" />
      </div> --}}
   </div>
</div>
<script>
   'use strict'
   // === エサ入力エリア（最大5件） =====================================
   // 定数・要素参照
   const baitsContainer = document.getElementById('baits-container');
   const addBaitRowBtn = document.getElementById('add-bait-row');
   const MAX_BAIT_ROWS = 5;

   // 行の取得
   const getBaitRows = () => Array.from(baitsContainer?.querySelectorAll('.bait-row') || []);

   // 再インデックスとUI更新
   function updateBaitControls() {
      const rows = getBaitRows();
      // 削除ボタンの表示制御
      rows.forEach((row, idx) => {
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

   // 削除（イベント委譲）
   baitsContainer?.addEventListener('click', (e) => {
      const btn = e.target.closest?.('.remove-bait-row');
      if (!btn) return;
      const row = btn.closest('.bait-row');
      if (!row) return;
      // 最低1行を維持
      if (getBaitRows().length <= 1) return;
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
      // 1行目以外は削除ボタンを表示
      const del = clone.querySelector('.remove-bait-row');
      if (del) del.classList.remove('hidden');
      baitsContainer.appendChild(clone);
      updateBaitControls();
   }

   // 追加ボタン
   addBaitRowBtn?.addEventListener('click', addBaitRow);
   // 初期化
   updateBaitControls();
</script>
