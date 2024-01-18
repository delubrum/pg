
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

    <div>
      <label for="nameFilter" class="block text-gray-600 text-sm mb-1">Nombre</label>
      <input type="text" id="nameFilter" name="nameFilter"
        class="filter w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" 
        hx-get="<?php echo $this->url ?>"
        hx-target="#list"
        hx-trigger="keyup changed delay:200ms"
        hx-indicator="#loading"
        hx-include=".filter"
      >
    </div>

    <div>
      <label for="emailFilter" class="block text-gray-600 text-sm mb-1">Email</label>
      <input type="text" id="emailFilter" name="emailFilter"
        class="filter w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" 
        hx-get="<?php echo $this->url ?>"
        hx-target="#list"
        hx-trigger="keyup changed delay:200ms"
        hx-indicator="#loading"
        hx-include=".filter"
      >
    </div>

    <div class="max-w-md">
        <span class="text-gray-600 text-sm">Status</span>
        <div 
          class="space-y-2 mt-1"
          hx-trigger="change from:body .form-checkbox"
          hx-get="<?php echo $this->url ?>" 
          hx-target="#list"
          hx-indicator="#loading"
          hx-include=".filter"
        >
          <div class="flex items-center">
            <input id="Activo" type="checkbox" name="statusFilter[]" value="1" checked class="filter form-checkbox h-5 w-5 checked:bg-teal-700 cursor-pointer">
            <label for="Activo" class="ml-2 cursor-pointer">Activo</label>
          </div>
          <div class="flex items-center">
            <input id="Inactivo" type="checkbox" name="statusFilter[]" value="0" checked class="filter form-checkbox h-5 w-5 checked:bg-teal-700 cursor-pointer">
            <label for="Inactivo" class="ml-2 cursor-pointer">Inactivo</label>
          </div>
      </div>
    </div>
  </div>
</div>