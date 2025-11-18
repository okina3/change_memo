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
                  <button class="btn bg-red-600 text-white px-3 py-1 master-delete" data-type="spot"
                     data-id="{{ $spot->id }}">
                     削除
                  </button>
               </td>
            </tr>
         @endforeach
      </tbody>
   </table>
@endif
