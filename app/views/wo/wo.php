<div @click.outside="showModal = false" class="w-[95%] sm:w-[95%] bg-white p-4 rounded-lg shadow-lg relative z-50">
    <!-- Close Button (X) in Top-Right Corner -->
    <button id="closeModal" @click="showModal = !showModal" class="absolute top-0 right-0 m-3 text-teal-700 hover:text-teal-900">
        <i class="ri-close-line text-2xl"></i>
    </button>
    <h1 class="mb-4 text-teal-700"><i class="ri-file-edit-line text-3xl"></i> <span class="text-2xl font-semibold">Completar Lote</span></h1>
    <form id="newForm" 
        class="overflow-y-auto max-h-[400px] p-4"
        hx-post='?c=WO&a=Update&status=Completar' 
        hx-swap="none" 
        hx-vals='js:{wo: JSON.stringify(wo.getData())}'
        hx-trigger='submit'
        hx-indicator="#loading"
    >
    <?php echo isset($id) ? "<input type='hidden' name='id' value='$id'>" : '' ?>

      <div class="w-full text-center py-4" id="spreadsheet"></div>

      <?php // if ($id->productname != 'Lodos') { ?>

      <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div>
            <label for="datetime" class="block text-gray-600 text-sm mb-1">Fecha Y Hora Cargue</label>
            <!-- <input type="datetime-local" min="<?php echo date("Y-m-d H:i")?>" id="datetime" name="datetime" onfocus='this.showPicker()' class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required> -->
            <input type="datetime-local" id="datetime" name="datetime" onfocus='this.showPicker()' class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>

        </div>

        <div>
          <label for="operator" class="block text-gray-600 text-sm mb-1">Responsable</label>
          <select id="operator" name="operatorId" 
            class="w-full bg-white p-[9px] w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none"
            required
          >
            <option value='' disabled selected></option>
            <?php foreach ($this->model->list("*","users"," and type = 'Operario' and status = 1") as $r) { ?>     
              <option value='<?php echo $r->id?>'><?php echo $r->username?></option>
            <?php } ?>
          </select>
        </div>

        <div>
          <label for="reactor" class="block text-gray-600 text-sm mb-1">Reactor N°</label>
          <select id="reactor" name="reactor" 
            class="w-full bg-white p-[9px] w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none"
            required
          >
            <option value='' disabled selected></option>
            <option value='1'>1</option>
            <option value='2'>2</option>
            <option value='3'>3</option>
          </select>
        </div>

        <div>
            <label for="paste" class="block text-gray-600 text-sm mb-1">Pasta que no Entra</label>
            <input  type="number" step="0.01" id="paste" name="paste" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
        </div>

        <div>
            <label for="toreturn" class="block text-gray-600 text-sm mb-1">Devolver</label>
            <input  type="number" step="0.01"  id="toreturn" name="toreturn" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
        </div>

        <div>
            <label for="surplus" class="block text-gray-600 text-sm mb-1">Excedente</label>
            <input  type="number" step="0.01"  id="surplus" name="surplus" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
        </div>

        <div>
            <label for="surplus" class="block text-gray-600 text-sm mb-1">Total a Cargar</label>
            <input readonly id="total" class="bg-teal-700 text-white w-full p-1.5 border border-gray-300 rounded-md">
        </div>

      </div>

      <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 pt-4">
        <div>
            <label for="notes" class="block text-gray-600 text-sm mb-1">Notas</label>
            <textarea name="notes" id="notes" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none"></textarea>
        </div>
      </div>

      <?php // } ?>

      <button type="submit" class="pt-6 float-right text-xl text-teal-900 font-bold hover:text-teal-700"><i class="ri-save-line"></i> Guardar</button>


    </div>


  </form>
</div>
<script>

  SUMCOL = function(instance, columnId) {
    var total = 0;
    for (var j = 0; j < instance.options.data.length; j++) {
        if (Number(instance.records[j][columnId].innerHTML)) {
            total += Number(instance.records[j][columnId].innerHTML);
        }
    }
    return total.toFixed(2);
  }

  wo = jspreadsheet(document.getElementById('spreadsheet'), {
    data: <?php echo $data ?>,
    minDimensions:[10,1],
    autoIncrement: false,
    allowInsertColumn: false, // Allow ly inserting columns
    allowDeleteColumn: false, // Allow ly deleting columns
    allowRenameColumn: false, // Allow ly deleting columns
    columnDrag:true,
    allowExport: false,
    parseFormulas: true,
    allowInsertRow: false, // Allow ly inserting columns
    allowDeleteRow: false, // Allow ly inserting columns
    footers: [['','','','','=SUMCOL(TABLE(), 4)','=SUMCOL(TABLE(), 5)','=SUMCOL(TABLE(), 6)','=SUMCOL(TABLE(), 7)','=SUMCOL(TABLE(), 8)','=SUMCOL(TABLE(), 9)']],
    columns: [
    {type:'hidden'},
    {type:'dropdown', width:'200', title:'CLIENTE', url:'?c=MR&a=Clients', readOnly: true },
    {title:'REMISIÓN',type:'text',width:120, readOnly: true},
    {type:'dropdown', width:'200', title:'PRODUCTO', url:'?c=MR&a=Products', readOnly: true },
    {type: 'dropdown', title:'TIPO ENVASE', width:120, source:["Tambor","Cuñete",],readOnly: true},
    {title:'PESO BRUTO \n ECOAMBIENTALES',type:'numeric',width:125,readOnly: true},
    {title:'PESO BRUTO \n CLIENTE',type:'numeric',width:120,readOnly: true},
    {title:'PESO TARAS \n ECO', type:'numeric', width:110},
    {title:'PESO TARAS \n CLIENTE',type:'numeric',width:110},
    {title:'PESO NETO ECO',width:110,type:'numeric',readOnly: true},
    {title:'PESO NETO \n CLIENTE',type:'numeric',width:110,readOnly: true},
    {type: 'dropdown',title:'ESTADO DEL \n TAMBOR',width:100,source:["Bueno","Malo",],readOnly: true},
    {title:'DEVOLVER',type: 'checkbox',width:100,readOnly: true},
    {title:'DERRAMES \n VEHÍCULO',type: 'checkbox',width:100,readOnly: true},
    {title:'DERRAMES \n CANECA',type: 'checkbox',width:100,readOnly: true},
    {title:'DERRAMES \n PLANTA',type: 'checkbox',width:110},
  ],
    updateTable:function(instance, cell, col, row, val, label, cellName) {
        paste = document.getElementById("paste").value;
        net = SUMCOL(wo, 9);
        row = row+1;
        document.getElementById("total").value = (net-paste);
        if (col == 9) {
            cell.innerHTML = (wo.getValue('F'+row)-(wo.getValue('H'+row))).toFixed(2);
        }
        if (col == 10) {
            cell.innerHTML = (wo.getValue('G'+row)-(wo.getValue('I'+row))).toFixed(2);
        }
    },
    text:{
        copy:'Copiar',
        paste:'Pegar',
        about: '',
    }
  });

  document.getElementById("paste").addEventListener("keyup", function(e) {
    var paste = this.value;
    var net = SUMCOL(wo, 5);
    var total = Number(net) - Number(paste);
    console.log(paste,net,);
    document.getElementById("total").value = total;
  });