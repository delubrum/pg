<div @click.outside="showModal = false" class="w-[95%] sm:w-[50%] bg-white p-4 rounded-lg shadow-lg relative z-50">
    <!-- Close Button (X) in Top-Right Corner -->
    <button id="closeModal" @click="showModal = !showModal" class="absolute top-0 right-0 m-3 text-teal-900 hover:text-teal-700">
        <i class="ri-close-line text-2xl"></i>
    </button>
    <h1 class="mb-4 text-teal-700"><i class="ri-file-add-line text-3xl"></i> <span class="text-2xl font-semibold"> <?php echo (isset($id)) ? 'Editar' : 'Nuevo'; ?> Cliente <span></h1>
    <form  id="newForm" 
        class="overflow-y-auto max-h-[600px] p-4"
        hx-post='?c=Clients&a=Save' 
        hx-swap="none"
        hx-vals='js:{data: JSON.stringify(jspreadsheet.getData())}'
        hx-indicator="#loading"
    >
      <?php echo isset($id) ? "<input type='hidden' name='id' value='$id->id'>" : '' ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="company" class="block text-gray-600 text-sm mb-1">Compañia</label>
            <input type="text" id="company" name="company" value="<?php echo isset($id) ? $id->company : '' ?>" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
        </div>
        <div>
            <label for="city" class="block text-gray-600 text-sm mb-1">Ciudad</label>
            <input type="text" id="city" name="city" value="<?php echo isset($id) ? $id->city : '' ?>" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
        </div>
      </div>

      <div class="pt-4 grid grid-cols-1 sm:grid-cols-1 gap-4">
        <div class="text-center text-sm" id="spreadsheet"></div>
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

<script>
jspreadsheet = jspreadsheet(document.getElementById('spreadsheet'), {
  minDimensions:[2,1],
  autoIncrement: false,
  allowInsertColumn: false, 
  allowDeleteColumn: false, 
  allowRenameColumn: false,
  columnDrag:true,
  allowExport: false,
  tableWidth: '100%',
  oninsertrow:function(instance, cell, col, row) {
    jspreadsheet.getData
    var data = jspreadsheet.getData();
    var lastRow = data.length;
    var cell = jspreadsheet.getCell('A'+lastRow);
    jspreadsheet.updateSelection(cell);
  },
  columns: [
    {title:'Contacto',type:'text',width:200,},
    {title:'Email',type:'text',width:200,},
    {title:'Tel',type:'number',width:200,},

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