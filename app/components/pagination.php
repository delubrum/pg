<tr>
  <td colspan="<?php echo count($this->fields) ?>" class="text-left mt-4 pt-4">
      <button 
        class="p-1 text-teal-900 font-semibold hover:text-teal-700"
        hx-get="<?php echo $this->url ?>&page=1" 
        hx-target="#list" 
        hx-include=".filter"
      >
        Primera
      </button>
      <?php if($page-1 != 0) { ?>
        <button 
        class="p-1 text-teal-900 font-semibold hover:text-teal-700"
          hx-get="<?php echo $this->url ?>&page=<?php echo $page-1 ?>" 
          hx-target="#list" 
          hx-include=".filter"
        >
          <i class="ri-arrow-left-s-line"></i>
        </button>
      <?php } ?>
      <select name="page" 
        class="p-1 cursor-pointer bg-gray-200 hover:bg-gray-300 rounded-md"
        hx-get="<?php echo $this->url ?>" 
        hx-target="#list"
        hx-include=".filter"
      >
        <?php for ($i = 1; $i <= ceil($filtered/$perPage); $i++) { ?>
        <option value="<?php echo $i ?>" <?php echo ($i==$page) ? 'selected' : '' ?>><?php echo $i ?></option>
        <?php } ?>
      </select>
      <?php if($page+1 <= ceil($filtered/$perPage)) { ?>
        <button
        class="p-1 text-teal-900 font-semibold hover:text-teal-700"
          hx-get="<?php echo $this->url ?>&page=<?php echo $page+1 ?>" 
          hx-target="#list"
          hx-include=".filter"
        >
        <i class="ri-arrow-right-s-line"></i>
      </button>
      <?php } ?>
      <button 
      class="p-1 text-teal-900 font-semibold hover:text-teal-700"
        hx-get="<?php echo $this->url ?>&page=<?php echo ceil($filtered/$perPage) ?>" 
        hx-target="#list"
        hx-include=".filter"
      >
        Última
      </button>
      <span class="float-right"><?php echo $filtered ?> / <?php echo $total ?> (10 por Página)</span>
  </td>
</tr>