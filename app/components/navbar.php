<div class="py-2 pl-4 pr-2 bg-white flex items-center shadow-md sticky top-0 left-0 z-20">
  <button type="button" @click="sidebar = !sidebar" class="text-lg text-gray-600 sidebar-toggle mr-4">
  <i class="ri-menu-line"></i>
  </button>
  <ul class="flex items-center text-sm text-gray-600 ml-4 font-medium"><?php echo $title ?></ul>
  <ul class="ml-auto flex items-center">
    <?php if(in_array(12,$permissions)) {require_once "app/components/notifications.php";} ?>
    <li class="dropdown" x-data="{ dropdown: false}">
      <button type="button" 
        class="text-gray-400 w-8 h-8 rounded flex items-center justify-center hover:bg-gray-50 hover:text-gray-600"
        @click="dropdown = !dropdown" >
        <i class="ri-user-line"></i>
      </button>
      <ul 
        class="my-2 shadow-md shadow-black/5 z-30 py-1.5 rounded-md bg-white border border-gray-100 w-full max-w-[140px]" 
        style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(0px, 48px, 0px);"
        x-cloak
        x-show="dropdown"
        @click.away="dropdown = false">
        <li>
          <a href='' hx-get='?c=Users&a=Profile&id=<?php echo $user->id ?>' hx-target='#myModal' @click='showModal = true' class="flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-blue-500 hover:bg-gray-50"><?php echo $user->username ?></a>
        </li>
        <li>
          <a href="?c=Home&a=Logout" class="flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-blue-500 hover:bg-gray-50">Salir</a>
        </li>
      </ul>
    </li>
  </ul>
</div>