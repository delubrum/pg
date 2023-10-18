<?php 
  $today=date("Y-m-d");
  $filter = "and date(createdAt) = '$today' and userId = $user->id";
  if($url == '?c=Report&a=Data') {
    if (!$this->model->list('id','report',$filter)) {
?>
<button 
  id="new"
  class='float-right mr-14 mt-4 bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded'
  hx-get='<?php echo $new ?>'
  hx-target="#myModal"
  @click='showModal = true'>
  <i class="ri-add-line"></i> New
</button>
<?php } else {echo "<h4 class='float-right mr-14 mt-4 text-blue-500'>You have already reported your hours today. Thank you!</h4>"; 
}} else { ?>
  <button 
  id="new"
  class='float-right mr-14 mt-4 bg-blue-500 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded'
  hx-get='<?php echo $new ?>'
  hx-target="#myModal"
  @click='showModal = true'>
  <i class="ri-add-line"></i> New
</button> 
<?php } ?>

<div class="mx-2 sm:mx-10 mt-2 sm:mt-4 px-3 sm:px-6 py-3 sm:py-6 bg-white rounded-lg shadow-xl">
  <table class="w-full display table-striped text-xs sm:text-sm" id="list">
      <thead>
        <tr>
          <?php foreach($fields as $f) { ?>
            <th <?php echo ($f == 'action') ? "style='text-align:right'" : "" ?>> <?php echo ucwords($f) ?> </th>
          <?php } ?>
        </tr>
      </thead>
    </table> 
</div>

<script>
var table = $('#list').DataTable({
  order: [0,'desc'],
  lengthChange : false,
  paginate: true,
  pageLength: 10,
  scrollX : true,
  autoWidth : false,
  serverSide : true,
  processing: true,
  ajax: {
    url: "<?php echo $url ?>",
    type: "POST",
  },
  columns : [
    <?php foreach($fields as $f) {
      echo "{data: '$f'},";
    } ?>
  ],
  columnDefs : [
      { "width": "100px", "targets": [<?php echo count($fields)-1 ?>] },
      { "className": "text-right", "targets": [<?php echo count($fields)-1 ?>] },

  ],
  <?php if(isset($_REQUEST['id'])) { ?>
  search: {
    "search": '<?php echo $_REQUEST['id'] ?>'
  },
  <?php } ?>
  drawCallback: function(settings) {
    htmx.process(document.getElementById('list'));
  }
});
</script>