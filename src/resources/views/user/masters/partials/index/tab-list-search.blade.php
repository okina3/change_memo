{{-- タブ表示と検索エリア --}}
<div class="mb-5 sm:flex sm:items-end sm:flex-row">
   {{-- タブ表示 --}}
   <div class="mr-10">
      <p class="mb-2 text-sm text-gray-500">クリックでカテゴリを選択してください。</p>
      <div class="flex items-center">
         <button type="submit" form="searchForm" name="tab" value="spots"
            class="btn-3 {{ $tab === 'spots' ? 'active' : '' }}" title="場所の一覧を表示">
            場所
         </button>
         <button type="submit" form="searchForm" name="tab" value="baits"
            class="btn-3 {{ $tab === 'baits' ? 'active' : '' }}" title="エサの一覧を表示">
            エサ
         </button>
         <button type="submit" form="searchForm" name="tab" value="fishNames"
            class="btn-3 {{ $tab === 'fishNames' ? 'active' : '' }}" title="魚種の一覧を表示">
            魚種
         </button>
      </div>
   </div>
   {{-- 検索フォーム --}}
   <div class=" mt-6 sm:mt-0">
      <p class="mb-2 text-sm text-gray-500">カテゴリごとに検索します。</p>
      <form id="searchForm" method="get" action="{{ route('user.masters.index') }}">
         <input class="px-2 py-1.5 mr-2 w-48 border rounded" type="text" name="keyword" value="{{ $keyword ?? '' }}"
            placeholder="検索キーワード">
         <button class="btn px-3 py-1 text-white bg-blue-800 ">
            検索
         </button>
      </form>
   </div>
</div>
