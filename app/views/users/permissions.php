<?php if (in_array(1,$permissions)) { ?>
  <div class="w-full">
    <h2 class="text-lg font-semibold text-teal-700">Permisos</h2>
    <div class="overflow-y-auto max-h-[600px] p-4"
    >
    <?php
      $current_category = '';					
      foreach($this->model->list('id,category,name','permissions', 'ORDER BY category,sort') as $r) {
        if ($r->category != $current_category) {
          echo "<h4 class='mt-2'>$r->category</h4>";
          // Reset the list of names
          echo "<div>";
          $current_category = $r->category;
        }
        $color = (in_array($r->id,$userPermissions)) ? 'bg-teal-900 hover:bg-teal-700' : 'bg-gray-500 hover:bg-gray-600';
        $action = (in_array($r->id,$userPermissions)) ? '0' : '1';
        echo "
        <button 
        hx-put='?c=Users&a=UpdatePermission&userId=$id->id&pId=$r->id&action=$action&name=$r->name'
        hx-swap = 'outerHTML'
        class='text-white text-sm py-2 px-4 m-1 rounded-md $color transition'>$r->name</button>";
      }
      echo "</div>";
    ?>
    </div>
  </div>
<?php } ?>