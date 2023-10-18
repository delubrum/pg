<div @mouseover="sidebar = !sidebar" class="left-side fixed top-0 left-0 w-8 h-full z-40"></div>
<div x-bind:class="{ '': sidebar, '-translate-x-full': !sidebar }"
  class="overflow-auto fixed left-0 top-0 w-64 h-full bg-gray-900 p-4 z-50 transition-transform -translate-x-full">
  <a class="flex items-center pb-4 border-b border-b-gray-800">
    <img src="app/assets/img/logo.png" class="w-[75%] mx-auto"/>
    <!-- <img src="app/assets/logo.png" alt="" class="w-8 h-8 rounded object-cover"/> -->
    <!-- <span class="text-lg font-bold text-white ml-3">Componenti</span> -->
  </a>
  <div id="sidebarMenu">
    <?php require_once "app/components/layout/sidebar-menu.php" ?>
  </div>
</div>
<div @mouseover="sidebar = !sidebar" 
  @click="sidebar = !sidebar"
  x-bind:class="{ '': sidebar, '-translate-x-full': !sidebar }" class="fixed top-0 left-0 w-full h-full bg-black/50 z-30">
</div>