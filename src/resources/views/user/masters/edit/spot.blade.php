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
               <input type="hidden" name="spotId" value="{{ $spot->id }}">

               <label class="block mb-2">場所名</label>
               <input name="name" type="text" value="{{ old('name', $spot->name) }}" class="input w-full mb-3"
                  required>

               {{-- ボタンエリア --}}
               <div class="flex gap-2">
                  {{-- 更新ボタン --}}
                  <div class="mb-5">
                     <button class="btn bg-blue-800 hover:bg-blue-700" type="submit">更新する</button>
                  </div>
                  {{-- <button type="submit" class="btn bg-violet-700 hover:bg-violet-500">更新する</button> --}}
                  <a href="{{ route('user.masters.index', ['tab' => 'spots']) }}" class="btn bg-gray-400">キャンセル</a>
               </div>
            </form>
         </div>
      </section>
   </div>
</x-app-layout>
