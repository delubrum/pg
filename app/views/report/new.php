<div @click.outside="showModal = false" class="overflow-auto w-[95%] sm:w-[25%] bg-white p-4 rounded-lg shadow-lg relative z-50">
    <!-- Close Button (X) in Top-Right Corner -->
    <button id="close" @click="showModal = !showModal" class="absolute top-0 right-0 m-3 text-gray-600 hover:text-gray-800">
        <i class="ri-close-line text-2xl"></i>
    </button>
    <h1 class="text-lg font-semibold mb-4"><i class="ri-file-add-line text-3xl"></i> New</h1>
    <form id="newForm" 
        class="overflow-y-auto max-h-[600px] p-4"
        hx-post='?c=Report&a=Save' 
        hx-target="#new" 
        hx-swap="outerHTML" 
        hx-on:htmx:after-request="table.ajax.reload( null, false );toastr.success('Success');document.getElementById('close').click()" 
    >
        <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
            <div>
                <label for="projectId" class="block text-gray-600 text-sm mb-1">Project</label>
                <select id="projectId" name="projectId" class="select2 w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none" required>
                    <option value="" disabled selected></option>
                    <?php foreach($this->model->list('id,name','projects'," and status = 1") as $r) { ?>
                        <option value="<?php echo $r->id ?>"><?php echo $r->name ?></option>
                    <?php } ?>
                </select>
            </div>
            <div>
                <label for="hours" class="block text-gray-600 text-sm mb-1">Hours</label>
                <input type="number" step="1" min="1" type="number" oninput="this.value = Math.floor(this.value);" id="hours" name="hours" class="w-full p-1.5 border border-gray-300 rounded-md focus:ring focus:ring-blue-500 focus:outline-none" required>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition"><i class="ri-save-line"></i> Save</button>
        </div>
    </form>
</div>

<script>
$('.select2').select2();
</script>