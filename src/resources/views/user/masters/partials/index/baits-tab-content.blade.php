{{-- エサのタブ内容 --}}
@if ($tab === 'baits' || $tab === 'baits')
   <table class="w-full">
      <thead>
         <tr class="text-left">
            <th class="p-2">名前</th>
            <th class="p-2">操作</th>
         </tr>
      </thead>
      <tbody>
         @foreach ($baits as $bait)
            <tr class="border-t">
               <td class="p-2">{{ $bait->name }}</td>
               <td class="p-2">
                  <button class="btn bg-red-600 text-white px-3 py-1 master-delete" data-type="bait"
                     data-id="{{ $bait->id }}">削除</button>
               </td>
            </tr>
         @endforeach
      </tbody>
   </table>
@endif
