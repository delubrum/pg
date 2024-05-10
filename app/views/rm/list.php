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
    <select hx-trigger="change" <?php echo (!in_array(13, $permissions)) ? 'disabled': '';?>
      hx-post="?c=RM&a=UpdateField" 
      hx-vals="js:{id: '<?php echo $r->RM ?>', val: event.target.value, field: 'status'}"
      hx-swap="none" 
      hx-indicator="#loading"
    >
    <option value=''></option>
    <?php 
    $array = ["Terminar R.M.", "Producción", "Iniciado", "Análisis", "Facturación", "Cerrado"];
    foreach($array as $s) { ?>
        <option <?php echo ($r->status == $s) ? 'selected' : ''; ?> value='<?php echo $s?>'><?php echo $s?></option>
    <?php } ?>
    </select>
  </td>
  <td class="px-2 py-2 border-b">
    <?php echo $r->factura ?>
  </td>
  <td class="text-right px-2 py-2 border-b cursor-pointer">
    <?php
    $edit = '';
      if ($r->status == 'Terminar R.M.') { $edit = "<a hx-get='?c=RM&a=RM&id=$r->RM' hx-target='#myModal' @click='showModal = true' class='text-teal-900 hover:text-teal-700'><i class='ri-edit-2-line text-2xl'></i> Terminar R.M.</a>"; }
      if ($r->status == 'Producción' || $r->status == 'Iniciado') { $edit = "<a hx-get='?c=BC&a=BC&id=$r->RM' hx-target='#myModal' @click='showModal = true' class='text-teal-900 hover:text-teal-700'><i class='ri-hammer-line text-2xl'></i> Producir</a>"; }
      if ($r->status == 'Análisis' and in_array(15,$permissions)) { $edit = "<a hx-get='?c=IP&a=IP&id=$r->RM' hx-target='#myModal' @click='showModal = true' class='text-teal-900 hover:text-teal-700'><i class='ri-edit-2-line text-2xl'></i> Análisis</a>"; }
      if ($r->status == 'Facturación' and in_array(10,$permissions)) { $edit = "<a hx-get='?c=IP&a=IV&id=$r->RM' hx-target='#myModal' @click='showModal = true' class='text-teal-900 hover:text-teal-700'><i class='ri-exchange-dollar-line text-2xl'></i> Facturar</a>"; }
      if ($r->status == 'Cerrado') { $edit = ""; }
      $rm = ($r->status != 'Terminar R.M.' and $r->status != 'Registrando') ? "<br><a href='?c=RM&a=Detail&id=$r->RM' target='_blank' class='text-teal-900 hover:text-teal-700'><i class='ri-file-line text-2xl'></i> Recibo de Material</a>" : "";
      $bc = ($r->status == 'Facturación' || $r->status == 'Cerrado' || $r->status == 'Análisis') ? "<br><a href='?c=BC&a=Detail&id=$r->RM' target='_blank' class='text-teal-900 hover:text-teal-700'><i class='ri-file-line text-2xl'></i> Bitácora</a>" : "";
      $pd = ($r->status == 'Facturación' || $r->status == 'Cerrado') ? "<br><a href='?c=Reports&a=PD&id=$r->RM' target='_blank' class='text-teal-900 hover:text-teal-700'><i class='ri-file-line text-2xl'></i> Paquete Despacho</a>" : "";
    ?>

    <div x-data="{ open: false }">
        <i @click="open = !open" class="ri-more-2-fill text-2xl cursor-pointer text-teal-900 hover:text-teal-700"></i>
        <div x-show="open" @click.away="open = false" class="absolute right-10 origin-top-right z-50 rounded-md shadow-lg">
            <div class="bg-white rounded-md py-2 px-4">
                <?php echo "$edit $rm $bc $pd" ?>
            </div>
        </div>
    </div>

  </td>
</tr>
</tbody>
<?php } require_once "app/components/pagination.php" ?>
