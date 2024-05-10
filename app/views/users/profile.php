<div @click.outside="showModal = false" class="w-[95%] sm:w-[90%] bg-white p-4 rounded-lg shadow-lg relative z-20">
		<button @click="showModal = !showModal" class="absolute top-0 right-0 m-3 text-gray-600 hover:text-gray-800">
        <i class="ri-close-line text-2xl"></i>
    </button>
    <h1 class="text-lg font-semibold mb-4 text-teal-700"><i class="ri-user-3-line text-3xl"></i> Perfíl de Usuario</h1>
	<div class="overflow-y-auto max-h-[600px] p-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Columna 1 - Update Data -->
        <div class="bg-white rounded-lg p-4">
            <h2 class="text-lg font-semibold mb-4 text-teal-700">Datos Generales</h2>
            <form method="POST" class="w-[95%] sm:w-[100%]">
            <input type="hidden" value="<?php echo $id->id ?>" id="id" name="id">
                <?php if($id->type == 'Cliente') { ?>
                <div class="mt-2">
									<label for="company" class="block text-gray-600 text-sm mb-1">Empresa</label>
									<input value="<?php echo $id->company ?>" type="company" id="company" name="company"  
                    hx-post='?c=Users&a=Update' 
                    hx-trigger="change"
                    hx-indicator="#loading"
                    class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
                </div>
                <?php } ?>
                <div>
									<label for="username" class="block text-gray-600 text-sm mb-1">Nombre</label>
									<input value="<?php echo $id->username ?>" type="text" id="username" name="username"   
                    hx-post='?c=Users&a=Update' 
                    hx-trigger="change"
                    hx-indicator="#loading"
                    class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
                </div>
                <div class="mt-2">
									<label for="email" class="block text-gray-600 text-sm mb-1">Email</label>
									<input value="<?php echo $id->email ?>" type="email" id="email" name="email"   
                    hx-post='?c=Users&a=Update' 
                    hx-trigger="change"
                    hx-indicator="#loading"
                    class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
                </div>
                <?php if($id->type == 'Cliente') { ?>
                <div class="mt-2">
									<label for="phone" class="block text-gray-600 text-sm mb-1">Tel</label>
									<input value="<?php echo $id->phone ?>" type="text" id="phone" name="phone"   
                    hx-post='?c=Users&a=Update' 
                    hx-trigger="change"
                    hx-indicator="#loading"
                    class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
                </div>
                <div class="mt-2">
									<label for="city" class="block text-gray-600 text-sm mb-1">Ciudad</label>
									<input value="<?php echo $id->city ?>" type="text" id="city" name="city"   
                    hx-post='?c=Users&a=Update' 
                    hx-trigger="change"
                    hx-indicator="#loading"
                    class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
                </div>
                <div class="mt-2">
									<label for="price" class="block text-gray-600 text-sm mb-1">Turbo Exclusivo</label>
									<input value="<?php echo $id->price ?>" type="number" id="price" name="price"   
                    hx-post='?c=Users&a=Update' 
                    hx-trigger="change"
                    hx-indicator="#loading"
                    class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
                </div>
                <div class="mt-2">
									<label for="price2" class="block text-gray-600 text-sm mb-1">Turbo Recorrido</label>
									<input value="<?php echo $id->price2 ?>" type="number" id="price2" name="price2"   
                    hx-post='?c=Users&a=Update' 
                    hx-trigger="change"
                    hx-indicator="#loading"
                    class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
                </div>
                <div class="mt-2">
									<label for="price3" class="block text-gray-600 text-sm mb-1">Camioneta Exclusivo</label>
									<input value="<?php echo $id->price3 ?>" type="number" id="price3" name="price3"   
                    hx-post='?c=Users&a=Update' 
                    hx-trigger="change"
                    hx-indicator="#loading"
                    class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
                </div>
                <div class="mt-2">
									<label for="price4" class="block text-gray-600 text-sm mb-1">Camioneta Recorrido</label>
									<input value="<?php echo $id->price4 ?>" type="number" id="price4" name="price4"   
                    hx-post='?c=Users&a=Update' 
                    hx-trigger="change"
                    hx-indicator="#loading"
                    class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
                </div>
                <div class="mt-2">
									<label for="price5" class="block text-gray-600 text-sm mb-1">Otros Transporte</label>
									<input value="<?php echo $id->price4 ?>" type="number" id="price5" name="price5"   
                    hx-post='?c=Users&a=Update' 
                    hx-trigger="change"
                    hx-indicator="#loading"
                    class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
                </div>
                <?php } ?>

            </form>
        </div>

        <!-- Columna 2 - Update Password -->
        <div class="bg-white rounded-lg p-4">
            <h2 class="text-lg font-semibold mb-4 text-teal-700">Actualizar Password</h2>
            <form hx-post='?c=Users&a=UpdatePassword' 
              hx-indicator="#loading"
              class="w-[95%] sm:w-[100%]">
              <input type="hidden" value="<?php echo $id->id ?>" id="id" name="id">
							<div>
								<label for="newpass" class="block text-gray-600 text-sm mb-1">Password</label>
								<input type="password" id="newpass" name="newpass" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
							</div>
							<div class="mt-2">
								<label for="cpass" class="block text-gray-600 text-sm mb-1">Confirmar Password</label>
								<input type="password" id="cpass" name="cpass" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
							</div>
							<div class="mt-6 flex justify-end">
									<button type="submit" class="bg-teal-900 text-white py-2 px-4 rounded-md hover:bg-teal-700 transition"><i class="ri-save-line"></i> Actualizar Password</button>
							</div>
            </form>
        </div>
		</div>
    <?php require_once "permissions.php" ?>
    </div>
	</div>
</div>
