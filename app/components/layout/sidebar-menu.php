<ul class="mt-4 flex-1">
<?php
$current_title = '';
foreach ($this->model->list('id,icon,title,subtitle,url', 'permissions', "and type = 'menu' ORDER BY title,sort") as $r) {
  if (in_array($r->id, $permissions)) {
    if ($r->title != $current_title) {
        // Start a new group with the dropdown title
        if ($current_title != '') {
          // Close the previous group if it exists
          echo '</ul></li>';
        }
        echo "<li class='mb-1 group' x-data='{ dropdown: false }'>
          <a
              href='#'
              class='flex items-center py-2 px-4 text-gray-300 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100'
              @click='dropdown = !dropdown'
              :class='dropdown ? \"bg-gray-950\" : \"\"'
          >
              $r->icon
              <span class='text-sm'>" . (isset($lang[$r->title]) ? $lang[$r->title] : $r->title) . "</span>
              <i class='ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90'></i>
          </a>
          <ul class='pl-7 mt-2 group-[.selected]:block' x-show='dropdown' @click.away='dropdown = false'>";
        $current_title = $r->title;
    } ?>
    <li class='mb-4'>
      <a class="cursor-pointer text-gray-300 text-sm flex items-center hover:text-blue-500 before:contents-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3"
        @click="sidebar = !sidebar" 
        hx-on:htmx:after-request="document.getElementById('title').innerHTML = '<?php echo "$r->title / $r->subtitle" ?>';" 
        hx-get="<?php echo $r->url ?>" 
        hx-target="#content" 
        hx-trigger="click" 
        >
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