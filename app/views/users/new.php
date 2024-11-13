<div @click.outside="showModal = false" class="w-[95%] sm:w-[50%] bg-white p-4 rounded-lg shadow-lg relative z-50">
    <!-- Close Button (X) in Top-Right Corner -->
    <button id="closeModal" @click="showModal = !showModal" class="absolute top-0 right-0 m-3 text-teal-900 hover:text-teal-700">
        <i class="ri-close-line text-2xl"></i>
    </button>
    <h1 class="text-lg font-semibold mb-4 text-teal-700"><i class="ri-user-3-line text-3xl"></i> Nuevo usuario</h1>
    <form  x-data="{ selectedOption: '' }" id="newForm" 
        class="overflow-y-auto max-h-[600px] p-4"
        hx-post='?c=Users&a=Save' 
        hx-swap="none" 
        hx-indicator="#loading"
    >
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label for="type" class="block text-gray-600 text-sm mb-1">Tipo</label>
            <!-- <select x-model="selectedOption" id="type" name="type" class="bg-white p-2 w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required> -->
            <select id="type" name="type" class="bg-white p-2 w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
              <option value="" disabled selected></option>
              <option value="Usuario">Usuario</option>
              <option value="Cliente">Cliente</option>
              <option value="Operario">Operario</option>
            </select>
          </div>
          <!-- <div x-show="selectedOption === 'Cliente'">
              <label for="company" class="block text-gray-600 text-sm mb-1">Empresa</label>
              <input id="company" name="company" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" :required="selectedOption === 'Cliente'">
          </div> -->
          <div>
              <label for="name" class="block text-gray-600 text-sm mb-1">Nombre</label>
              <input type="text" id="name" name="name" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
          </div>
          <div>
              <label for="email" class="block text-gray-600 text-sm mb-1">Email</label>
              <input type="email" id="email" name="email" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
          </div>
          <div>
              <label for="newpass" class="block text-gray-600 text-sm mb-1">Password</label>
              <input type="password" id="newpass" name="newpass" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required autocomplete="false">
          </div>
          <div>
              <label for="cpass" class="block text-gray-600 text-sm mb-1">Confirmar Password</label>
              <input type="password" id="cpass" name="cpass" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required autocomplete="false">
          </div>

        </div>

        <div class="mt-6 flex justify-end">
          <button type="submit" 
          class="text-xl float-left text-teal-900 px-4 py-2 font-bold hover:text-teal-700"
          >
            <i class="ri-save-line"></i> Registrar
          </button>
        </div>
    </form>
</div>