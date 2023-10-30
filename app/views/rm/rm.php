<div @click.outside="showModal = false" class="w-[95%] sm:w-[90%] bg-white p-4 rounded-lg shadow-lg relative z-50">
    <!-- Close Button (X) in Top-Right Corner -->
    <button id="close" @click="showModal = !showModal" class="absolute top-0 right-0 m-3 text-teal-900 hover:text-teal-700">
        <i class="ri-close-line text-2xl"></i>
    </button>
    <h1 class="text-lg font-semibold mb-4 text-teal-700"><i class="ri-file-add-line text-3xl"></i> Nuevo Registro</h1>
    <form  id="newForm" 
        class="overflow-y-auto max-h-[600px] p-4"
        hx-post='?c=RM&a=Update' 
        hx-swap="none" 
        hx-vals='js:{table: table.getData()}'
        hx-trigger='submit'
    >
    <?php echo isset($id) ? "<input type='hidden' name='id' value='$id->id'>" : '' ?>
      <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
        <div>
            <b>RM:</b> <?php echo $id->id ?>
        </div>
        <div>
            <b>FECHA:</b> <?php echo $id->date ?>
        </div>
        <div>
            <b>CLIENTE:</b> <?php echo $id->clientname ?>
        </div>
        <div>
            <b>PRODUCTO:</b> <?php echo $id->productname ?>
        </div>
        <div>
            <b>CIUDAD:</b> <?php echo $id->city ?>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 py-4">
          <div class="text-center text-sm" id="spreadsheet"></div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div>
            <label for="datetime" class="block text-gray-600 text-sm mb-1">Fecha Y Hora Cargue</label>
            <input type="datetime-local"  id="datetime" name="datetime" onfocus='this.showPicker()' min="<?php echo date('Y-m-d', strtotime('-2 days')); ?>" max="<?php echo date('Y-m-d'); ?>" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
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


          <div class="col-sm-1">
              <div class="form-group">
                  <label>* Reactor N°:</label>
                  <div class="input-group">
                      <select class="form-control" name="reactor">
                          <option></option>
                          <option>1</option>
                          <option>2</option>
                          <option>3</option>
                      </select>
                  </div>
              </div>
          </div>

          <div class="col-sm-2">
              <div class="form-group">
                  <label>* Pasta que no Entra:</label>
                  <div class="input-group">
                  <input type="number" step="0.01" class="form-control" id="paste" name="paste" required>
                  </div>
              </div>
          </div>

          <div class="col-sm-1">
              <div class="form-group">
                  <label>* Devolver:</label>
                  <div class="input-group">
                  <input type="number" step="1" min="0" class="form-control" name="toreturn" required>
                  </div>
              </div>
          </div>

          <div class="col-sm-1">
              <div class="form-group">
                  <label>* Excedente:</label>
                  <div class="input-group">
                  <input type="number" step="1" min="0" class="form-control" name="surplus" required>
                  </div>
              </div>
          </div>

          <div class="col-sm-3">
              <div class="form-group">
                  <label>* Total a Cargar:</label>
                  <div class="input-group mt-1 h5 text-primary">
                  <span id="total"> </span>
                  </div>
              </div>
          </div>

          <div class="col-sm-12">
              <div class="form-group">
                  <label>Notas:</label>
                  <div class="input-group">
                  <textarea style="width:100%" class="form-control form-control-sm" name="notes"></textarea>
                  </div>
              </div>
          </div>

      </div>


    </div>


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
    url: "?c=RM&a=ItemsData&id=<?php echo $id->id ?>",
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
    footers: [['=SUMCOL(TABLE(), 0)','=SUMCOL(TABLE(), 1)','=SUMCOL(TABLE(), 2)','=SUMCOL(TABLE(), 3)','=SUMCOL(TABLE(), 4)','=SUMCOL(TABLE(), 5)']],
    columns: [
      { 
        title:'PESO BRUTO',
        type:'numeric',
        width:100,
        readOnly: true,
      },
      { 
        title:'PESO BRUTO \n CLIENTE',
        type:'numeric',
        width:100,
        readOnly: true,
      },
      { 
        title:'TARAS',
        type:'numeric',
        width:100,
      },
      { 
        title:'TARAS CLIENTE',
        type:'numeric',
        width:100,
      },
      { 
        title:'PESO NETO',
        width:100,
        type:'numeric',
        readOnly: true,
      },
      { 
        title:'PESO NETO \n CLIENTE',
        type:'numeric',
        width:100,
        readOnly: true,
      },
      {
        type: 'dropdown',
        title:'ESTADO DEL \n TAMBOR',
        width:100,
        source:[
          "Bueno",
          "Malo",
        ],
        validate: 'required',
        readOnly: true,
      },
      { 
        title:'DERRAMES \n VEHÍCULO',
        type: 'checkbox',
        width:100,
        readOnly: true,
      },
      { 
        title:'DERRAMES \n CANECA',
        type: 'checkbox',
        width:100,
        readOnly: true,
      },
      { 
        title:'DERRAMES \n PLANTA',
        type: 'checkbox',
        width:100,
      },
    ],
    updateTable:function(instance, cell, col, row, val, label, cellName) {
        paste = $('#paste').val();
        total = SUMCOL(table, 4);
        row = row+1;
        $('#total').html(total-paste);
        if (col == 4) {
            cell.innerHTML = (table.getValue('A'+row)-(table.getValue('C'+row))).toFixed(2);
        }
        if (col == 5) {
            cell.innerHTML = (table.getValue('B'+row)-(table.getValue('D'+row))).toFixed(2);
        }
    },
    text:{
        copy:'Copiar',
        paste:'Pegar',
        about: '',
    }
});

$(document).on("change", "#paste", function(e) {
    paste = $('#paste').val();
    net = SUMCOL(table, 4);
    total = Number(net) - Number(paste);
    $('#total').html(total);
});

// Function to check for empty fields in JSON data
function hasEmptyFields(data) {
  function checkObject(obj) {
    for (const key in obj) {
      if (obj.hasOwnProperty(key)) {
        const value = obj[key];
        
        if (value === '' || value === null || value === undefined) {
          return true; // Found an empty field, return true
        } else if (typeof value === 'object') {
          if (checkObject(value)) {
            return true; // Recursively found an empty field, return true
          }
        }
      }
    }
    
    return false; // No empty fields found in this object
  }

  return checkObject(data);
}