<div
  class="overflow-auto fixed right-0 top-12 w-72 h-full p-4 z-20 transition-transform translate-x-full bg-white shadow-xl"
  x-bind:class="{ '': showFilters, 'translate-x-full': !showFilters }"
>
    <button @click="showFilters = !showFilters" class="absolute top-0 right-0 m-3 text-teal-900 hover:text-teal-700">
        <i class="ri-close-line text-2xl"></i>
    </button>
    <h1 class="text-lg font-semibold mb-4 text-teal-700"><i class="ri-filter-3-line text-3xl"></i> Filtros</h1>
    <?php if (isset($filters)) {require_once $filters;} ?>
</div>
