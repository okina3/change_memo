<div class="mb-8">
   {{-- エサの入力 --}}
   <div class="md:flex-row md:gap-8 flex flex-col items-start gap-6">
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
                        <option value="">エサの選択してください</option>
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
      {{-- 新規エサの追加 --}}
      <div>
         <h2 class="mt-2 mb-1 block text-sm text-gray-700">（新規エサを選択肢に追加）</h2>
         <div class="flex gap-2 items-center">
            <input id="new_bait_input" class="w-60 rounded" type="text" name="new_bait" value="{{ old('new_bait') }}"
               placeholder="例: アオイソメ">
            <button type="button" id="add_bait_btn" class="btn-2 btn-bk bg-yellow-500 hover:bg-yellow-400">
               追加
            </button>
         </div>
         {{-- エラーメッセージ（新規エサの追加） --}}
         <x-input-error class="mt-2" :messages="$errors->get('new_bait')" />
         {{-- AJAX 用メッセージ表示領域 --}}
         <div id="bait_message" class="mt-2 text-sm" aria-live="polite"></div>
      </div>
   </div>
</div>
<script>
   'use strict'
   // === 新規エサの追加 =====================================
   document.addEventListener('DOMContentLoaded', () => {
      // 追加ボタン、新規エサ入力欄、エサセレクトボックスの要素の取得
      const addBtn = document.getElementById('add_bait_btn');
      const input = document.getElementById('new_bait_input');
      const baitsContainer = document.getElementById('baits-container');
      if (!addBtn || !input || !baitsContainer) return;

      // CSRFトークン取得
      const getCsrfToken = () => {
         const meta = document.querySelector('meta[name="csrf-token"]');
         if (meta) return meta.getAttribute('content');
         const tokenInput = document.querySelector('input[name="_token"]');
         return tokenInput ? tokenInput.value : '';
      };

      // メッセージ表示（フォーム内の表示領域に入れる）
      const messageEl = document.getElementById('bait_message');
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

      // エサの追加の実行
      const addBait = async (newBait) => {
         const url = "{{ route('user.bait.store') }}";
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
            // サーバーへ新規エサを送信する
            const res = await fetch(url, {
               method: 'POST',
               headers,
               body: JSON.stringify({
                  new_bait: newBait
               }),
            });

            // 成功: セレクトに追加
            if (res.status === 201) {
               const data = await res.json();
               // すべてのエサを取得して、各々に新しい option を追加
               const allSelects = Array.from(document.querySelectorAll('select[name="baits[]"]'));
               // 各 select に新しい option を追加
               allSelects.forEach((s) => {
                  const opt = document.createElement('option');
                  opt.value = data.id;
                  opt.textContent = data.name;
                  s.appendChild(opt);
               });
               input.value = '';
               showMessage('エサの選択肢に追加しました', 'success');
               setTimeout(clearMessage, 6000);
               return;
            }

            // 失敗: 422エラーメッセージを表示
            if (res.status === 422) {
               const data = await res.json().catch(() => ({}));
               const serverMsg = data?.errors?.new_bait?.[0] ?? data?.message;
               // 通常のバリデーションではじかれた場合のエラーメッセージ（保険）。
               showMessage(serverMsg ?? '入力に誤りがあります', 'error');
               return;
            }

            // 失敗: それ以外のエラーメッセージを表示
            try {
               const otherData = await res.json().catch(() => ({}));
               const otherMsg = otherData?.errors?.new_bait?.[0] ?? otherData?.message;
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
         const newBait = input.value.trim();
         if (!newBait) {
            showMessage('エサを入力してください。', 'error');
            return;
         }
         addBait(newBait);
      });
   });


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
