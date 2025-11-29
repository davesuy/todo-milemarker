<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
        Todos ({{ todos.length }})
      </h2>
    </div>

    <div v-if="loading" class="text-center py-8 text-gray-500">
      Loading todos...
    </div>

    <div v-else-if="todos.length === 0" class="text-center py-8 text-gray-500">
      No todos found. Create your first one above!
    </div>

    <div v-else class="space-y-3">
      <TodoItem
        v-for="todo in todos"
        :key="todo.id"
        :todo="todo"
        @toggle="emit('toggle', todo.id)"
        @update="(data) => emit('update', todo.id, data)"
        @delete="emit('delete', todo.id)"
      />
    </div>
  </div>
</template>

<script setup>
import TodoItem from './TodoItem.vue';

const props = defineProps({
  todos: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['toggle', 'update', 'delete']);
</script>

