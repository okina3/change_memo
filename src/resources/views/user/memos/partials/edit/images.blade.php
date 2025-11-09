{{-- 選択したメモに紐づいた画像の表示 --}}
<div class="mb-10">
   <h2 class="sub_heading mb-1">画像の選択</h2>
   {{-- モーダルウィンドウ --}}
   <x-user.list-select-image :allImages='$all_images' :getMemoImagesId="$get_memo_images_id" />
</div>