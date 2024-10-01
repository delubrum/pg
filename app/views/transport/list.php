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
  <?php foreach ($list as $r) { ?>
  <tr>
    <td class="px-2 py-2 border-b">
    <?php echo $r->fecha ?>
    </td>
    <td class="px-2 py-2 border-b">
    <?php echo $r->tipo ?>
    </td>
    <td class="px-2 py-2 border-b">
    <?php echo $r->código ?>
    </td>
    <td class="px-2 py-2 border-b">
    <?php echo $this->model->get('username','users', "and id = $r->responsable")->username ?>
    </td>
    <td class="px-2 py-2 border-b">
      Ecoambientales
    </td>
    <td class="px-2 py-2 border-b">
      <?php 
      echo $this->model->get('b.company as clientname','rm a', "and a.id = $r->código","LEFT JOIN users b on a.clientId = b.id")->clientname;
      ?>
    </td>
    <td class="px-2 py-2 border-b">
      <?php echo ($r->tipo == 'RM') ? $this->model->get('remission','rm'," and id = '$r->código'")->remission : ''; ?>
    </td>
    <td class="px-2 py-2 border-b">
      <?php echo $r->kg ?>
    </td>
    <td class="px-2 py-2 border-b">
      <input hx-trigger="change" <?php echo (!in_array(14, $permissions)) ? 'disabled': '';?>
      hx-post="?c=Transport&a=UpdateField"
      hx-vals="js:{id: '<?php echo $r->id ?>', val: event.target.value, field: 'price'}"
      hx-swap="none" 
      hx-indicator="#loading"
      value="<?php echo number_format($r->valor) ?>">
    </td>
    <td class="px-2 py-2 border-b">
      <?php echo $r->drumsReturned ?>
    </td>
    <td class="px-2 py-2 border-b">
      <?php echo $r->barrels ?>
    </td>
    <td class="px-2 py-2 border-b">
      <?php echo $r->drums ?>
    </td>
    <td class="px-2 py-2 border-b">
      <?php echo ($r->tipo == 'Factura' and isset($this->model->get('returnToClient','rm'," and id = $r->rmId")->returnToClient) and $this->model->get('returnToClient','rm'," and id = $r->rmId")->returnToClient != 0) ? $r->barrels : 0 ?>
    </td>
    <td class="px-2 py-2 border-b">
      <?php echo ($r->tipo == 'Factura' and isset($this->model->get('returnToClient','rm'," and id = $r->rmId")->returnToClient) and $this->model->get('returnToClient','rm'," and id = $r->rmId")->returnToClient != 0) ? $r->drums : 0 ?>
    </td>
    <td class="px-2 py-2 border-b">
      <?php echo $r->drumsSended ?>
    </td>
  </tr>
  <?php $suma += $r->valor;} require_once "app/components/pagination.php" ?>
</tbody>
<tfoot>
  <tr>
    <th colspan="9" ><br><br><div style="font-size:20px">TOTAL: <?php echo number_format($this->model->get('sum(price) as price','transport')->price) ?></div></th>
  </tr>
</tfoot>





 