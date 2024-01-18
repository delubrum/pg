<?php foreach ($this->notifications as $r) { ?>
  <a href="#" class="py-2 px-4 flex items-center hover:bg-gray-50 group border-b"
  hx-get="<?php echo $r->url ?>&id=<?php echo $r->itemId ?>"
  hx-target="#content"
  >
    <div class="ml-2">
      <div class="text-[13px] text-gray-600 font-medium"><?php echo $r->title ?></div>
    </div>
    <hr>
  </a>
<?php } ?>