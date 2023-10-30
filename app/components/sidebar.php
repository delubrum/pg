<div @mouseover="sidebar = !sidebar" class="left-side fixed top-0 left-0 w-8 h-full z-40"></div>
<div x-bind:class="{ '': sidebar, '-translate-x-full': !sidebar }"
  class="overflow-auto fixed left-0 top-0 w-64 h-full p-4 z-50 transition-transform -translate-x-full bg-white">
  <a class="flex items-center pb-4 border-b border-b-gray-800">
    <img src="app/assets/img/logo2.png" class="w-[75%] mx-auto"/>
    <!-- <img src="app/assets/logo.png" alt="" class="w-8 h-8 rounded object-cover"/> -->
    <!-- <span class="text-lg font-bold text-white ml-3">Componenti</span> -->
  </a>
  <ul class="mt-4 flex-1">
    <?php
    $current_title = '';
    foreach ($this->model->list('id,icon,title,subtitle,url', 'permissions', "and type = 'menu' ORDER BY sort,title") as $r) {
      if (in_array($r->id, $permissions)) {
        isset($_REQUEST['c']) ? $active = "?c=" . $_REQUEST['c'] . "&a=" . $_REQUEST['a'] . "&m=" . $_REQUEST['m'] : $active = '';
        isset($_REQUEST['m']) ? $menu = $_REQUEST['m'] : $menu = '';
        if ($r->title != $current_title) {
            if ($current_title != '') { ?>
          </ul></li>
          <?php } ?>
            <li class='mb-1 group' <?php echo ($r->title != $menu) ? "x-data='{ dropdown: false }'" : "x-data='{ dropdown: true }'"; ?>>
              <a
                  href='#'
                  class='flex items-center py-2 px-4 text-black <?php echo ($r->title != $menu) ? "hover:bg-teal-900" : "bg-teal-900 text-white"; ?> hover:text-gray-100 rounded-md'
                  @click='dropdown = !dropdown'
                  :class="dropdown ? 'bg-teal-900 text-white' : ''"
              >
                  <?php echo $r->icon ?> 
                  <span class='text-sm'><?php echo (isset($lang[$r->title]) ? $lang[$r->title] : $r->title) ?></span>
                  <i class='ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90'></i>
              </a>
              <ul class='pl-7 mt-2 group-[.selected]:block' x-show='dropdown' @click.away='dropdown = false'>
              <?php $current_title = $r->title; } ?>
        <li class='mb-4'>
          <a href="<?php echo $r->url ?>" class="cursor-pointer text-sm flex items-center <?php echo ($r->url != $active) ? "hover:text-teal-700" : "text-teal-700"; ?>  before:contents-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">
            <?php echo isset($lang[$r->subtitle]) ? $lang[$r->subtitle] : $r->subtitle ?>
          </a>
        </li>
        <?php
      }
    }
    if ($current_title != '') {
        echo '</ul></li>';
    }
    ?>
  </ul>
</div>

<div @mouseover="sidebar = !sidebar" 
  @click="sidebar = !sidebar"
  x-bind:class="{ '': sidebar, '-translate-x-full': !sidebar }" class="fixed top-0 left-0 w-full h-full bg-black/50 z-30">
</div>
