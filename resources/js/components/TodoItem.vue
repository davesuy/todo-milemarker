<template>
  <div
    :class="[
      'border-l-4 p-4 rounded-lg transition-all',
      todo.is_completed
        ? 'bg-gray-50 dark:bg-gray-700/50 border-gray-400'
        : isOverdue
        ? 'bg-red-50 dark:bg-red-900/20 border-red-500'
        : 'bg-white dark:bg-gray-700 border-blue-500',
      todo.category?.color ? `border-[${todo.category.color}]` : ''
    ]"
  >
    <div v-if="!editing" class="flex items-start gap-3">
      <!-- Checkbox -->
      <button
        @click="emit('toggle')"
        :class="[
          'mt-1 w-5 h-5 rounded border-2 flex items-center justify-center transition-colors',
          todo.is_completed
            ? 'bg-blue-600 border-blue-600'
            : 'border-gray-300 hover:border-blue-500'
        ]"
      >
        <svg
          v-if="todo.is_completed"
          class="w-3 h-3 text-white"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
        </svg>
      </button>

      <!-- Content -->
      <div class="flex-1 min-w-0">
        <div class="flex items-start justify-between gap-2">
          <h3
            :class="[
              'text-lg font-medium',
              todo.is_completed
                ? 'line-through text-gray-500 dark:text-gray-400'
                : 'text-gray-900 dark:text-white'
            ]"
          >
            {{ todo.title }}
          </h3>
          <div class="flex gap-2">
            <button
              @click="startEdit"
              class="text-gray-400 hover:text-blue-600 transition-colors"
              title="Edit"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
            </button>
            <button
              @click="emit('delete')"
              class="text-gray-400 hover:text-red-600 transition-colors"
              title="Delete"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </div>
        </div>

        <p
          v-if="todo.description"
          :class="[
            'mt-1 text-sm',
            todo.is_completed
              ? 'text-gray-400 dark:text-gray-500'
              : 'text-gray-600 dark:text-gray-300'
          ]"
        >
          {{ todo.description }}
        </p>

        <div class="flex flex-wrap gap-2 mt-2">
          <span
            v-if="todo.category"
            :style="{ backgroundColor: todo.category.color + '20', color: todo.category.color }"
            class="px-2 py-1 text-xs font-medium rounded-full"
          >
            {{ todo.category.name }}
          </span>

          <span
            v-if="todo.due_date"
            :class="[
              'px-2 py-1 text-xs font-medium rounded-full',
              isOverdue && !todo.is_completed
                ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'
                : 'bg-gray-100 text-gray-700 dark:bg-gray-600 dark:text-gray-200'
            ]"
          >
            📅 {{ formatDate(todo.due_date) }}
          </span>

          <span
            v-if="todo.completed_at"
            class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300"
          >
            ✓ {{ formatDate(todo.completed_at) }}
          </span>
        </div>
      </div>
    </div>

    <!-- Edit Form -->
    <div v-else class="space-y-3">
      <input
        v-model="editForm.title"
        type="text"
        required
        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
      />
      <textarea
        v-model="editForm.description"
        rows="2"
        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
      ></textarea>
      <div class="flex gap-2">
        <button
          @click="saveEdit"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
        >
          Save
        </button>
        <button
          @click="cancelEdit"
          class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500"
        >
          Cancel
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive } from 'vue';

const props = defineProps({
  todo: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['toggle', 'update', 'delete']);

const editing = ref(false);
const editForm = reactive({
  title: '',
  description: ''
});

const isOverdue = computed(() => {
  if (!props.todo.due_date || props.todo.is_completed) return false;
  return new Date(props.todo.due_date) < new Date();
});

const formatDate = (dateString) => {
  const date = new Date(dateString);
  const now = new Date();
  const diff = date - now;
  const days = Math.floor(diff / (1000 * 60 * 60 * 24));

  if (days === 0) {
    return 'Today ' + date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
  } else if (days === 1) {
    return 'Tomorrow';
  } else if (days === -1) {
    return 'Yesterday';
  } else if (days > 1 && days < 7) {
    return `In ${days} days`;
  } else if (days < -1 && days > -7) {
    return `${Math.abs(days)} days ago`;
  }

  return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};

const startEdit = () => {
  editForm.title = props.todo.title;
  editForm.description = props.todo.description || '';
  editing.value = true;
};

const saveEdit = () => {
  emit('update', editForm);
  editing.value = false;
};

const cancelEdit = () => {
  editing.value = false;
};
</script>

