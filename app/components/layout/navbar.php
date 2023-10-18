<div class="py-2 pl-4 pr-2 bg-white flex items-center shadow-md sticky top-0 left-0 z-20">
  <button type="button" @click="sidebar = !sidebar" class="text-lg text-gray-600 sidebar-toggle mr-4">
  <i class="ri-menu-line"></i>
  </button>
  <ul id="title" class="flex items-center text-sm text-gray-600 ml-4 font-medium"></ul>
  <ul class="ml-auto flex items-center">
    <?php require_once "app/components/layout/notifications.php" ?>
    <?php require_once "app/components/layout/userdrop.php" ?>
    <!-- <li class="text-sm text-gray-600 mr-3 font-medium"><?php echo $user->username ?></li>
    <li>
      <a href="?c=Home&a=Logout" class="flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-blue-500 hover:bg-gray-50"><i class="ri-close-line"></i></a>
    </li> -->
  </ul>
</div>