<div @click.outside="showModal = false" class="w-[95%] sm:w-[95%] bg-white p-4 rounded-lg shadow-lg relative z-50">
    <!-- Close Button (X) in Top-Right Corner -->
    <button id="closeModal" @click="showModal = !showModal" class="absolute top-0 right-0 m-3 text-teal-700 hover:text-teal-900">
        <i class="ri-close-line text-2xl"></i>
    </button>
    <h1 class="mb-4 text-teal-700"><i class="ri-file-add-line text-3xl"></i> <span class="text-2xl font-semibold">Nuevo RM (<?php echo isset($this->model->get('id','rm', ' ORDER BY id DESC LIMIT 1')->id) ?  $this->model->get('id','rm', ' ORDER BY id DESC LIMIT 1')->id + 1 : 1 ; ?>)</span></h1>
    <form  id="newForm" 
        class="overflow-y-auto max-h-[400px] p-2"
        hx-post='?c=MR&a=Save' rm_items
        hx-swap="none" 
        hx-vals='js:{drums: JSON.stringify(drums.getData()),returned: JSON.stringify(returned.getData())}'
        hx-indicator="#loading"
    >
      <?php echo isset($id) ? "<input type='hidden' name='id' value='$id->id'>" : '' ?>
      <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
        <div>
            <label for="date" class="block text-gray-600 text-sm mb-1"><br>Fecha</label>
            <input type="date"  id="date" name="date" onfocus='this.showPicker()' class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
        </div>
        <div>
          <label for="operatorId" class="block text-gray-600 text-sm mb-1"><br>Responsable</label>
          <select id="operatorId" name="operatorId" class="w-full bg-white p-[9px] w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
            <option value='' disabled selected></option>
            <?php foreach ($this->model->list("*","users"," and type = 'Operario' and status = 1") as $r) { ?>     
              <option value='<?php echo $r->id?>'><?php echo $r->username?></option>
            <?php } ?>
          </select>
        </div>

        <div>
          <label for="price" class="block text-gray-600 text-sm mb-1"><br>Valor de Transporte</label>
          <select id="operatorId" name="operatorId" class="w-full bg-white p-[9px] w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
            <option value='' disabled selected></option>
            <option value='1'>Turbo Exclusivo</option>
            <option value='2'>Turbo Recorrido</option>
            <option value='3'>Camioneta Exclusivo</option>
            <option value='4'>Camioneta Recorrido</option>
          </select>
        </div>

      </div>

      <div class="pt-4 grid grid-cols-1 sm:grid-cols-1 gap-4">
        <div class="text-center text-sm" id="spreadsheet"></div>
      </div>


      <div class="pt-4 grid grid-cols-1 sm:grid-cols-1 gap-4">
        <div class="text-center text-sm" id="returned"></div>
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


returned = jspreadsheet(document.getElementById('returned'), {
  minDimensions:[2,1],
  autoIncrement: false,
  allowInsertColumn: false, 
  allowDeleteColumn: false, 
  allowRenameColumn: false,
  columnDrag:true,
  allowExport: false,
  oninsertrow:function(instance, cell, col, row) {
    returned.getData
    var data = returned.getData();
    var lastRow = data.length;
    var cell = returned.getCell('A'+lastRow);
    returned.updateSelection(cell);
  },
  columns: [
    {type:'dropdown', width: 300, title:'CLIENTE', url:'?c=RM&a=Clients', autocomplete:true, validate: 'required'},
    {title:'TAMBORES PLASTICOS DEVUELTOS',type:'text',width:300,},
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

drums = jspreadsheet(document.getElementById('spreadsheet'), {
  minDimensions:[5,1],
  autoIncrement: false,
  allowInsertColumn: false, 
  allowDeleteColumn: false, 
  allowRenameColumn: false,
  columnDrag:true,
  allowExport: false,
  oninsertrow:function(instance, cell, col, row) {
    drums.getData
    var data = drums.getData();
    var lastRow = data.length;
    var cell = drums.getCell('A'+lastRow);
    drums.updateSelection(cell);
  },
  footers: [['','=SUMCOL(TABLE(), 1)','=SUMCOL(TABLE(), 2)']],
  columns: [
    {type:'dropdown', width:'200', title:'CLIENTE', url:'?c=MR&a=Clients', autocomplete:true, validate: 'required'},
    {title:'REMISIÓN',type:'text',width:120,},
    {type:'dropdown', width:'200', title:'PRODUCTO', url:'?c=MR&a=Products', autocomplete:true, validate: 'required'},
    {type: 'dropdown', title:'TIPO ENVASE', width:120, source:["Tambor","Cuñete",],validate: 'required',},
    {title:'PESO BRUTO \n ECOAMBIENTALES',type:'numeric',width:125,},
    {title:'PESO BRUTO \n CLIENTE',type:'numeric',width:120,},
    {type:'hidden',},
    {type:'hidden',},
    {type:'hidden',},
    {type:'hidden',},
    {type: 'dropdown',title:'ESTADO DEL \n TAMBOR',width:100,source:["Bueno","Malo",],validate: 'required',},
    {title:'DEVOLVER',type: 'checkbox',width:100,},
    {title:'DERRAMES \n VEHÍCULO',type: 'checkbox',width:100,},
    {title:'DERRAMES \n CANECA',type: 'checkbox',width:100,},
    {type:'hidden',},
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