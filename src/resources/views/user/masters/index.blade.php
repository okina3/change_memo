<x-app-layout>
   <div class="px-2 py-2 bg-slate-200">
      <section class="text-gray-600 border border-gray-400 rounded-lg bg-white overflow-hidden">
         {{-- マスター管理ページのタイトル --}}
         <h1 class="heading heading_bg">マスター管理</h1>
         <div class="p-3 h-[85vh] overflow-y-scroll overscroll-none">
            {{-- 検索エリア --}}
            <div class="my-3">
               <form method="get" action="{{ route('user.masters.index') }}">
                  <select class="px-2 py-1.5 mr-2 w-24 border rounded" name="tab">
                     <option value="spots" {{ $tab === 'spots' ? 'selected' : '' }}>場所</option>
                     <option value="baits" {{ $tab === 'baits' ? 'selected' : '' }}>エサ</option>
                     <option value="fishNames" {{ $tab === 'fishNames' ? 'selected' : '' }}>魚種</option>
                  </select>
                  <input class="px-2 py-1.5 mr-2 w-48 border rounded" type="text" name="q"
                     value="{{ $q ?? '' }}" placeholder="検索キーワード">
                  <button class="btn bg-blue-800 text-white px-3 py-1">検索</button>
               </form>
            </div>

            <div>
               {{-- タブ表示 --}}
               <div class="mb-3">
                  <a href="?tab=spots"
                     class="px-3 py-1 mr-2 {{ $tab === 'spots' ? 'bg-blue-600 text-white rounded' : 'bg-gray-100 rounded' }}">場所</a>
                  <a href="?tab=baits"
                     class="px-3 py-1 mr-2 {{ $tab === 'baits' ? 'bg-blue-600 text-white rounded' : 'bg-gray-100 rounded' }}">エサ</a>
                  <a href="?tab=fishNames"
                     class="px-3 py-1 mr-2 {{ $tab === 'fishNames' ? 'bg-blue-600 text-white rounded' : 'bg-gray-100 rounded' }}">魚種</a>
               </div>

               {{-- 各タブの内容 --}}
               @if ($tab === 'spots' || $tab === 'spots')
                  <div>
                     <table class="w-full">
                        <thead>
                           <tr class="text-left">
                              <th class="p-2">ID</th>
                              <th class="p-2">名前</th>
                              <th class="p-2">操作</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($spots as $spot)
                              <tr class="border-t">
                                 <td class="p-2">{{ $spot->id }}</td>
                                 <td class="p-2">{{ $spot->name }}</td>
                                 <td class="p-2">
                                    <button class="btn bg-red-600 text-white px-3 py-1 master-delete" data-type="spot"
                                       data-id="{{ $spot->id }}">削除</button>
                                 </td>
                              </tr>
                           @endforeach
                        </tbody>
                     </table>
                     <div class="mt-3">{{ $spots->links() }}</div>
                  </div>
               @endif

               @if ($tab === 'baits' || $tab === 'baits')
                  <div>
                     <table class="w-full">
                        <thead>
                           <tr class="text-left">
                              <th class="p-2">ID</th>
                              <th class="p-2">名前</th>
                              <th class="p-2">操作</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($baits as $bait)
                              <tr class="border-t">
                                 <td class="p-2">{{ $bait->id }}</td>
                                 <td class="p-2">{{ $bait->name }}</td>
                                 <td class="p-2">
                                    <button class="btn bg-red-600 text-white px-3 py-1 master-delete" data-type="bait"
                                       data-id="{{ $bait->id }}">削除</button>
                                 </td>
                              </tr>
                           @endforeach
                        </tbody>
                     </table>
                     <div class="mt-3">{{ $baits->links() }}</div>
                  </div>
               @endif

               @if ($tab === 'fishNames' || $tab === 'fishNames')
                  <div>
                     <table class="w-full">
                        <thead>
                           <tr class="text-left">
                              <th class="p-2">ID</th>
                              <th class="p-2">名前</th>
                              <th class="p-2">操作</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($fishNames as $fish)
                              <tr class="border-t">
                                 <td class="p-2">{{ $fish->id }}</td>
                                 <td class="p-2">{{ $fish->name }}</td>
                                 <td class="p-2">
                                    <button class="btn bg-red-600 text-white px-3 py-1 master-delete"
                                       data-type="fish-name" data-id="{{ $fish->id }}">削除</button>
                                 </td>
                              </tr>
                           @endforeach
                        </tbody>
                     </table>
                     <div class="mt-3">{{ $fishNames->links() }}</div>
                  </div>
               @endif
            </div>
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
