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
      <div class="lg:flex gap-6">
         {{-- 新規魚名の追加 --}}
         <div>
            <h2 class="mt-2 mb-1 block text-sm text-gray-700">（新規魚種を選択肢に追加）</h2>
            <div class="flex gap-2 items-center">
               <input id="new_fish_input" class="w-60 rounded" type="text" name="new_fish"
                  value="{{ old('new_fish') }}" placeholder="例: ヤマメ">
               <button type="button" id="add_fish_btn" data-url="{{ route('user.fish-name.store') }}"
                  class="btn-2 btn-bk bg-yellow-500 hover:bg-yellow-400">
                  追加
               </button>
            </div>
            {{-- エラーメッセージ（新規魚名の追加） --}}
            <x-input-error class="mt-2" :messages="$errors->get('new_fish')" />
            {{-- AJAX 用メッセージ表示領域 --}}
            <div id="fish_message" class="mt-2 text-sm" aria-live="polite"></div>
         </div>
         {{-- 釣果合計 --}}
         <div id="catch-total" class="lg:mt-6 mt-3 p-1 w-28 border rounded self-center">
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
   // === 新規魚種の追加 =====================================
   document.addEventListener('DOMContentLoaded', () => {
      // 追加ボタン、新規魚名入力欄の要素取得
      const addBtn = document.getElementById('add_fish_btn');
      const input = document.getElementById('new_fish_input');
      if (!addBtn || !input) return;

      // CSRFトークン取得
      const getCsrfToken = () => {
         const meta = document.querySelector('meta[name="csrf-token"]');
         if (meta) return meta.getAttribute('content');
         const tokenInput = document.querySelector('input[name="_token"]');
         return tokenInput ? tokenInput.value : '';
      };

      // メッセージ表示（フォーム内の表示領域に入れる）
      const messageEl = document.getElementById('fish_message');
      const clearMessage = () => {
         if (!messageEl) return;
         messageEl.textContent = '';
         messageEl.classList.remove('text-red-600', 'text-green-600');
      };

      // メッセージの表示色を決定する。（エラーは赤、成功は緑で表示）
      const showMessage = (message, type = 'error') => {
         if (!messageEl) return;
         messageEl.textContent = message || '';
         messageEl.classList.remove('text-red-600', 'text-green-600');
         if (type === 'error') {
            messageEl.classList.add('text-red-600');
         } else if (type === 'success') {
            messageEl.classList.add('text-green-600');
         }
      };

      // 魚種の追加の実行
      const addFish = async (newFish) => {
         const url = addBtn.dataset.url || "{{ route('user.fish-name.store') }}";
         const headers = {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
            'Accept': 'application/json',
         };

         // ボタン無効化と表示を変更
         addBtn.disabled = true;
         const originalText = addBtn.textContent;
         addBtn.textContent = '追加中...';

         try {
            // サーバーへ新規魚名を送信する
            const res = await fetch(url, {
               method: 'POST',
               headers,
               body: JSON.stringify({
                  new_fish: newFish
               }),
            });

            // 成功: 全ての魚種選択欄に新しい option を追加
            if (res.status === 201) {
               const data = await res.json();
               const allSelects = Array.from(document.querySelectorAll('select[name$="[fish_name_id]"]'));
               allSelects.forEach((s) => {
                  const opt = document.createElement('option');
                  opt.value = data.id;
                  opt.textContent = data.name;
                  s.appendChild(opt);
               });
               input.value = '';
               showMessage('魚種の選択肢に追加しました', 'success');
               setTimeout(clearMessage, 6000);
               return;
            }

            // 失敗: 422エラーメッセージを表示
            if (res.status === 422) {
               const data = await res.json().catch(() => ({}));
               const serverMsg = data?.errors?.new_fish?.[0] ?? data?.message;
               // 通常のバリデーションではじかれた場合のエラーメッセージ（保険）。
               showMessage(serverMsg ?? '入力に誤りがあります', 'error');
               return;
            }

            // 失敗: それ以外のエラーメッセージを表示
            try {
               const otherData = await res.json().catch(() => ({}));
               const otherMsg = otherData?.errors?.new_fish?.[0] ?? otherData?.message;
               // 通常のバリデーションではじかれた場合のエラーメッセージ（保険）。
               showMessage(otherMsg ?? '追加に失敗しました。時間をおいて再試行してください。', 'error');
            } catch (err) {
               // 通常のバリデーションではじかれた場合のエラーメッセージ（保険）。
               showMessage('追加に失敗しました。時間をおいて再試行してください。', 'error');
            }

         } catch (e) {
            console.error(e);
            // 通常のバリデーションではじかれた場合のエラーメッセージ（保険）。
            showMessage('通信エラーが発生しました', 'error');
         } finally {
            addBtn.disabled = false;
            addBtn.textContent = originalText;
         }
      };

      // 追加ボタンにクリックイベントリスナーを追加
      addBtn.addEventListener('click', () => {
         // メッセージをクリアし、入力値をトリムしてサーバーへ送信
         clearMessage();
         const newFish = input.value.trim();
         if (!newFish) {
            showMessage('魚種名を入力してください。', 'error');
            return;
         }
         addFish(newFish);
      });
   });


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
