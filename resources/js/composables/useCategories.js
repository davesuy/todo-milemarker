import { ref } from 'vue';
import axios from 'axios';

export function useCategories() {
    const categories = ref([]);
    const loading = ref(false);
    const error = ref(null);

    const fetchCategories = async () => {
        loading.value = true;
        error.value = null;
        try {
            const response = await axios.get('/api/categories');
            categories.value = response.data;
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to fetch categories';
            console.error('Fetch categories error:', err);
        } finally {
            loading.value = false;
        }
    };

    const createCategory = async (categoryData) => {
        loading.value = true;
        error.value = null;
        try {
            console.log('useCategories: Creating category with data:', categoryData);
            const response = await axios.post('/api/categories', categoryData);
            console.log('useCategories: Category created:', response.data);
            categories.value.push(response.data);
            return response.data;
        } catch (err) {
            console.error('useCategories: Create category error:', err);
            console.error('useCategories: Error response:', err.response?.data);
            error.value = err.response?.data?.message || 'Failed to create category';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const updateCategory = async (id, categoryData) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await axios.put(`/api/categories/${id}`, categoryData);
            const index = categories.value.findIndex(c => c.id === id);
            if (index !== -1) {
                categories.value[index] = response.data;
            }
            return response.data;
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to update category';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    const deleteCategory = async (id) => {
        loading.value = true;
        error.value = null;
        try {
            await axios.delete(`/api/categories/${id}`);
            categories.value = categories.value.filter(c => c.id !== id);
        } catch (err) {
            error.value = err.response?.data?.message || 'Failed to delete category';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    return {
        categories,
        loading,
        error,
        fetchCategories,
        createCategory,
        updateCategory,
        deleteCategory
    };
}

