<div class="w-[95%] sm:w-[25%] bg-white p-4 rounded-lg shadow-lg relative z-50">
  <!-- Close Button (X) in Top-Right Corner -->
  <button id="closeModal" @click="showModal = !showModal" class="absolute top-0 right-0 m-3 text-teal-700 hover:text-teal-900">
      <i class="ri-close-line text-2xl"></i>
  </button>
  <h1 class="mb-4 text-teal-700"><i class="ri-exchange-dollar-line text-3xl"></i> <span class="text-2xl font-semibold">Facturación</span></h1>
  <form x-data="{ recover: <?php echo $qty ?>, mpc: 0, lp: 0, dh: 0, pe: 0 }"
      class="overflow-y-auto max-h-[600px] p-4"
      hx-post='?c=IP&a=IVSave' 
      hx-swap="none" 
      hx-trigger='submit'
      hx-indicator="#loading"
  >
  <?php echo isset($id) ? "<input type='hidden' name='id' value='$id'>" : '' ?>
    <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 text-sm">
      <div>
        <label for="invoice" class="block text-gray-600 text-sm mb-1">Nro Factura</label>
        <input type="text"  id="invoice" name="invoice" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div>
      <div>
        <label for="user" class="block text-gray-600 text-sm mb-1">Responsable</label>
        <select id="user" name="user" class="w-full bg-white p-[9px] w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
          <option value='' disabled selected></option>
          <?php foreach ($this->model->list("*","users"," and type = 'Operario' and status = 1") as $r) { ?>     
            <option value='<?php echo $r->id?>'><?php echo $r->username?></option>
          <?php } ?>
        </select>
      </div>

      <button type="submit" class="py-2 text-right text-xl text-teal-900 font-bold hover:text-teal-700"><i class="ri-save-line"></i> Guardar</button>
</form>

