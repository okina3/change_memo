<x-app-layout>
   <div class="px-2 py-2 bg-slate-200">
      <section class="min-h-[45vh] text-gray-600 border border-gray-400 rounded-lg bg-white overflow-hidden">
         {{-- メモの編集ページのタイトル --}}
         <h1 class="heading heading_bg">メモ編集</h1>
         {{-- 選択したメモを編集するエリア --}}
         <div class="p-3">
            <form action="{{ route('user.update') }}" method="post">
               @csrf
               @method('patch')
               {{-- 共有中のメモの目印 --}}
               @if ($select_memo->status)
                  <div class="mark_bg">
                     <p class="mark">{{ $select_memo->status }}</p>
                  </div>
               @endif
               {{-- 釣行日・釣行時間・釣り場所 --}}
               @include('user.memos.partials.edit.basic-info')
               {{-- 気象状態 --}}
               @include('user.memos.partials.edit.weather-state')
               {{-- 川の状態 --}}
               @include('user.memos.partials.edit.river-state')
               {{-- エサの入力 --}}
               @include('user.memos.partials.edit.baits')
               {{-- 釣果の入力 --}}
               @include('user.memos.partials.edit.fishing_results')
               {{-- 選択したメモの備考の表示 --}}
               <x-user.edit.content :selectMemo='$select_memo' />
               {{-- 選択したメモに紐づいた既存タグを表示 --}}
               @include('user.memos.partials.edit.tags')
               {{-- 新規タグを表示 --}}
               <x-user.tags.new_tag />
               {{-- 選択したメモに紐づいた画像の表示 --}}
               <x-user.images.list-select-image :allImages='$all_images' :getMemoImagesId="$get_memo_images_id" />
               {{-- 選択されているメモのidを取得 --}}
               <input type="hidden" name="memoId" value="{{ $select_memo->id }}">
               {{-- メモの更新ボタン --}}
               <div class="mb-5">
                  <button class="btn bg-blue-800 hover:bg-blue-700" type="submit">更新する</button>
               </div>
            </form>
            {{-- 戻るボタン --}}
            <x-user.button.back-button />
         </div>
      </section>
   </div>
   <script>
      // Blade から渡された既選択画像データをグローバル変数に格納（list-select-image.js が処理）
      window.__PRESELECTED_IMAGES__ = @json($get_memo_images);
   </script>
   {{-- 固有の JavaScript の読み込み --}}
   {{-- list-select-image.js: 画像クリックでの選択／選択解除とサムネイル表示の制御 --}}
   @vite(['resources/js/user/memos/list-select-image.js'])
</x-app-layout>
