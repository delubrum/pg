<?php foreach ($notifications as $r) { ?>
  <a href="#" class="py-2 px-4 flex items-center hover:bg-gray-50 group"
  hx-get="<?php echo $r->url ?>&id=<?php echo $r->itemId ?>"
  hx-target="#content"
  >
    <div class="ml-2">
      <div class="text-[13px] text-gray-600 font-medium truncate group-hover:text-blue-500"><?php echo $r->title ?></div>
      <div class="text-[11px] text-gray-400">Id: <?php echo $r->itemId ?></div>
    </div>
  </a>
<?php } ?>