<x-app-layout>
   {{-- 場所名の編集 --}}
   <div class="px-2 py-2 bg-slate-200">
      <section class="text-gray-600 border border-gray-400 rounded-lg bg-white overflow-hidden">
         {{-- タイトル --}}
         <h1 class="heading heading_bg">場所の編集</h1>
         <div class="p-3">
            {{-- フラッシュメッセージ --}}
            <x-common.flash-message status="session('status')" />
            {{-- 選択したメモを編集するエリア --}}
            <form action="{{ route('user.spot.update') }}" method="POST">
               @csrf
               @method('patch')
               <label class="block mb-2">場所名</label>
               <input class="input w-full mb-3 required" name="name" type="text"
                  value="{{ old('name', $spot->name) }}">
               <input type="hidden" name="spotId" value="{{ $spot->id }}">
               {{-- 更新ボタン --}}
               <div class="mb-5">
                  <button class="btn bg-blue-800 hover:bg-blue-700" type="submit">更新する</button>
               </div>
            </form>
            {{-- 戻るボタン --}}
            <x-user.button.back-button-masters :tab="'spots'" />
         </div>
      </section>
   </div>
</x-app-layout>
