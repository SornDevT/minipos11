import './bootstrap';

import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import SideMenu from './components/SideMenu.vue';
import Pagination from "./components/Pagination.vue";
import { createPinia } from 'pinia';
const pinia = createPinia();


const app = createApp(App);
app.component('SideMenu',SideMenu);
app.component('Pagination',Pagination);
app.use(pinia);
app.use(router);
app.mount('#app-vue');