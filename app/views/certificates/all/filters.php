
<div class="overflow-y-auto max-h-[600px] p-6">
  <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
    <div>
      <label for="clientFilter" class="block text-gray-600 text-sm mb-1">Cliente</label>
      <input type="text" id="clientFilter" name="clientFilter"
        class="filter w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" 
        hx-get="<?php echo $this->url ?>"
        hx-target="#list"
        hx-trigger="keyup changed delay:200ms"
        hx-indicator="#loading"
        hx-include=".filter"
      >
    </div>

  </div>
</div>