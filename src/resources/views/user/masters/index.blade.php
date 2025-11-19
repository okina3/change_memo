<x-app-layout>
   <div class="px-2 py-2 bg-slate-200">
      <section class="text-gray-600 border border-gray-400 rounded-lg bg-white overflow-hidden">
         {{-- マスター管理ページのタイトル --}}
         <h1 class="heading heading_bg">マスター管理</h1>
         <div class="p-3 h-[85vh] overflow-y-scroll overscroll-none">
            {{-- フラッシュメッセージ --}}
            <x-common.flash-message status="session('status')" />
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
      document.addEventListener('click', function(e) {
         if (!e.target.classList.contains('master-delete')) return;
         const type = e.target.dataset.type;
         const id = e.target.dataset.id;
         if (!confirm('本当に削除しますか？ 完全に削除されます。')) return;
         const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
         fetch(`/masters/${type}/${id}`, {
            method: 'DELETE',
            headers: {
               'X-CSRF-TOKEN': token,
               'Accept': 'application/json',
               'Content-Type': 'application/json'
            }
         }).then(res => res.json()).then(data => {
            if (data.ok) {
               // 削除成功: ページを再読み込みして一覧を更新
               location.reload();
            } else {
               alert(data.message || '削除に失敗しました');
            }
         }).catch(err => {
            alert('サーバーエラー');
         });
      });
   </script>
</x-app-layout>
