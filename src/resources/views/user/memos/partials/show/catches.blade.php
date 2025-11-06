<div class="mb-8">
   <div class="md:gap-8 md:flex-row md:flex-wrap lg:gap-12 flex flex-col items-start gap-6">
      {{-- 釣果 --}}
      <div class="">
         <h2 class="sub_heading mb-1">釣果</h2>
         <div class="flex items-start">
            <div class="lg:gap-6 flex flex-wrap items-center gap-3 catch-row">
               {{-- 魚名 --}}
               <div class="md:w-auto w-full">
                  {{-- @foreach ($all_fish_names as $fish)
                     <option value="{{ $fish->id }}" @selected(($entry['fish_name'] ?? '') == $fish->id)>{{ $fish->name }}
                     </option>
                  @endforeach --}}
               </div>
               {{-- 釣果（匹） --}}
               <div class="flex items-center gap-2">
                  <div class="p-2 md:w-24 w-20 border border-gray-500 text-right rounded">

                  </div>
                  <span class="text-gray-600">匹</span>
               </div>
               {{-- サイズ（cm） --}}
               <div class="flex items-center gap-2">
                  <div class="p-2 md:w-24 w-20 border border-gray-500 text-right rounded">

                  </div>
                  <span class="text-gray-600">cm</span>
               </div>
            </div>
         </div>
      </div>
   </div>
