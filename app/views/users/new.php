<div @click.outside="showModal = false" class="w-[95%] sm:w-[50%] bg-white p-4 rounded-lg shadow-lg relative z-50">
    <!-- Close Button (X) in Top-Right Corner -->
    <button @click="showModal = !showModal" class="absolute top-0 right-0 m-3 text-gray-600 hover:text-gray-800">
        <i class="ri-close-line text-2xl"></i>
    </button>
    <h1 class="text-lg font-semibold mb-4"><i class="ri-user-3-line text-3xl"></i> New</h1>
    <form id="newForm" 
        class="overflow-y-auto max-h-[600px] p-4"
        hx-post='?c=Users&a=Save' 
        hx-swap="none" 
        hx-on:htmx:after-request="toast(event.detail.xhr.response)" 
    >
        <div x-data="{ selectedOption: '' }" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="type" class="block text-gray-600 text-sm mb-1">Type</label>
                <select x-model="selectedOption" id="type" name="type" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none" required>
                    <option value="" disabled selected></option>
                    <option value="Admin">Admin</option>
                    <option value="User">User</option>
                </select>
            </div>
            <div>
                <label for="name" class="block text-gray-600 text-sm mb-1">Full Name</label>
                <input type="text" id="name" name="name" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none" required>
            </div>
            <div>
                <label for="email" class="block text-gray-600 text-sm mb-1">Email Address</label>
                <input type="email" id="email" name="email" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none" required>
            </div>
            <div>
                <label for="newpass" class="block text-gray-600 text-sm mb-1">Password</label>
                <input type="password" id="newpass" name="newpass" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none" required>
            </div>
            <div>
                <label for="cpass" class="block text-gray-600 text-sm mb-1">Confirm Password</label>
                <input type="password" id="cpass" name="cpass" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none" required>
            </div>
            <div x-show="selectedOption === 'User'">
                <label for="hour" class="block text-gray-600 text-sm mb-1">Hour</label>
                <input type="number" step="0.01" id="hour" name="hour" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none" x-bind:required="selectedOption === 'User'">
            </div>
            <div x-show="selectedOption === 'User'">
                <label for="overtime" class="block text-gray-600 text-sm mb-1">Overtime</label>
                <input type="number" step="0.01" id="overtime" name="overtime" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none" x-bind:required="selectedOption === 'User'">
            </div>
            <div x-show="selectedOption === 'User'">
                <label for="payroll" class="block text-gray-600 text-sm mb-1">Payroll</label>
                <select id="payroll" name="payroll" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none" style="background-color: white;" x-bind:required="selectedOption === 'User'">
                    <option value="" disabled selected></option>
                    <option value="ESM-Roldan">ESM-Roldan</option>
                    <option value="Componeti">Componeti</option>
                </select>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition"><i class="ri-save-line"></i> Register</button>
        </div>
    </form>
</div>