
<div class="overflow-y-auto max-h-[600px] p-6">
  <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
    <div>
      <label for="fromFilter" class="block text-gray-600 text-sm mb-1">Desde</label>
      <input type="date" onfocus='this.showPicker()' id="fromFilter" name="fromFilter"
        class="filter w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" 
        hx-get="<?php echo $this->url ?>"
        hx-target="#list"
        hx-indicator="#loading"
        hx-include=".filter"
      >
    </div>
    <div>
      <label for="toFilter" class="block text-gray-600 text-sm mb-1">Hasta</label>
      <input type="date" onfocus='this.showPicker()' id="toFilter" name="toFilter"
        class="filter w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" 
        hx-get="<?php echo $this->url ?>"
        hx-target="#list"
        hx-indicator="#loading"
        hx-include=".filter"
      >
    </div>

  </div>
</div>