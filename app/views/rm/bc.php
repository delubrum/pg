<div class="w-[95%] sm:w-[90%] bg-white p-4 rounded-lg shadow-lg relative z-50">
  <!-- Close Button (X) in Top-Right Corner -->
  <button id="closeModal" @click="showModal = !showModal" class="absolute top-0 right-0 m-3 text-teal-900 hover:text-teal-700">
      <i class="ri-close-line text-2xl"></i>
  </button>
  <h1 class="mb-4 text-teal-700"><i class="ri-hammer-line text-3xl"></i> <span class="text-2xl font-semibold">Producción</span></h1>
  <form id="newForm" 
      class="overflow-y-auto max-h-[600px] p-4"
      hx-post='?c=BC&a=Save' 
      hx-swap="none" 
      hx-trigger='submit'
      hx-indicator="#loading"
  >
  <?php echo isset($id) ? "<input type='hidden' name='id' value='$id->rmId'>" : '' ?>
    <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 text-sm">
      <div>
          <b>LOTE:</b> <?php echo $id->id ?>
      </div>
      <div>
          <b>RM:</b> <?php echo $id->rmId ?>
      </div>
      <div>
          <b>CLIENTE:</b> <?php echo $id->clientname ?>
      </div>
      <div>
            <b>REMISIÓN:</b> <?php echo $id->remission ?>
        </div>
        <div>
            <b>PRODUCTO:</b> <?php echo $id->productname ?>
        </div>
      <div>
        <b>REACTOR:</b> <?php echo $id->reactor ?>
      </div>
    </div>

    <div 
    class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm py-4"
    hx-get='?c=BC&a=Results&id=<?php echo $id->rmId?>'
    hx-trigger="load, listItemsChanged from:body"
    hx-swap="innerHtml"
    hx-indicator="#loading"
    ></div>


    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
      <div>
        <label for="type" class="block text-gray-600 text-sm mb-1">Tipo</label>
        <select id="type" name="type" 
          hx-post='?c=BC&a=Update' 
          hx-trigger="change"
          hx-indicator="#loading"
          class="w-full bg-white p-[9px] w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none"
          required
        >
          <option value='' disabled selected></option>
          <option <?php echo (isset($id) and $id->type == 'Servicio') ? 'selected' : ''; ?>>Servicio</option>
          <option <?php echo (isset($id) and $id->type == 'Producción') ? 'selected' : ''; ?>>Producción</option>
          <option <?php echo (isset($id) and $id->type == 'Reproceso') ? 'selected' : ''; ?>>Reproceso</option>
        </select>
      </div>

      <!-- <div>
        <label for="mud_dist" class="block text-gray-600 text-sm mb-1">Lodos de Destilación</label>
        <input  type="number" id="mud_dist" name="mud_dist" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div>

      <div>
        <label for="mud" class="block text-gray-600 text-sm mb-1">Lodos del Proceso</label>
        <input  type="number" id="mud" name="mud" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div>

      <div>
        <label for="distilled" class="block text-gray-600 text-sm mb-1">Destilado Humedo</label>
        <input  type="number" id="distilled" name="distilled" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div>

      <div>
        <label for="evaporation" class="block text-gray-600 text-sm mb-1">Perdida Evaporación</label>
        <input  type="number" id="evaporation" name="evaporation" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div> -->

      <div>
        <label for="water0" class="block text-gray-600 text-sm mb-1">Agua Inicial</label>
        <input  type="number" id="water0" name="water0"  
          hx-post='?c=BC&a=Update' 
          hx-trigger="change"
          hx-indicator="#loading"
          value="<?php echo isset($id) ? $id->water0 : '' ?>"
          class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div>

      <div>
        <label for="water1" class="block text-gray-600 text-sm mb-1">Agua Final</label>
        <input  type="number" id="water1" name="water1"  
          hx-post='?c=BC&a=Update' 
          hx-trigger="change"
          hx-indicator="#loading"
          value="<?php echo isset($id) ? $id->water1 : '' ?>"
          class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div>

      <div>
        <label for="gas0" class="block text-gray-600 text-sm mb-1">Gas Inicial</label>
        <input  type="number" id="gas0" name="gas0"  
          hx-post='?c=BC&a=Update' 
          hx-trigger="change"
          hx-indicator="#loading"
          value="<?php echo isset($id) ? $id->gas0 : '' ?>"
          class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div>

      <div>
        <label for="gas1" class="block text-gray-600 text-sm mb-1">Gas Final</label>
        <input  type="number" id="gas1" name="gas1"  
          hx-post='?c=BC&a=Update' 
          hx-trigger="change"
          hx-indicator="#loading"
          value="<?php echo isset($id) ? $id->gas1 : '' ?>"
          class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div>

      <div>
        <label for="energy0" class="block text-gray-600 text-sm mb-1">Energía Inicial</label>
        <input  type="number" id="energy0" name="energy0"  
          hx-post='?c=BC&a=Update' 
          hx-trigger="change"
          hx-indicator="#loading"
          value="<?php echo isset($id) ? $id->energy0 : '' ?>"
          class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div>

      <div>
        <label for="energy1" class="block text-gray-600 text-sm mb-1">Energía Final</label>
        <input  type="number" id="energy1" name="energy1"  
          hx-post='?c=BC&a=Update' 
          hx-trigger="change"
          hx-indicator="#loading"
          value="<?php echo isset($id) ? $id->energy1 : '' ?>"
          class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div>

    </div>
    <button
      class="pt-6 float-right text-xl text-teal-900 font-bold hover:text-teal-700"
      hx-get='?c=BC&a=NewItem&id=<?php echo $id->id?>'
      hx-target="#nestedModal"
      hx-swap="innerHtml"
      @click='nestedModal = true'
      hx-indicator="#loading"
    >
      <i class="ri-file-add-line"></i> Agregar
    </button>
    <div class="flex text-sm pt-4 w-full gap-2">
      <table class="w-2/3 table-excel"
      hx-trigger="load, listItemsChanged from:body"
      hx-get="?c=BC&a=DataItems&id=<?php echo $id->id?>"
      hx-swap="innerHtml"
      hx-indicator="#loading"
      class="mt-4 min-w-full text-sm"
      ></table>    
      <table class="w-1/3 table-excel"
      hx-trigger="load, listItemsChanged from:body"
      hx-get="?c=BC&a=DataItemsB&id=<?php echo $id->id?>"
      hx-swap="innerHtml"
      hx-indicator="#loading"
      class="mt-4 min-w-full text-sm"
      ></table> 
    </div>

    <button type="submit" class="pt-6 float-right text-xl text-teal-900 font-bold hover:text-teal-700"><i class="ri-save-line"></i> Guardar</button>

  </form>
</div>