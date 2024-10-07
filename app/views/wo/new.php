<div @click.outside="showModal = false" class="w-[95%] sm:w-[95%] bg-white p-4 rounded-lg shadow-lg relative z-50">
    <!-- Close Button (X) in Top-Right Corner -->
    <button id="closeModal" @click="showModal = !showModal" class="absolute top-0 right-0 m-3 text-teal-700 hover:text-teal-900">
        <i class="ri-close-line text-2xl"></i>
    </button>
    <h1 class="mb-4 text-teal-700"><i class="ri-file-add-line text-3xl"></i> <span class="text-2xl font-semibold">Nuevo RM (<?php echo isset($this->model->get('id','rm', ' ORDER BY id DESC LIMIT 1')->id) ?  $this->model->get('id','rm', ' ORDER BY id DESC LIMIT 1')->id + 1 : 1 ; ?>)</span></h1>
    <form  id="newForm" 
        class="overflow-y-auto max-h-[400px] p-2"
        hx-post='?c=WO&a=Save' rm_items
        hx-swap="none" 
        hx-indicator="#loading"
    >

    <!-- <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
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
          <?php foreach ($this->model->list("*","users"," and type = 'Cliente' and status = 1 ORDER BY company ASC") as $r) { ?>     
              <option value='<?php echo $r->id?>'><?php echo $r->company?></option>
          <?php } ?>
        </select>
      </div>
      <div>
          <label for="qty" class="block text-gray-600 text-sm mb-1">Remisión del Cliente</label>
          <input id="remission" name="remission" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
      </div>
      <div>
        <label for="product" class="block text-gray-600 text-sm mb-1">Producto</label>
        <select id="product" name="productId" class="bg-white p-[9px] w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-teal-700 focus:outline-none" required>
          <option value='' disabled selected>Seleccione el Cliente...</option>
        </select>
      </div>
    </div> -->


      <table class="w-full display table-striped text-xs sm:text-sm" id="wo">
          <thead>
              <tr>
                  <?php foreach($fields as $f) { ?>
                  <th <?php echo ($f == 'action') ? "style='text-align:right'" : "" ?>> <?php echo ucwords($f) ?> </th>
                  <?php } ?>
              </tr>
          </thead>
      </table> 

      <button type="submit" class="pt-6 float-right text-xl text-teal-900 font-bold hover:text-teal-700"><i class="ri-save-line"></i> Crear Lote</button>

    </form>
</div>

<script>
var wo = $('#wo').DataTable({
    order: [0,'asc'],
    paginate: false,
    searchable: false,
    ajax: {
      url: "<?php echo $url ?>",
      type: "POST",
      dataSrc: function (json) {
        // Check if the data array is not empty or null
        if (json != '') {
          return json;
        } else {
          return []; // Return an empty array to prevent rendering
        }
      },
    },
    columns : [
        <?php foreach($fields as $f) {
        echo "{data: '$f'},";
        } ?>
    ],
});

</script>