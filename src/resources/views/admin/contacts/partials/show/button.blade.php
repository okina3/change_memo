{{-- ボタンエリア --}}
<div>
   {{-- 削除ボタン --}}
   <form action="{{ route('admin.contact.destroy') }}" method="post">
      @csrf
      @method('delete')
      {{-- 選択されている問い合わせ情報のidを取得 --}}
      <input type="hidden" name="contentId" value="{{ $select_contact->id }}">
      <button class="btn bg-red-600 hover:bg-red-500" type="submit">削除</button>
   </form>
   {{-- 戻るボタン --}}
   <div class="mb-2 flex justify-end">
      <button onclick="location.href='{{ route('admin.contact.index') }}'" class="btn bg-gray-800 hover:bg-gray-700">
         戻る
      </button>
   </div>
</div>
