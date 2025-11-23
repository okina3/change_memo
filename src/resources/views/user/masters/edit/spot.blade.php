<x-app-layout>
   <div class="px-2 py-2 bg-slate-200">
      <section class="text-gray-600 border border-gray-400 rounded-lg overflow-hidden">
         <h1 class="heading">場所の編集</h1>
         <div class="p-3 bg-white">
            <x-common.flash-message status="session('status')" />

            <form action="{{ route('user.spot.update') }}" method="POST" class="max-w-lg">
               @csrf
               @method('patch')
               <input type="hidden" name="spotId" value="{{ $spot->id }}">

               <label class="block mb-2">場所名</label>
               <input name="name" type="text" value="{{ old('name', $spot->name) }}" class="input w-full mb-3" required>

               <div class="flex gap-2">
                  <button type="submit" class="btn bg-violet-700 hover:bg-violet-500">更新</button>
                  <a href="{{ route('user.masters.index', ['tab' => 'spots']) }}" class="btn bg-gray-400">キャンセル</a>
               </div>
            </form>
         </div>
      </section>
   </div>
</x-app-layout>
