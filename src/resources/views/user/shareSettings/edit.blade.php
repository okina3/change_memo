<x-app-layout>
   <div class="px-2 py-2 bg-slate-200">
      <section class="min-h-[45vh] text-gray-600 border border-gray-400 rounded-lg bg-white overflow-hidden">
         {{-- 共有中のメモの編集ページのタイトル --}}
         <h1 class="heading heading_bg">共有のメモ編集</h1>
         {{-- 選択した共有メモを編集するエリア --}}
         <div class="p-3">
            {{-- 選択した共有メモのユーザーの名前を表示 --}}
            <div class="mb-5 flex items-center font-semibold">
               <p class="text-blue-700 border-b border-slate-500">
                  {{ optional($select_user)->name ?? '' }}
               </p>
               <p class="ml-1">さん のメモ</p>
            </div>
            {{-- コメント --}}
            <p class="mb-5">※「備考」のみ編集可能。</p>
            {{-- 編集中の共有メモの表示 --}}
            <form action="{{ route('user.share-setting.update') }}" method="post">
               @csrf
               @method('patch')
               {{-- 選択した共有メモの釣行日・釣行時間・釣り場所を表示 --}}
               @include('user.memos.partials.show.basic-info')
               {{-- 選択した共有メモの気象状態を表示 --}}
               @include('user.memos.partials.show.weather-state')
               {{-- 選択した共有メモの川の状態を表示 --}}
               @include('user.memos.partials.show.river-state')
               {{-- 選択した共有メモのエサの入力を表示 --}}
               @include('user.memos.partials.show.baits')
               {{-- 選択した共有メモの釣果の入力を表示 --}}
               @include('user.memos.partials.show.fishing_results')
               {{-- 選択した共有メモの備考を表示 --}}
               @include('user.memos.partials.edit.content')
               {{-- 選択した共有メモに紐づいたタグの表示 --}}
               @include('user.memos.partials.show.tags')
               {{-- 選択した共有メモに紐づいた画像を表示 --}}
               @include('user.memos.partials.show.images')
               {{-- 選択されている共有メモのidを取得 --}}
               <input type="hidden" name="memoId" value="{{ $select_memo->id }}">
               {{-- 更新するボタン --}}
               <div class="mb-5">
                  <button class="btn bg-blue-800 hover:bg-blue-700" type="submit">更新する</button>
               </div>
            </form>
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
