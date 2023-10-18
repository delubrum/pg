<div class="bg-white rounded-lg shadow-md p-6 mx-10 mt-10">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-semibold">Filtros</h3>
    </div>
    
    <form id="filter_form" method="post" autocomplete="off" enctype="multipart/form-data" action="?c=RM&a=Index">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="col-span-1 sm:col-span-1">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium">RM:</label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="number" step="1" min="1" class="form-input py-2 px-4 block w-full sm:text-sm sm:leading-5" value="<?php echo !empty($_POST) ? $_POST['id'] : '' ?>" name="id">
                    </div>
                </div>
            </div>
            <div class="col-span-1 sm:col-span-1">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium">Estado:</label>
                    <div class="relative rounded-md shadow-sm">
                        <select class="form-select py-2 px-4 block w-full sm:text-sm sm:leading-5" name="status">
                            <option></option>
                            <option <?php echo (!empty($_REQUEST['status']) and 'Terminar R.M.' == $_REQUEST['status']) ? 'selected' : ''; ?> value="Terminar R.M.">Terminar R.M.</option>
                            <option <?php echo (!empty($_REQUEST['status']) and 'Producción' == $_REQUEST['status']) ? 'selected' : ''; ?> value="Producción">Producción</option>
                            <option <?php echo (!empty($_REQUEST['status']) and 'Iniciado' == $_REQUEST['status']) ? 'selected' : ''; ?> value="Iniciado">Iniciado</option>
                            <option <?php echo (!empty($_REQUEST['status']) and 'Facturación' == $_REQUEST['status']) ? 'Facturación' : ''; ?> value="Facturación">Facturación</option>
                            <option <?php echo (!empty($_REQUEST['status']) and 'Cerrado' == $_REQUEST['status']) ? 'selected' : ''; ?> value="Cerrado">Cerrado</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-span-1 sm:col-span-1">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium">Desde:</label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="date" onfocus='this.showPicker()' class="form-input py-2 px-4 block w-full sm:text-sm sm:leading-5" value="<?php echo !empty($_POST) ? $_POST['from'] : '' ?>" name="from">
                    </div>
                </div>
            </div>
            <div class="col-span-1 sm:col-span-1">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium">Hasta:</label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="date" onfocus='this.showPicker()' class="form-input py-2 px-4 block w-full sm:text-sm sm:leading-5" value="<?php echo !empty($_POST) ? $_POST['to'] : '' ?>" name="to">
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded float-right">
            <i class="fas fa-search"></i> Buscar
        </button>
    </form>
</div>