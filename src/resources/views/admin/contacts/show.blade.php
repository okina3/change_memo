<x-app-layout>
   <div class="px-2 py-2 bg-rose-100">
      <section class="text-gray-600 border border-gray-400 rounded-lg overflow-hidden">
         {{-- 問い合わせ情報の詳細ページのタイトル --}}
         <h1 class="heading heading_bg bg-rose-900">ユーザーからの問い合わせの詳細</h1>
         <div class="p-3 h-[85vh] bg-white">
            <div class="mb-3">
               {{-- 選択した問い合わせ情報の詳細表示エリア --}}
               @include('admin.contacts.partials.show.contacts')
               {{-- ボタンエリア --}}
               @include('admin.contacts.partials.show.button')
            </div>
         </div>
      </section>
   </div>
</x-app-layout>
