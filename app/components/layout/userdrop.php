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
      <a href="?c=Home&a=Logout" class="flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-blue-500 hover:bg-gray-50">Logout</a>
    </li>
  </ul>
</li>