<div class="w-[95%] sm:w-[90%] bg-white p-4 rounded-lg shadow-lg relative z-50">
  <!-- Close Button (X) in Top-Right Corner -->
  <button id="closeModal" @click="showModal = !showModal" class="absolute top-0 right-0 m-3 text-teal-900 hover:text-teal-700">
      <i class="ri-close-line text-2xl"></i>
  </button>
  <h1 class="text-lg font-semibold mb-4 text-teal-700"><i class="ri-file-add-line text-3xl"></i> Informe Proceso y Análisis</h1>
  <form x-data="{ recover: <?php echo $qty ?>, recoverbit: <?php echo $qtybit ?>, mpc: 0, lp: 0, dh: 0, pe: 0 }"
      class="overflow-y-auto max-h-[600px] p-4"
      hx-post='?c=IP&a=Save' 
      hx-swap="none" 
      hx-trigger='submit'
      hx-indicator="#loading"
  >
  <?php echo isset($id) ? "<input type='hidden' name='id' value='$id->id'>" : '' ?>
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
        <b>PRODUCTO: </b> <?php echo $id->productname ?>
      </div>
      <div>
        <b>REACTOR:</b> <?php echo $id->reactor ?>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 py-4">
    <div class="text-center">
          <b>RECUPERADO BITACORA:</b> <br>
          <span class='text-2xl text-teal-700 font-bold' x-text="recoverbit"></span>
      </div>

      <div class="text-center">
          <b>PESO MP A RECUPERAR:</b> <br>
          <span class='text-2xl text-teal-700 font-bold' x-text="recover"></span>
      </div>
      <div class="text-center">
          <b>CALCULO CERO:</b><br>
          <span class='text-2xl text-teal-700 font-bold' x-text="(recover - mpc - lp - dh - pe).toFixed(2);"></span>
          <input type="hidden" name="cero" x-model="(recover - mpc - lp - dh - pe).toFixed(2);">
      </div>
      <div class="text-center">
          <b>% RECUPERACIÓN CLIENTE:</b><br>
          <span class='text-2xl text-teal-700 font-bold' x-text="((mpc / recover) * 100).toFixed();"></span>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
      <div>
        <label for="mpClient" class="block text-gray-600 text-sm mb-1">Peso Recuperado Cliente</label>
        <input  type="number" step="0.01" x-model="mpc" id="mpClient" name="mpClient" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div>
      <div>
        <label for="mudpClient" class="block text-gray-600 text-sm mb-1">Lodos del Proceso Cliente</label>
        <input  type="number" step="0.01" x-model="lp" id="mudpClient" name="mudpClient" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div>
      <div>
        <label for="distilledClient" class="block text-gray-600 text-sm mb-1">Destilado Humedo Cliente</label>
        <input  type="number" step="0.01" x-model="dh" id="distilledClient" name="distilledClient" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div>
      <div>
        <label for="evaporationClient" class="block text-gray-600 text-sm mb-1">Perdida Evaporación Cliente</label>
        <input  type="number" step="0.01" x-model="pe" id="evaporationClient" name="evaporationClient" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div>
      <div>
        <label for="densidad" class="block text-gray-600 text-sm mb-1">Densidad (g/ml - 0.800 - 0.900)</label>
        <input id="densidad" name="densidad" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div>
      <div>
        <label for="humedad" class="block text-gray-600 text-sm mb-1">% Humedad (4.90 - 9.50)</label>
        <input id="humedad" name="humedad" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div>
      <div>
        <label for="ph" class="block text-gray-600 text-sm mb-1">% PH (5)</label>
        <input id="ph" name="ph" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div>
    </div>
    <div class="mt-6 flex justify-end">
      <button type="submit" 
      class="text-xl float-left text-teal-900 px-4 py-2 font-bold hover:text-teal-700"
      >
        <i class="ri-save-line"></i> Guardar
      </button>
    </div>
  </form>
</div>