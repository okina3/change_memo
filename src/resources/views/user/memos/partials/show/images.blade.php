{{-- 選択したメモの画像の表示 --}}
<div class="mb-10">
   <h2 class="sub_heading mb-1">登録画像</h2>
   {{-- モーダルウィンドウ --}}
   <x-user.big-select-image :getMemoImages='$get_memo_images' />
</div>
