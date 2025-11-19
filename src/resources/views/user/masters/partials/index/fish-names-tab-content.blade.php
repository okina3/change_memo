{{-- 魚名のタブ内容 --}}
@if ($tab === 'fishNames' || $tab === 'fishNames')
   <div>
      <table class="w-full">
         <thead>
            <tr class="text-left">
               <th class="p-2">名前</th>
               <th class="p-2">操作</th>
            </tr>
         </thead>
         <tbody>
            @foreach ($fishNames as $fish)
               <tr class="border-t">
                  <td class="p-2">{{ $fish->name }}</td>
                  <td class="p-2">
                     <button class="btn bg-red-600 text-white px-3 py-1 master-delete" data-type="fish-name"
                        data-id="{{ $fish->id }}">削除</button>
                  </td>
               </tr>
            @endforeach
         </tbody>
      </table>
   </div>
@endif
