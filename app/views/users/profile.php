<div @click.outside="showModal = false" class="w-[95%] sm:w-[90%] bg-white p-4 rounded-lg shadow-lg relative z-20">
		<button @click="showModal = !showModal" class="absolute top-0 right-0 m-3 text-gray-600 hover:text-gray-800">
        <i class="ri-close-line text-2xl"></i>
    </button>
    <h1 class="text-lg font-semibold mb-4"><i class="ri-user-3-line text-3xl"></i> User Profile</h1>
	<div class="overflow-y-auto max-h-[600px] p-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Columna 1 - Update Data -->
        <div class="bg-white rounded-lg p-4">
            <h2 class="text-lg font-semibold mb-4">Data</h2>
            <form action="tu_script_de_actualización.php" method="POST" class="w-[95%] sm:w-[100%]">
                <div>
									<label for="name" class="block text-gray-600 text-sm mb-1">Full Name</label>
									<input value="<?php echo $id->username ?>" type="text" id="name" name="name" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none" required>
                </div>
                <div class="mt-2">
									<label for="email" class="block text-gray-600 text-sm mb-1">Email Address</label>
									<input value="<?php echo $id->email ?>" type="email" id="email" name="email" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none" required>
                </div>
            </form>
        </div>

        <!-- Columna 2 - Update Password -->
        <div class="bg-white rounded-lg p-4">
            <h2 class="text-lg font-semibold mb-4">Update Password</h2>
            <form action="tu_script_de_actualización_de_contraseña.php" method="POST" class="w-[95%] sm:w-[100%]">
							<div>
								<label for="newpass" class="block text-gray-600 text-sm mb-1">Password</label>
								<input type="password" id="newpass" name="newpass" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none" required>
							</div>
							<div class="mt-2">
								<label for="cpass" class="block text-gray-600 text-sm mb-1">Confirm Password</label>
								<input type="password" id="cpass" name="cpass" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none" required>
							</div>
							<div class="mt-6 flex justify-end">
									<button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition"><i class="ri-save-line"></i> Update Password</button>
							</div>
            </form>
        </div>
		</div>
    <?php require_once "permissions.php" ?>
    </div>
	</div>
</div>
