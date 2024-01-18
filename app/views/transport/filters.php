
<div class="overflow-y-auto max-h-[600px] p-6">
  <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
    <div>
      <label for="from" class="block text-gray-600 text-sm mb-1">Desde</label>
      <input type="date" onfocus='this.showPicker()' id="from" name="from"
        class="filter w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" 
        hx-get="<?php echo $this->url ?>"
        hx-target="#list"
        hx-indicator="#loading"
        hx-include=".filter"
      >
    </div>
    <div>
      <label for="to" class="block text-gray-600 text-sm mb-1">Hasta</label>
      <input type="date" onfocus='this.showPicker()' id="to" name="to"
        class="filter w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" 
        hx-get="<?php echo $this->url ?>"
        hx-target="#list"
        hx-indicator="#loading"
        hx-include=".filter"
      >
    </div>

    <!-- <div>
      <label for="name" class="block text-gray-600 text-sm mb-1">Nombre</label>
      <input type="text" id="name" name="name"
        class="filter w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" 
        hx-get="<?php echo $this->url ?>"
        hx-target="#list"
        hx-trigger="keyup changed delay:200ms"
        hx-indicator="#loading"
        hx-include=".filter"
      >
    </div> -->

  </div>
</div>