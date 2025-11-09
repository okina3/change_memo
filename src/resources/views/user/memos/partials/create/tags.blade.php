{{-- 既存タグの選択 --}}
<div class="mb-10">
   <h2 class="sub_heading mb-1">既存タグの選択</h2>
   <div class="flex flex-wrap gap-3">
      @foreach ($all_tags as $tag)
         <div class="flex items-center gap-1 hover:font-semibold">
            <input class="mb-1 rounded" type="checkbox" name="tags[]" id="{{ $tag->id }}" value="{{ $tag->id }}"
               @checked(in_array($tag->id, old('tags', []))) />
            <label for="{{ $tag->id }}">{{ $tag->name }}</label>
         </div>
      @endforeach
   </div>
</div>
{{-- 新規タグ入力 --}}
<div class="mb-10">
   <h2 class="sub_heading mb-1">新規タグの追加</h2>
   <input class="w-60 rounded" type="text" name="new_tag" value="{{ old('new_tag') }}" placeholder="ここに新規タグを入力" />
   {{-- エラーメッセージ（新規タグ） --}}
   <x-input-error class="mt-2" :messages="$errors->get('new_tag')" />
</div>
