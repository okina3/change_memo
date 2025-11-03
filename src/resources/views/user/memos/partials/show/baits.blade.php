<div class="mb-8">
   {{-- エサの入力 --}}
   <div class="md:flex-row md:gap-8 flex flex-col items-start gap-6">
      <div class="">
         <h2 class="sub_heading mb-1">エサ</h2>
         <div>
            <div id="baits-container" class="space-y-2">
               <div class="flex items-center gap-3 bait-row">

                  {{-- @foreach ($$select_memo->baits as $bait)
                     <div class="rounded w-60">
                        {{ $bait->name }}
                     </div>
                  @endforeach --}}
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
