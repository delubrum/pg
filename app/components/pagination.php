<tr>
  <td colspan="2" class="text-center my-4 flex">
      <button 
        class="px-2 py-1 mx-1 bg-teal-900  hover:bg-teal-700 text-white rounded-md"
        hx-get="<?php echo $this->url ?>&page=1" 
        hx-target="#list" 
        hx-include=".filter"
      >
        Primera
      </button>
      <?php if($page-1 != 0) { ?>
        <button 
          class="px-2 py-1 mx-1 bg-teal-900  hover:bg-teal-700 text-white rounded-md"
          hx-get="<?php echo $this->url ?>&page=<?php echo $page-1 ?>" 
          hx-target="#list" 
          hx-include=".filter"
        >
          <
        </button>
      <?php } ?>
      <select name="page" 
        class="mx-2 cursor-pointer bg-gray-200 py-1 px-2 hover:bg-gray-300 rounded-md"
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
          class="px-2 py-1 mx-1 bg-teal-900  hover:bg-teal-700 text-white rounded-md"
          hx-get="<?php echo $this->url ?>&page=<?php echo $page+1 ?>" 
          hx-target="#list"
          hx-include=".filter"
        >
          >
      </button>
      <?php } ?>
        <button 
          class="px-2 py-1 mx-1 bg-teal-900  hover:bg-teal-700 text-white rounded-md"
          hx-get="<?php echo $this->url ?>&page=<?php echo ceil($filtered/$perPage) ?>" 
          hx-target="#list"
          hx-include=".filter"
        >
          Última
        </button>
  </td>
  <td class="text-right p-2 mx-2" colspan="<?php echo count($this->fields)-1 ?>"> <?php echo $filtered ?> Filtrados / <?php echo $total ?> Total (10 por Página)</td>
</tr>