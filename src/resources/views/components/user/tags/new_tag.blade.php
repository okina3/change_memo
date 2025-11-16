{{-- 新規タグ入力 --}}
<div class="mb-10">
   <h2 class="sub_heading mb-1">新規タグの追加</h2>
   <input class="w-60 rounded" type="text" name="new_tag" value="{{ old('new_tag') }}" placeholder="ここに新規タグを入力" />
   {{-- エラーメッセージ（新規タグ） --}}
   <x-input-error class="mt-2" :messages="$errors->get('new_tag')" />
</div>
