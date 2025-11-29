import { ref } from 'vue';
import axios from 'axios';

export function useAuth() {
    const user = ref(null);
    const token = ref(localStorage.getItem('token'));
    const loading = ref(false);
    const error = ref(null);

    // Set axios default headers if token exists
    if (token.value) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
    }

    const register = async (name, email, password, password_confirmation) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await axios.post('/api/register', {
                name,
                email,
                password,
                password_confirmation
            });
            token.value = response.data.token;
            user.value = response.data.user;
            localStorage.setItem('token', token.value);
            axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
            return true;
        } catch (err) {
            error.value = err.response?.data?.message || 'Registration failed';
            return false;
        } finally {
            loading.value = false;
        }
    };

    const login = async (email, password) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await axios.post('/api/login', { email, password });
            token.value = response.data.token;
            user.value = response.data.user;
            localStorage.setItem('token', token.value);
            axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
            return true;
        } catch (err) {
            error.value = err.response?.data?.message || 'Login failed';
            return false;
        } finally {
            loading.value = false;
        }
    };

    const logout = async () => {
        loading.value = true;
        try {
            await axios.post('/api/logout');
        } catch (err) {
            console.error('Logout error:', err);
        } finally {
            user.value = null;
            token.value = null;
            localStorage.removeItem('token');
            delete axios.defaults.headers.common['Authorization'];
            loading.value = false;
        }
    };

    const getUser = async () => {
        if (!token.value) return false;

        loading.value = true;
        try {
            const response = await axios.get('/api/user');
            user.value = response.data;
            return true;
        } catch (err) {
            // Token is invalid
            token.value = null;
            localStorage.removeItem('token');
            delete axios.defaults.headers.common['Authorization'];
            return false;
        } finally {
            loading.value = false;
        }
    };

    const isAuthenticated = () => {
        return !!token.value;
    };

    return {
        user,
        token,
        loading,
        error,
        register,
        login,
        logout,
        getUser,
        isAuthenticated
    };
}

