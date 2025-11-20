{{-- 魚名のタブ内容 --}}
@if ($tab === 'fishNames' || $tab === 'fishNames')
   {{-- タイトル --}}
   <h2 class="sub_heading mb-1">魚名</h2>
   @foreach ($fishNames as $fish)
      <div class="py-3 flex justify-between items-center border-b border-slate-300">
         {{-- 魚名の表示 --}}
         <p class="mr-5 md:w-[70%] truncate">
            {{ $fish->name }}
         </p>
         {{-- ボタンエリア --}}
         <div class="mt-2 md:w-[30%]">
            <form onsubmit="return deleteCheck()"
               action="{{ route('user.masters.destroy', ['type' => 'fish-name', 'id' => $fish->id]) }}" method="POST">
               @csrf
               @method('delete')
               <button type="submit" class="btn bg-red-600 hover:bg-red-500">
                  削除
               </button>
            </form>
         </div>
      </div>
   @endforeach
@endif
