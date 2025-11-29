<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
      Filter & Sort
    </h2>

    <div class="space-y-4">
      <!-- Search -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Search
        </label>
        <input
          v-model="localFilters.search"
          @input="handleFilterChange"
          type="text"
          placeholder="Search todos..."
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
        />
      </div>

      <!-- Category Filter -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Category
        </label>
        <select
          v-model="localFilters.category_id"
          @change="handleFilterChange"
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
        >
          <option value="">All Categories</option>
          <option v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.name }}
          </option>
        </select>
      </div>

      <!-- Status Filter -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Status
        </label>
        <select
          v-model="localFilters.status"
          @change="handleFilterChange"
          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
        >
          <option value="">All Todos</option>
          <option value="incomplete">Incomplete</option>
          <option value="completed">Completed</option>
          <option value="overdue">Overdue</option>
        </select>
      </div>

      <!-- Sort By -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Sort By
        </label>
        <div class="grid grid-cols-2 gap-2">
          <select
            v-model="localFilters.sort_by"
            @change="handleFilterChange"
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
          >
            <option value="created_at">Created</option>
            <option value="due_date">Due Date</option>
            <option value="title">Title</option>
          </select>
          <select
            v-model="localFilters.sort_order"
            @change="handleFilterChange"
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
          >
            <option value="asc">Ascending</option>
            <option value="desc">Descending</option>
          </select>
        </div>
      </div>

      <!-- Reset Button -->
      <button
        @click="handleReset"
        class="w-full bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-white py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors"
      >
        Reset Filters
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  categories: {
    type: Array,
    default: () => []
  },
  filters: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['update:filters', 'filter']);

const localFilters = ref({ ...props.filters });

watch(() => props.filters, (newFilters) => {
  localFilters.value = { ...newFilters };
}, { deep: true });

const handleFilterChange = () => {
  emit('update:filters', { ...localFilters.value });
  emit('filter');
};

const handleReset = () => {
  localFilters.value = {
    search: '',
    category_id: '',
    status: '',
    sort_by: 'created_at',
    sort_order: 'desc'
  };
  handleFilterChange();
};
</script>

