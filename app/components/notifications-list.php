<tbody hx-confirm="Seguro?" hx-target="closest tr">
<?php foreach ($this->model->list('id,title,itemId,url,target,permissionId','notifications', "and status = 1") as $r) { ?>
  <tr>
    <td>
  <a class="py-2 px-4 flex items-center hover:bg-gray-50 group border-b relative">
    <div class="ml-2 pr-8"> <!-- Add padding to the right -->
        <div class="text-[13px] text-gray-600 font-medium"><?php echo $r->title ?></div>
    </div>
    <div class="absolute right-0 top-0 bottom-0 flex items-center">
        <button class="text-red-500 hover:text-red-700 px-2 focus:outline-none"  
          hx-delete="?c=Home&a=DeleteAlert&id=<?php echo $r->id ?>"
        >
          <i class="ri-delete-bin-2-fill"></i></button>
    </div>
    <hr>
  </a>
</td>
</tr>
<?php } ?>
</tbody>