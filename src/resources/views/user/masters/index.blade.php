<x-app-layout>
   <div class="px-2 py-2 bg-slate-200">
      <section class="text-gray-600 border border-gray-400 rounded-lg  overflow-hidden">
         {{-- マスター管理ページのタイトル --}}
         <h1 class="heading heading_bg">マスター管理</h1>
         <div class="p-3 h-[85vh] overflow-y-scroll overscroll-none bg-white">
            {{-- フラッシュメッセージ --}}
            <x-common.flash-message status="session('status')" />

            {{-- 釣り場の登録 --}}
            <div>
               <h2 class="mb-1 block text-sm text-gray-700">（釣り場を登録）</h2>
               <div class="flex gap-2 items-center">
                  <input id="new_spot_input" class="w-60 rounded" type="text" name="new_spot"
                     value="{{ old('new_spot') }}" placeholder="例:相模川上流">
                  <button type="button" id="add_spot_btn" data-url="{{ route('') }}"
                     class="btn-2 btn-bk bg-yellow-500 hover:bg-yellow-400">
                     追加
                  </button>
               </div>
               {{-- エラーメッセージ（釣り場の登録） --}}
               <x-input-error class="mt-2" :messages="$errors->get('new_spot')" />
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
