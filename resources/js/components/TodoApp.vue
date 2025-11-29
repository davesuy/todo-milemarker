<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Authentication Forms -->
    <div v-if="!auth.isAuthenticated()" class="min-h-screen flex items-center justify-center p-4">
      <div class="max-w-md w-full bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-8">
          📝 Todo App
        </h1>

        <!-- Login/Register Toggle -->
        <div class="flex border-b border-gray-200 dark:border-gray-700 mb-6">
          <button
            @click="showLogin = true"
            :class="[
              'flex-1 py-3 text-sm font-medium',
              showLogin
                ? 'text-blue-600 border-b-2 border-blue-600'
                : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'
            ]"
          >
            Login
          </button>
          <button
            @click="showLogin = false"
            :class="[
              'flex-1 py-3 text-sm font-medium',
              !showLogin
                ? 'text-blue-600 border-b-2 border-blue-600'
                : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'
            ]"
          >
            Register
          </button>
        </div>

        <!-- Login Form -->
        <form v-if="showLogin" @submit.prevent="handleLogin" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Email
            </label>
            <input
              v-model="loginForm.email"
              type="email"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Password
            </label>
            <input
              v-model="loginForm.password"
              type="password"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
            />
          </div>
          <div v-if="auth.error.value" class="text-red-600 text-sm">
            {{ auth.error.value }}
          </div>
          <button
            type="submit"
            :disabled="auth.loading.value"
            class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 disabled:opacity-50"
          >
            {{ auth.loading.value ? 'Loading...' : 'Login' }}
          </button>
        </form>

        <!-- Register Form -->
        <form v-else @submit.prevent="handleRegister" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Name
            </label>
            <input
              v-model="registerForm.name"
              type="text"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Email
            </label>
            <input
              v-model="registerForm.email"
              type="email"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Password
            </label>
            <input
              v-model="registerForm.password"
              type="password"
              required
              minlength="8"
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
              Confirm Password
            </label>
            <input
              v-model="registerForm.password_confirmation"
              type="password"
              required
              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
            />
          </div>
          <div v-if="auth.error.value" class="text-red-600 text-sm">
            {{ auth.error.value }}
          </div>
          <button
            type="submit"
            :disabled="auth.loading.value"
            class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 disabled:opacity-50"
          >
            {{ auth.loading.value ? 'Loading...' : 'Register' }}
          </button>
        </form>
      </div>
    </div>

    <!-- Main App -->
    <div v-else class="container mx-auto px-4 py-8 max-w-6xl">
      <!-- Header -->
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">
          📝 My Todos
        </h1>
        <div class="flex items-center gap-4">
          <span class="text-gray-600 dark:text-gray-300">
            {{ auth.user.value?.name }}
          </span>
          <button
            @click="auth.logout"
            class="px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg"
          >
            Logout
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
          <CategoryManager
            :categories="categoriesData.categories.value"
            :loading="categoriesData.loading.value"
            :error="categoriesData.error.value"
            @create="handleCreateCategory"
            @delete="handleDeleteCategory"
          />
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3 space-y-6">
          <!-- Todo Form -->
          <TodoForm
            :categories="categoriesData.categories.value"
            @create="handleCreateTodo"
          />

          <!-- Filter Bar -->
          <FilterBar
            :categories="categoriesData.categories.value"
            v-model:filters="filters"
            @filter="loadTodos"
          />

          <!-- Todo List -->
          <TodoList
            :todos="todosData.todos.value"
            :loading="todosData.loading.value"
            @toggle="handleToggleTodo"
            @update="handleUpdateTodo"
            @delete="handleDeleteTodo"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue';
import { useAuth } from '../composables/useAuth';
import { useTodos } from '../composables/useTodos';
import { useCategories } from '../composables/useCategories';
import TodoForm from './TodoForm.vue';
import TodoList from './TodoList.vue';
import FilterBar from './FilterBar.vue';
import CategoryManager from './CategoryManager.vue';

const auth = useAuth();
const todosData = useTodos();
const categoriesData = useCategories();

const showLogin = ref(true);
const loginForm = reactive({
  email: '',
  password: ''
});

const registerForm = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
});

const filters = ref({
  search: '',
  category_id: '',
  status: '',
  sort_by: 'created_at',
  sort_order: 'desc'
});

const handleLogin = async () => {
  const success = await auth.login(loginForm.email, loginForm.password);
  if (success) {
    await loadData();
  }
};

const handleRegister = async () => {
  const success = await auth.register(
    registerForm.name,
    registerForm.email,
    registerForm.password,
    registerForm.password_confirmation
  );
  if (success) {
    await loadData();
  }
};

const loadTodos = async () => {
  const activeFilters = {};
  if (filters.value.search) activeFilters.search = filters.value.search;
  if (filters.value.category_id) activeFilters.category_id = filters.value.category_id;
  if (filters.value.status) activeFilters.status = filters.value.status;
  activeFilters.sort_by = filters.value.sort_by;
  activeFilters.sort_order = filters.value.sort_order;

  await todosData.fetchTodos(activeFilters);
};

const loadData = async () => {
  await Promise.all([
    categoriesData.fetchCategories(),
    loadTodos()
  ]);
};

const handleCreateTodo = async (todoData) => {
  await todosData.createTodo(todoData);
};

const handleToggleTodo = async (id) => {
  await todosData.toggleTodo(id);
};

const handleUpdateTodo = async (id, todoData) => {
  await todosData.updateTodo(id, todoData);
};

const handleDeleteTodo = async (id) => {
  if (confirm('Are you sure you want to delete this todo?')) {
    await todosData.deleteTodo(id);
  }
};

const handleCreateCategory = async (categoryData) => {
  try {
    console.log('Creating category:', categoryData);
    await categoriesData.createCategory(categoryData);
    console.log('Category created successfully');
  } catch (err) {
    console.error('Failed to create category:', err);
    alert('Failed to create category: ' + (err.response?.data?.message || err.message));
  }
};

const handleDeleteCategory = async (id) => {
  if (confirm('Are you sure you want to delete this category?')) {
    await categoriesData.deleteCategory(id);
  }
};

onMounted(async () => {
  if (auth.isAuthenticated()) {
    const success = await auth.getUser();
    if (success) {
      await loadData();
    }
  }
});
</script>

