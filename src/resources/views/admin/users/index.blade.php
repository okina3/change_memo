<x-app-layout>
   <div class="px-2 py-2 bg-rose-100">
      {{-- フラッシュメッセージ --}}
      <x-common.flash-message status="session('status')" />
      {{-- ユーザーの検索の表示エリア --}}
      @include('admin.users.partials.index.users-search')
      {{-- 登録ユーザー一覧の表示エリア --}}
      @include('admin.users.partials.index.users-list')
   </div>
   <script>
      'use strict'

      // 削除のアラート
      function deleteCheck() {
         const RESULT = confirm('本当に利用停止してもいいですか?');
         if (!RESULT) alert("キャンセルしました");
         return RESULT;
      }
   </script>
</x-app-layout>
