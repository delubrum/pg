<div class="w-[95%] sm:w-[25%] bg-white p-4 rounded-lg shadow-lg relative z-50">

<button id="closeNested" @click="nestedModal = !nestedModal" class="absolute top-0 right-0 m-3 text-teal-900 hover:text-teal-700">
    <i class="ri-close-line text-2xl"></i>
</button>

<h1 class="mb-4 text-teal-700"><i class="ri-file-add-line text-3xl"></i> <span class="text-2xl font-semibold">Nuevo Registro</span></h1>

<form
  class="overflow-y-auto max-h-[600px] p-4"
  hx-post='?c=BC&a=SaveItem' 
  hx-swap="none" 
  hx-indicator="#loading"
>
  <?php echo isset($id) ? "<input type='hidden' name='bcId' value='$id'>" : '' ?>
  <div x-data="{ selectedOption: '' }" class="grid grid-cols-1 sm:grid-cols-1 gap-4">
    <div>
      <label for="type" class="block text-gray-600 text-sm mb-1">Tipo</label>
      <select x-model="selectedOption" id="type" name="type" 
        class="w-full bg-white p-[9px] w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none"
        required
      >
        <option value='' disabled selected></option>
        <option value='Caldera'>Caldera</option>
        <option value='Ingreso'>Ingreso</option>
      </select>
    </div>
    <div x-show="selectedOption === 'Ingreso'">
      <label for="net" class="block text-gray-600 text-sm mb-1">Peso Neto</label>
      <input  type="number" step="0.01" min="0" id="net" name="net" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" x-bind:required="selectedOption === 'Ingreso'">
    </div>

    <div x-show="selectedOption === 'Ingreso'">
      <label for="drum" class="block text-gray-600 text-sm mb-1">Peso Tambor</label>
      <input  type="number" step="0.01" min="0" id="drum" name="drum" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" x-bind:required="selectedOption === 'Ingreso'">
    </div>

    <div>
      <label for="temp" class="block text-gray-600 text-sm mb-1">Temperatura</label>
      <input  type="number" step="0.01" min="0" id="temp" name="temp" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
    </div>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 pt-4">
    <div>
        <label for="notes" class="block text-gray-600 text-sm mb-1">Notas</label>
        <textarea name="notes" id="notes" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none"></textarea>
    </div>
  </div>

  <button type="submit" class="pt-6 float-right text-xl text-teal-900 font-bold hover:text-teal-700"><i class="ri-save-line"></i> Agregar</button>

</form>