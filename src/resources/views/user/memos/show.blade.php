<x-app-layout>
   <div class="px-2 py-2 bg-slate-200">
      <section class="min-h-[45vh] text-gray-600 border border-gray-400 rounded-lg bg-white overflow-hidden">
         {{-- メモの詳細ページのタイトル --}}
         <h1 class="heading heading_bg">メモ詳細</h1>
         {{-- 選択したメモの詳細を表示するエリア --}}
         <div class="p-3">
            {{-- メモの共有設定を表示するエリア --}}
            @include('user.memos.partials.show.memo-share-status', [
                'selectMemoId' => $select_memo->id,
                'sharedUsers' => $shared_users,
            ])
            {{-- メモの詳細を表示エリア --}}
            <div class="mb-3">
               {{-- 共有中のメモの目印 --}}
               @if ($select_memo->status)
                  <div class="mark_bg">
                     <p class="mark">{{ $select_memo->status }}</p>
                  </div>
               @endif
               {{-- 釣行日・釣行時間・釣り場所 --}}
               @include('user.memos.partials.show.basic-info')
               {{-- 気象状態 --}}
               @include('user.memos.partials.show.weather-state')
               {{-- 川の状態 --}}
               @include('user.memos.partials.show.river-state')
               {{-- エサの入力 --}}
               @include('user.memos.partials.show.baits')
               {{-- 釣果の入力 --}}
               @include('user.memos.partials.show.catches')
               {{-- メモの備考表示 --}}
               @include('user.memos.partials.show.content')
               {{-- タグの表示 --}}
               @include('user.memos.partials.show.tags')
               {{-- 画像の表示 --}}
               @include('user.memos.partials.show.images')
               {{-- 戻るボタン --}}
               <div class="mb-2 flex justify-end">
                  <button onclick="location.href='{{ route('user.index') }}'" class="btn bg-gray-800 hover:bg-gray-700">
                     戻る
                  </button>
               </div>
            </div>
         </div>
      </section>
   </div>
   <script>
      'use strict'
      // 共有情報を見る為の、アコーディオンの為の記述
      document.addEventListener('DOMContentLoaded', function() {
         // アコーディオンのボタンと、共有設定の備考を取得
         const INFORMATION = document.getElementById('shared-information');
         const BUTTON = document.getElementById('shared-button');
         // ボタンがクリックされたときの処理
         BUTTON.addEventListener('click', function() {
            // 共有設定の備考の表示/非表示を切り替え
            INFORMATION.classList.toggle('active');
         });
      });
   </script>
</x-app-layout>
