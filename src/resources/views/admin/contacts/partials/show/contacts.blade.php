{{-- 選択した問い合わせ情報の詳細を表示するエリア --}}
{{-- 選択した問い合わせ情報のユーザー名を表示 --}}
<div class="mb-5">
   <h2 class="sub_heading mb-1">ユーザー名</h2>
   <p class="p-2 border border-gray-500 rounded">{{ $select_contact->user->name }}</p>
</div>
{{-- 選択した問い合わせ情報のメールアドレスを表示 --}}
<div class="mb-5">
   <h2 class="sub_heading mb-1">メールアドレス</h2>
   <p class="p-2 border border-gray-500 rounded">{{ $select_contact->user->email }}</p>
</div>
{{-- 選択した問い合わせ情報の件名を表示 --}}
<div class="mb-5">
   <h2 class="sub_heading mb-1">件名</h2>
   <p class="p-2 border border-gray-500 rounded">{{ $select_contact->subject }}</p>
</div>
{{-- 選択した問い合わせ情報の内容を表示 --}}
<div class="mb-5">
   <h2 class="sub_heading mb-1">問い合わせ内容</h2>
   <textarea class="w-full rounded" name="content" rows="7" disabled>{{ $select_contact->message }}</textarea>
</div>
