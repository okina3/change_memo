{{-- 場所のタブ内容 --}}
@if ($tab === 'spots' || $tab === 'spots')
   {{-- タイトル --}}
   <h2 class="sub_heading mb-1">場所名</h2>
   @foreach ($spots as $spot)
      <div class="py-3 flex justify-between items-center border-b border-slate-300">
         {{-- 場所名の表示 --}}
         <p class="mr-5 md:w-[70%] truncate">
            {{ $spot->name }}
         </p>
         {{-- ボタンエリア --}}
         <div class="mt-2 md:w-[30%]">
            <form onsubmit="return deleteCheck()" action="{{ route('user.spot.destroy') }}" method="POST">
               @csrf
               @method('delete')
               <input type="hidden" name="spotId" value="{{ $spot->id }}">
               <button type="submit" class="btn bg-red-600 hover:bg-red-500">
                  削除
               </button>
            </form>
         </div>
      </div>
   @endforeach
@endif
