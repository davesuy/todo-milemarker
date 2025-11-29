import './bootstrap';
import { createApp } from 'vue';
import TodoApp from './components/TodoApp.vue';

const app = createApp(TodoApp);
app.mount('#app');

