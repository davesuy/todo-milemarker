<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
      Categories
    </h2>

    <!-- Add Category Form -->
    <form @submit.prevent="handleSubmit" class="space-y-3 mb-4">
      <div v-if="error" class="text-red-600 text-sm p-2 bg-red-50 dark:bg-red-900/20 rounded">
        {{ error }}
      </div>
      <div>
        <input
          v-model="form.name"
          type="text"
          required
          placeholder="Category name"
          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm"
        />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Color
        </label>
        <div class="flex gap-2">
          <input
            v-model="form.color"
            type="color"
            class="h-10 w-16 rounded border border-gray-300 dark:border-gray-600 cursor-pointer"
          />
          <input
            v-model="form.color"
            type="text"
            pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
            placeholder="#3B82F6"
            class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm"
          />
        </div>
      </div>
      <button
        type="submit"
        :disabled="loading || !form.name.trim()"
        class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm disabled:opacity-50 disabled:cursor-not-allowed"
      >
        {{ loading ? 'Adding...' : 'Add Category' }}
      </button>
    </form>

    <!-- Categories List -->
    <div v-if="loading" class="text-center py-4 text-gray-500 text-sm">
      Loading...
    </div>

    <div v-else-if="categories.length === 0" class="text-center py-4 text-gray-500 text-sm">
      No categories yet
    </div>

    <div v-else class="space-y-2">
      <div
        v-for="category in categories"
        :key="category.id"
        class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50"
      >
        <div class="flex items-center gap-2">
          <span
            class="w-4 h-4 rounded-full"
            :style="{ backgroundColor: category.color }"
          ></span>
          <span class="text-sm font-medium text-gray-900 dark:text-white">
            {{ category.name }}
          </span>
          <span
            v-if="category.todos_count"
            class="text-xs text-gray-500 dark:text-gray-400"
          >
            ({{ category.todos_count }})
          </span>
        </div>
        <button
          @click="emit('delete', category.id)"
          class="text-red-600 hover:text-red-700 dark:text-red-400"
          title="Delete category"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue';

const props = defineProps({
  categories: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  },
  error: {
    type: String,
    default: null
  }
});

const emit = defineEmits(['create', 'delete']);

const form = reactive({
  name: '',
  color: '#3B82F6'
});

const handleSubmit = () => {
  console.log('CategoryManager: Form submitted', form);
  emit('create', { ...form });
  console.log('CategoryManager: Create event emitted');

  // Reset form
  form.name = '';
  form.color = '#3B82F6';
};
</script>

