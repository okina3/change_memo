<div class="mb-8">
   {{-- エサ --}}
   <div class="md:flex-row md:gap-8 flex flex-col items-start gap-6">
      <div class="">
         <h2 class="sub_heading mb-1">エサ</h2>
         <div>
            <div id="baits-container" class="space-y-2">
               <div class="flex items-center gap-3 bait-row">
                  @foreach ($get_memo_baits_name as $bait_name)
                     <div class="p-2 w-52 border border-gray-500 rounded">
                        {{ $bait_name }}
                     </div>
                  @endforeach
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
