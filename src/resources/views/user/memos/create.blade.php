<x-app-layout>
   <div class="px-2 py-2 bg-slate-200">
      <section class="min-h-[45vh] text-gray-600 border border-gray-400 rounded-lg bg-white overflow-hidden">
         {{-- メモの新規作成ページのタイトル --}}
         <h1 class="heading heading_bg">新規メモ作成</h1>
         {{-- メモを新規作成するエリア --}}
         <div class="p-3">
            <form action="{{ route('user.store') }}" method="post">
               @csrf
               {{-- 釣行日・釣行時間・釣り場所 --}}
               @include('user.memos.partials.create.basic-info')
               {{-- 気象状態 --}}
               @include('user.memos.partials.create.weather-state')
               {{-- 川の状態 --}}
               @include('user.memos.partials.create.river-state')
               {{-- エサの入力 --}}
               @include('user.memos.partials.create.baits')
               {{-- 釣果の入力 --}}
               @include('user.memos.partials.create.fishing_results')
               {{-- メモの備考入力 --}}
               @include('user.memos.partials.create.content')
               {{-- タグの選択 --}}
               @include('user.memos.partials.create.tags')
               {{-- 新規タグ入力 --}}
               <x-user.tags.new_tag />
               {{-- 画像の選択 --}}
               <x-user.images.list-select-image :allImages='$all_images' />
               {{-- メモの保存ボタン --}}
               <div class="mb-5">
                  <button class="btn bg-blue-800 hover:bg-blue-700" type="submit">保存する</button>
               </div>
            </form>
            {{-- 戻るボタン --}}
            <x-user.button.back-button />
         </div>
      </section>
   </div>
   {{-- 固有の JavaScript の読み込み --}}
   {{-- list-select-image.js: 画像クリックでの選択／選択解除とサムネイル表示の制御 --}}
   @vite(['resources/js/user/memos/list-select-image.js'])
</x-app-layout>
