<x-app-layout>
   <div class="px-2 py-2 bg-slate-200">
      <section class="text-gray-600 border border-gray-400 rounded-lg  overflow-hidden">
         {{-- マスター管理ページのタイトル --}}
         <h1 class="heading heading_bg">マスター管理</h1>
         <div class="p-3 h-[85vh] overflow-y-scroll overscroll-none bg-white">
            {{-- フラッシュメッセージ --}}
            <x-common.flash-message status="session('status')" />

            <div class="mb-5">
               {{-- 新規釣り場の登録 --}}
               <form action="{{ route('user.masters.spot.store') }}" method="POST">
                  @csrf
                  <h2 class="sub_heading mb-1">新規釣り場の登録</h2>
                  <div class="flex gap-2 items-center">
                     <input id="new_spot_input" class="w-60 rounded" type="text" name="new_spot"
                        value="{{ old('new_spot') }}" placeholder="例:相模川上流">
                     {{-- 釣り場を保存するボタン --}}
                     <button class="btn bg-blue-800 hover:bg-blue-700" type="submit">
                        保存
                     </button>
                  </div>
                  {{-- エラーメッセージ（新規釣り場の登録） --}}
                  <x-input-error class="mt-2" :messages="$errors->get('new_spot')" />
               </form>
               {{-- 新規エサの登録 --}}
               <form action="{{ route('user.masters.bait.store') }}" method="POST">
                  @csrf
                  <h2 class="sub_heading mb-1">新規エサの登録</h2>
                  <div class="flex gap-2 items-center">
                     <input id="new_bait_input" class="w-60 rounded" type="text" name="new_bait"
                        value="{{ old('new_bait') }}" placeholder="例: アオイソメ">
                     <button type="submit" class="btn bg-blue-800 hover:bg-blue-700" type="submit">
                        保存
                     </button>
                  </div>
                  {{-- エラーメッセージ（新規エサの登録） --}}
                  <x-input-error class="mt-2" :messages="$errors->get('new_bait')" />
               </form>
               {{-- 新規魚名の登録 --}}
               <form action="{{ route('user.masters.fish-name.store') }}" method="POST">
                  @csrf
                  <h2 class="sub_heading mb-1">新規魚名の登録</h2>
                  <div class="flex gap-2 items-center">
                     <input id="new_fish_input" class="w-60 rounded" type="text" name="new_fish_name"
                        value="{{ old('new_fish_name') }}" placeholder="例: ヤマメ">
                     <button class="btn bg-blue-800 hover:bg-blue-700" type="submit">
                        保存
                     </button>
                  </div>
                  {{-- エラーメッセージ（新規魚名の登録） --}}
                  <x-input-error class="mt-2" :messages="$errors->get('new_fish_name')" />
               </form>
            </div>

            {{-- タブ表示と検索エリア --}}
            @include('user.masters.partials.index.tab-list-search')
            {{-- 場所のタブ内容 --}}
            @include('user.masters.partials.index.spots-tab-content')
            {{-- エサのタブ内容 --}}
            @include('user.masters.partials.index.baits-tab-content')
            {{-- 魚名のタブ内容 --}}
            @include('user.masters.partials.index.fish-names-tab-content')
         </div>
      </section>
   </div>

   <script>
      'use strict'

      //削除のアラート
      function deleteCheck() {
         const RESULT = confirm('本当に削除してもいいですか? 完全に削除されます。');
         if (!RESULT) alert("削除をキャンセルしました");
         return RESULT;
      }
   </script>
</x-app-layout>
