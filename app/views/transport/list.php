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
      <?php $query = ($r->tipo == 'RM') ?  'a.id' : 'a.invoice';
      echo $this->model->get('b.username as clientname','rm a', "and $query = $r->código","LEFT JOIN users b on a.clientId = b.id")->clientname;
      ?>
    </td>
    <td class="px-2 py-2 border-b">
      <?php echo $r->tambores ?>
    </td>
    <td class="px-2 py-2 border-b">
      <?php echo $r->kg ?>
    </td>
    <td class="px-2 py-2 border-b">
      <?php echo $r->valor ?>
    </td>
  </tr>
  <?php } require_once "app/components/pagination.php" ?>
</tbody>





 