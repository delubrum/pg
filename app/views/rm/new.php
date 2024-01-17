<div @click.outside="showModal = false" class="w-[95%] sm:w-[90%] bg-white p-4 rounded-lg shadow-lg relative z-50">
    <!-- Close Button (X) in Top-Right Corner -->
    <button id="closeModal" @click="showModal = !showModal" class="absolute top-0 right-0 m-3 text-teal-700 hover:text-teal-900">
        <i class="ri-close-line text-2xl"></i>
    </button>
    <h1 class="mb-4 text-teal-700"><i class="ri-file-add-line text-3xl"></i> <span class="text-2xl font-semibold">Nuevo RM</span></h1>
    <form  id="newForm" 
        class="overflow-y-auto max-h-[400px] p-4"
        hx-post='?c=RM&a=Save' 
        hx-swap="none" 
        hx-vals='js:{table: JSON.stringify(table.getData())}'
        hx-indicator="#loading"
    >
      <?php echo isset($id) ? "<input type='hidden' name='id' value='$id->id'>" : '' ?>
      <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
        <div>
          <label for="client" class="block text-gray-600 text-sm mb-1">Cliente</label>
          <select id="client" name="clientId" 
            class="w-full bg-white p-[9px] w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none"
            hx-get="?c=RM&a=ClientProducts"
            hx-target="#product"
            hx-swap="innerHtml"
            hx-indicator="#loading"
            required
          >
            <option value='' disabled selected></option>
            <?php foreach ($this->model->list("*","users"," and type = 'Cliente' and status = 1") as $r) { ?>     
                <option value='<?php echo $r->id?>'><?php echo $r->company?></option>
            <?php } ?>
          </select>
        </div>
        <div>
          <label for="product" class="block text-gray-600 text-sm mb-1">Producto</label>
          <select id="product" name="productId" class="bg-white p-[9px] w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
            <option value='' disabled selected>Seleccione el Cliente...</option>
          </select>
        </div>
        <div>
            <label for="date" class="block text-gray-600 text-sm mb-1">Fecha</label>
            <input type="date"  id="date" name="date" onfocus='this.showPicker()' min="<?php echo date('Y-m-d', strtotime('-2 days')); ?>" max="<?php echo date('Y-m-d'); ?>" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
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
        <div>
            <label for="qty" class="block text-gray-600 text-sm mb-1">Tambores Plásticos Recibidos</label>
            <input type="number" step="1" id="qty" name="qty" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
        </div>
      </div>
      <div class="pt-4 grid grid-cols-1 sm:grid-cols-1 gap-4">
        <div class="text-center text-sm" id="spreadsheet"></div>
      </div>

      <button type="submit" class="pt-6 float-right text-xl text-teal-900 font-bold hover:text-teal-700"><i class="ri-save-line"></i> Guardar</button>

    </form>
</div>

<script>

var SUMCOL = function(instance, columnId) {
    var total = 0;
    for (var j = 0; j < instance.options.data.length; j++) {
        if (Number(instance.records[j][columnId].innerHTML)) {
            total += Number(instance.records[j][columnId].innerHTML);
        }
    }
    return total.toFixed(2);
}

    table = jspreadsheet(document.getElementById('spreadsheet'), {
    minDimensions:[5,1],
    autoIncrement: false,
    allowInsertColumn: false, 
    allowDeleteColumn: false, 
    allowRenameColumn: false,
    columnDrag:true,
    allowExport: false,
    footers: [['=SUMCOL(TABLE(), 0)','=SUMCOL(TABLE(), 1)']],
    columns: [
      { 
        title:'PESO BRUTO',
        type:'numeric',
        width:120,
      },
      { 
        title:'PESO BRUTO \n CLIENTE',
        type:'numeric',
        width:120,
      },
      { 
        type:'hidden',
      },
      { 
        type:'hidden',
      },
      { 
        type:'hidden',
      },
      {
        type:'hidden',
      },
      {
        type: 'dropdown',
        title:'ESTADO DEL \n TAMBOR',
        width:120,
        source:[
          "Bueno",
          "Malo",
        ],
        validate: 'required',
      },
      { 
        title:'DERRAMES \n VEHÍCULO',
        type: 'checkbox',
        width:120,
      },
      { 
        title:'DERRAMES \n CANECA',
        type: 'checkbox',
        width:120,
      },
      { 
        type:'hidden',
      },
    ],
    text:{
        insertANewRowBefore:'Insertar fila antes',
        insertANewRowAfter:'Insertar fila despues',
        deleteSelectedRows:'Borrar filas',
        copy:'Copiar',
        paste:'Pegar',
        about: '',
        areYouSureToDeleteTheSelectedRows:'Desea borrar las filas seleccionadas?',
    }
});
</script>