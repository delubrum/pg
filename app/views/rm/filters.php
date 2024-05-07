
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
      <label for="RMFilter" class="block text-gray-600 text-sm mb-1">RM</label>
      <input type="text" id="RMFilter" name="RMFilter"
        class="filter w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" 
        hx-get="<?php echo $this->url ?>"
        hx-target="#list"
        hx-trigger="keyup changed delay:200ms"
        hx-indicator="#loading"
        hx-include=".filter"
      >
    </div>

    <div>
      <label for="userFilter" class="block text-gray-600 text-sm mb-1">Creador</label>
      <input type="text" id="userFilter" name="userFilter"
        class="filter w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" 
        hx-get="<?php echo $this->url ?>"
        hx-target="#list"
        hx-trigger="keyup changed delay:200ms"
        hx-indicator="#loading"
        hx-include=".filter"
      >
    </div>

    <div>
      <label for="productFilter" class="block text-gray-600 text-sm mb-1">Producto</label>
      <input type="text" id="productFilter" name="productFilter"
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
          hx-trigger="change .form-checkbox"
          hx-get="<?php echo $this->url ?>" 
          hx-target="#list"
          hx-indicator="#loading"
          hx-include=".filter"
        >
          <div class="flex items-center">
            <input id="Terminar R.M." type="checkbox" name="statusFilter[]" value="Terminar R.M." checked class="filter form-checkbox h-5 w-5 checked:bg-teal-700 cursor-pointer">
            <label for="Terminar R.M." class="ml-2 cursor-pointer">Terminar R.M.</label>
          </div>
          <div class="flex items-center">
            <input id="Producción" type="checkbox" name="statusFilter[]" value="Producción" checked class="filter form-checkbox h-5 w-5 checked:bg-teal-700 cursor-pointer">
            <label for="Producción" class="ml-2 cursor-pointer">Producción</label>
          </div>
          <div class="flex items-center">
            <input id="Iniciado" type="checkbox" name="statusFilter[]" value="Iniciado" checked class="filter form-checkbox h-5 w-5 checked:bg-teal-700 cursor-pointer">
            <label for="Iniciado" class="ml-2 cursor-pointer">Iniciado</label>
          </div>
          <div class="flex items-center">
            <input id="Análisis" type="checkbox" name="statusFilter[]" value="Análisis" checked class="filter form-checkbox h-5 w-5 checked:bg-teal-700 cursor-pointer">
            <label for="Análisis" class="ml-2 cursor-pointer">Análisis</label>
          </div>
          <div class="flex items-center">
            <input id="Facturación" type="checkbox" name="statusFilter[]" value="Facturación" checked class="filter form-checkbox h-5 w-5 checked:bg-teal-700 cursor-pointer">
            <label for="Facturación" class="ml-2 cursor-pointer">Facturación</label>
          </div>
          <div class="flex items-center">
            <input id="Cerrado" type="checkbox" name="statusFilter[]" value="Cerrado" checked class="filter form-checkbox h-5 w-5 checked:bg-teal-700 cursor-pointer">
            <label for="Cerrado" class="ml-2 cursor-pointer">Cerrado</label>
          </div>
      </div>
    </div>
  </div>
</div>