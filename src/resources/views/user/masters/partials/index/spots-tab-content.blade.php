{{-- 場所のタブ内容 --}}
@if ($tab === 'spots' || $tab === 'spots')
   <table class="w-full">
      <thead>
         <tr class="text-left">
            <th class="p-2">名前</th>
            <th class="p-2">操作</th>
         </tr>
      </thead>
      <tbody>
         @foreach ($spots as $spot)
            <tr class="border-t">
               <td class="p-2">{{ $spot->name }}</td>
               <td class="p-2">
                  <form onsubmit="return deleteCheck()"
                     action="{{ route('user.masters.destroy', ['type' => 'spot', 'id' => $spot->id]) }}" method="POST">
                     @csrf
                     @method('delete')
                     <button type="submit" class="btn px-3 py-1 bg-red-600 text-white">削除</button>
                  </form>
               </td>
            </tr>
         @endforeach
      </tbody>
   </table>
@endif
