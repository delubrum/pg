<div class="container mx-auto p-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <div class="text-3xl float-left text-teal-900 py-2 font-bold">
                <i class="ri-file"></i> Informe General
            </div>
            <div class="overflow-x-auto w-full py-4">
                <div> Total Plásticos Prestados: <span class="text-xl text-teal-900 px-2 font-bold"><?php echo $prestados = ceil($this->model->get('sum(mpClient) as total','rm')->total/170) ?></span> </div>
                <div> Total Plásticos Devueltos Por Clientes: <span class="text-xl text-teal-900 px-2 font-bold"><?php echo $devueltos = $this->model->get('sum(drumsReturned) as total','rm')->total ?></span> </div>
                <br>
                <div class="text-xl text-teal-900 font-bold"> Total Plásticos en Inventario: <span class="text-2xl px-4"><?php echo $prestados-$devueltos ?> </span> </div>
                <br>
                <div> Total Metálicos en Inventario: <span class="text-xl text-teal-900 px-4 py-2 font-bold"><?php echo $this->model->get('sum(drums) as total','rm',' and returnToClient = 0')->total ?></span> </div>
                <div> Total Cuñetes en Inventario: <span class="text-xl text-teal-900 px-4 py-2 font-bold"><?php echo $this->model->get('sum(barrels) as total','rm',' and returnToClient = 0')->total ?></span> </div>
            </div>
        </div>
        <div>
            <div class="text-3xl float-left text-teal-900 font-bold">
                <i class="ri-file"></i> Informe Cliente
            </div>
            <div class="overflow-x-auto w-full">
                <div>
                    <label for="client" class="block text-gray-600 text-sm mb-1 py-2">Cliente</label>
                    <select id="client" name="clientId" 
                        class="w-50 bg-white p-1.5 border border-gray-300 rounded-md "
                        hx-get="?c=Drums&a=Client"
                        hx-target="#clients"
                        hx-swap="innerHTML"
                        hx-indicator="#loading"
                    >
                        <option value='' disabled selected></option>
                        <?php foreach ($this->model->list("*","clients"," and status = 1 ORDER BY company ASC") as $r) { ?>     
                            <option value='<?php echo $r->id?>'><?php echo $r->company?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="py-4" id="clients">
                    <!-- Contenido de los clientes -->
                </div>
            </div>
        </div>
    </div>
</div>