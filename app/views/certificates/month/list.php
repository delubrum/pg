<thead>
  <tr>
    <?php $suma = 0; foreach($this->fields as $f) { ?>
      <th 
      <?php if ($f != 'acción') { ?>
        hx-get="<?php echo $this->url ?>&colum=<?php echo $f ?>&order=<?php echo $newOrder ?>" 
        hx-target="#list" 
        hx-include=".filter"
      <?php } ?>
      class="cursor-pointer px-2 py-2 text-left text-md border-b-2 border-teal-700 <?php echo ($f == 'acción') ? "text-right" : "" ?>"> 
      <?php if($newOrder == 'asc' and $colum == $f) { echo "<i class='ri-arrow-down-s-fill'></i>"; } ?> 
      <?php if($newOrder == 'desc' and $colum == $f) { echo "<i class='ri-arrow-up-s-fill'></i>"; } ?>
      <?php echo ucwords($f); ?>
      </th>
    <?php } ?>
  </tr>
</thead>
<tbody>
  <?php foreach ($list as $r) { 
    if ((date("Y-m-l",strtotime($r->date)) <= date("Y-m-d"))) {
      $date = $r->date;
    ?>
  <tr>
    <td class="px-2 py-2 border-b">
    <?php echo date("Y-m",strtotime($r->date)); ?>
    </td>
    <td class="text-right px-2 py-2 border-b cursor-pointer">
    <?php echo "<a href='?c=CMonth&a=Detail&date=$date' type='button' target='_blank' class='text-teal-900 hover:text-teal-700'><i class='ri-eye-line text-2xl'></i></a>" ?>
    </td>
  </tr>
  <?php }} require_once "app/components/pagination.php" ?>
</tbody>





 