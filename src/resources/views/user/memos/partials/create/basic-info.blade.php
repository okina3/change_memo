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
      <div class="sm:flex-row flex flex-col items-start gap-8">
         {{-- 釣り場所 --}}
         <div>
            <label class="mb-1 block text-sm text-gray-700">場所</label>
            <select name="fishing_spot" id="fishing_spot_select" class="w-60 rounded">
               <option value="" @selected(old('fishing_spot', '') == '')>
                  場所を選択してください
               </option>
               @foreach ($all_spots as $spot)
                  <option value="{{ $spot->id }}" @selected(old('fishing_spot') == $spot->id)>
                     {{ $spot->name }}
                  </option>
               @endforeach
            </select>
            {{-- エラーメッセージ（場所） --}}
            <x-input-error class="mt-2" :messages="$errors->get('fishing_spot')" />
         </div>
         {{-- 新規釣り場の入力 --}}
         <div>
            <h2 class="mb-1 block text-sm text-gray-700">（新規釣り場の入力）</h2>
            <div class="flex gap-2 items-center">
               <input id="new_spot_input" class="sm:w-56 md:w-56 w-full rounded" type="text" name="new_spot"
                  value="{{ old('new_spot') }}" placeholder="相模川上流">
               <button type="button" id="add_spot_btn" class="px-3 py-1 rounded border border-gray-300 text-sm">
                  追加
               </button>
            </div>
            {{-- AJAX 用メッセージ表示領域 --}}
            <div id="spot_message" class="mt-2 text-sm" aria-live="polite"></div>
         </div>
      </div>
   </div>
</div>
<script>
   document.addEventListener('DOMContentLoaded', () => {
      const addBtn = document.getElementById('add_spot_btn');
      const input = document.getElementById('new_spot_input');
      const select = document.getElementById('fishing_spot_select');
      if (!addBtn || !input || !select) return;

      // CSRF トークン取得（meta タグ優先、その後 _token フィールドを参照）
      const getCsrfToken = () => {
         const meta = document.querySelector('meta[name="csrf-token"]');
         if (meta) return meta.getAttribute('content');
         const tokenInput = document.querySelector('input[name="_token"]');
         return tokenInput ? tokenInput.value : '';
      };

      // メッセージ表示（フォーム内の表示領域に入れる）
      const messageEl = document.getElementById('spot_message');
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

      // サーバからのエラーから表示用のメッセージ文字列を抽出して返す。
      const extractValidationMessage = async (res) => {
         const data = await res.json().catch(() => ({}));
         const errors = Object.values(data.errors || {}).flat();
         if (errors.length) return errors.join('\n');
         return data.message || null;
      };

      // スポット追加の実行
      const addSpot = async (name) => {
         const url = "{{ route('user.spot.store') }}";
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
            const res = await fetch(url, {
               method: 'POST',
               headers,
               body: JSON.stringify({
                  name
               }),
            });

            // 成功: セレクトに追加して選択状態にする
            if (res.status === 201) {
               const data = await res.json();
               const opt = document.createElement('option');
               opt.value = data.id;
               opt.textContent = data.name;
               opt.selected = true;
               select.appendChild(opt);
               select.dispatchEvent(new Event('change'));
               input.value = '';
               showMessage('新規釣り場を追加しました', 'success');
               setTimeout(clearMessage, 3000);
               return;
            }

            // 失敗: 重複エラーメッセージ（バリデーションから）
            if (res.status === 422) {
               const msg = await extractValidationMessage(res);
               showMessage(msg || '入力エラーが発生しました', 'error');
               return;
            }

            // 失敗: それ以外のエラーメッセージ
            showMessage('新規釣り場の追加に失敗しました。時間をおいて再試行してください。', 'error');

         } catch (e) {
            console.error(e);
            showMessage('通信エラーが発生しました', 'error');
         } finally {
            addBtn.disabled = false;
            addBtn.textContent = originalText;
         }
      };

      // クリックハンドラ（空文字の送信のエラーメッセージ）
      addBtn.addEventListener('click', () => {
         clearMessage();
         const name = input.value.trim();
         if (!name) {
            showMessage('新規釣り場を入力してください', 'error');
            return;
         }
         addSpot(name);
      });
   });
</script>
