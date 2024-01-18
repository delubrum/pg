<div @click.outside="showModal = false" class="w-[95%] sm:w-[25%] bg-white p-4 rounded-lg shadow-lg relative z-50">
    <!-- Close Button (X) in Top-Right Corner -->
    <button id="closeModal" @click="showModal = !showModal" class="absolute top-0 right-0 m-3 text-teal-900 hover:text-teal-700">
        <i class="ri-close-line text-2xl"></i>
    </button>
    <h1 class="text-lg font-semibold mb-4 text-teal-700"><i class="ri-contrast-drop-2-line text-3xl"></i> <?php echo (isset($id)) ? 'Editar' : 'Nuevo'; ?> Producto</h1>
    <form  id="newForm" 
        class="overflow-y-auto max-h-[600px] p-4"
        hx-post='?c=Products&a=Save' 
        hx-swap="none" 
        hx-indicator="#loading"
    >
      <?php echo isset($id) ? "<input type='hidden' name='id' value='$id->id'>" : '' ?>
      <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
        <div>
            <label for="name" class="block text-gray-600 text-sm mb-1">Nombre</label>
            <input type="text" id="name" name="name" value="<?php echo isset($id) ? $id->name : '' ?>" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
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