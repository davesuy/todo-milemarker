import { ref } from 'vue';
import axios from 'axios';

export function useTodos() {
    const todos = ref([]);
    const loading = ref(false);
    const error = ref(null);

    const fetchTodos = async (filters = {}) => {
        loading.value = true;
        error.value = null;
        try {
            const params = new URLSearchParams(filters);
            const response = await axios.get(`/api/todos?${params}`);
            todos.value = response.data;
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to fetch todos';
            console.error('Fetch todos error:', err);
        } finally {
            loading.value = false;
        }
    };

    const createTodo = async (todoData) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await axios.post('/api/todos', todoData);
            todos.value.unshift(response.data);
            return response.data;
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to create todo';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const updateTodo = async (id, todoData) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await axios.put(`/api/todos/${id}`, todoData);
            const index = todos.value.findIndex(t => t.id === id);
            if (index !== -1) {
                todos.value[index] = response.data;
            }
            return response.data;
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to update todo';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const deleteTodo = async (id) => {
        loading.value = true;
        error.value = null;
        try {
            await axios.delete(`/api/todos/${id}`);
            todos.value = todos.value.filter(t => t.id !== id);
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to delete todo';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const toggleTodo = async (id) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await axios.post(`/api/todos/${id}/toggle`);
            const index = todos.value.findIndex(t => t.id === id);
            if (index !== -1) {
                todos.value[index] = response.data;
            }
            return response.data;
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to toggle todo';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    return {
        todos,
        loading,
        error,
        fetchTodos,
        createTodo,
        updateTodo,
        deleteTodo,
        toggleTodo
    };
}

