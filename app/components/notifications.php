<li class="dropdown" x-data="{ isOpen: false}">
  <button 
      class="text-gray-400 w-8 h-8 rounded flex items-center justify-center hover:bg-gray-50 hover:text-gray-600 relative"
      type="button" 
      @click="isOpen = !isOpen" 
      hx-get="?c=Home&a=Notifications&list=1"
      hx-target="#notifications"
    >
      <i class="ri-notification-3-line"></i>
      <span 
        class="absolute top-0 right-0 flex items-center justify-center w-4 h-4 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full"
        hx-get="?c=Home&a=Notifications&list=0"
        hx-trigger="every 2s"
        hx-target="this"
      >
      <?php echo count($this->model->list('id,title,itemId,url,target,permissionId','notifications', "and status = 1")) ?>
      </span>
  </button>
  <ul 
    class="max-h-64 overflow-y-auto shadow-md shadow-black/5 z-30 max-w-xs w-full bg-white rounded-md border border-gray-100 my-2" 
    style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(0px, 48px, 0px);"
    x-cloak
    x-show="isOpen"
    @click.away="isOpen = false"
  >
  <table id="notifications"></table>
  </ul>
</li>