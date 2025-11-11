<x-app-layout>
   <div class="px-2 py-2 bg-slate-200">
      <section class="min-h-[45vh] text-gray-600 border border-gray-400 rounded-lg bg-white overflow-hidden">
         {{-- 共有中のメモの詳細ページのタイトル --}}
         <h1 class="heading heading_bg">共有のメモ詳細</h1>
         {{-- 選択した共有メモの詳細を表示するエリア --}}
         <div class="p-3">
            {{-- 選択した共有メモのユーザーの名前を表示 --}}
            <div class="mb-5 flex items-center font-semibold">
               <p class="text-blue-700 border-b border-slate-500">
                  {{ optional($select_user)->name ?? '' }}
               </p>
               <p class="ml-1">さん のメモ</p>
            </div>
            {{-- 選択した共有メモの釣行日・釣行時間・釣り場所を表示 --}}
            <x-user.memos.show.basic-info :selectMemo='$select_memo' />
            {{-- 選択した共有メモの気象状態を表示 --}}
            <x-user.memos.show.weather-state :selectMemo='$select_memo' />
            {{-- 選択した共有メモの川の状態を表示 --}}
            <x-user.memos.show.river-state :selectMemo='$select_memo' />
            {{-- 選択した共有メモのエサの入力を表示 --}}
            <x-user.memos.show.baits :getMemoBaitsName='$get_memo_baits_name' />
            {{-- 選択した共有メモの釣果の入力を表示 --}}
            <x-user.memos.show.fishing_results :getMemoFishResults='$get_memo_fish_results' />
            {{-- 選択した共有メモの備考の表示 --}}
            <x-user.memos.show.content :selectMemo='$select_memo' />
            {{-- 選択した共有メモに紐づいたタグの表示 --}}
            <x-user.memos.show.tags :getMemoTagsName='$get_memo_tags_name' />
            {{-- 選択した共有メモに紐づいた画像の表示 --}}
            <x-user.memos.show.big-select-image :getMemoImages='$get_memo_images' />
            {{-- 戻るボタン --}}
            <div class="mb-2 flex justify-end">
               <button class="btn bg-gray-800 hover:bg-gray-700"
                  onclick="location.href='{{ route('user.share-setting.index') }}'">
                  戻る
               </button>
            </div>
         </div>
      </section>
   </div>
</x-app-layout>
