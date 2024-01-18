<div class="mx-10 mr-2 sm:mx-6 mt-2 sm:mt-6 px-2 sm:px-4 py-3 sm:py-4 bg-white rounded-lg shadow-xl">

<?php if(isset($new)) { ?>
  <button 
    class="text-xl float-left text-teal-900 px-4 py-2 font-bold hover:text-teal-700"
    hx-get='<?php echo $new ?>'
    hx-target="#myModal"
    @click='showModal = true'
    hx-indicator="#loading"
  >
    <i class="ri-file-add-line"></i> Crear
  </button>
<?php } ?>

<?php if(isset($filters)) { ?>
  <button 
    class="text-xl float-right text-teal-900 px-4 py-2 font-bold hover:text-teal-700"
    @click='showFilters = true'>
    <i class="ri-filter-3-line"></i> Filtrar
  </button>
<?php } ?>


  <div class="overflow-x-auto w-full" @click ="showFilters = false">
    <table id="list" 
      hx-trigger="load, listChanged from:body"
      hx-get="<?php echo $this->url ?>"
      hx-indicator="#loading"
      class="mt-4 min-w-full text-sm"
    ></table>
  </div>



</div>