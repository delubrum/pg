<thead>
  <tr>
  <?php foreach($this->fields as $f) { ?>
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
</tbody>
<?php foreach ($list as $r) { ?>
<tr>
  <td class="px-2 py-2 border-b">
    <?php echo $r->RM ?>
  </td>
  <td class="px-2 py-2 border-b">
    <?php echo $r->fecha ?>
  </td>
  <td class="px-2 py-2 border-b">
    <?php echo $r->creador ?>
  </td>
  <td class="px-2 py-2 border-b">
    <?php echo $r->cliente ?>
  </td>
  <td class="px-2 py-2 border-b">
    <?php echo $r->producto ?>
  </td>
  <td class="px-2 py-2 border-b">
    <?php echo $r->status ?>
  </td>
  <td class="px-2 py-2 border-b">
    <?php echo $r->factura ?>
  </td>
  <td class="px-2 py-2 border-b">
    <?php 
    $edit = "<a hx-get='?c=RM&a=RM&id=$r->RM' hx-target='#myModal' @click='showModal = true' class='block text-teal-900 hover:text-teal-700 cursor-pointer float-right mx-3'><i class='ri-edit-2-line text-2xl'></i> Editar</a>";
    echo "$edit" ?>
  </td>
</tr>
<?php } require_once "app/components/pagination.php" ?>
