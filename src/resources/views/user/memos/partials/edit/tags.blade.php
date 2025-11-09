{{-- 選択したメモに紐づいた既存タグを表示 --}}
<div class="mb-10">
   <h2 class="sub_heading mb-1">既存タグの選択</h2>
   <div class="flex flex-wrap gap-3">
      @foreach ($all_tags as $tag)
         <div class="flex items-center gap-1 hover:font-semibold">
            <input class="mb-1 rounded" type="checkbox" name="tags[]" id="{{ $tag->id }}" value="{{ $tag->id }}"
               {{ in_array($tag->id, $get_memo_tags_id) ? 'checked' : '' }} />
            <label for="{{ $tag->id }}">{{ $tag->name }}</label>
         </div>
      @endforeach
   </div>
</div>
{{-- 新規タグ入力 --}}
<div class="mb-10">
   <h2 class="sub_heading mb-1">新規タグの追加</h2>
   <div class="mr-5">
      <input class="w-60 rounded" type="text" name="new_tag" placeholder="ここに新規タグを入力" />
   </div>
   {{-- エラーメッセージ（新規タグ） --}}
   <x-input-error class="mt-2" :messages="$errors->get('new_tag')" />
</div>
