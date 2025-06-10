import '../css/app.css';

import { createApp } from 'vue';
import { createRouter, createWebHistory, RouterView } from 'vue-router';
import axios from 'axios';
import { initializeTheme } from './composables/useAppearance';
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import '@mdi/font/css/materialdesignicons.css';

// Import des composants
import Welcome from './pages/Welcome.vue';
import RecipeIndex from './pages/Recipes/Index.vue';
import RecipeShow from './pages/Recipes/Recipe.vue';
import NotFound from './pages/404.vue';

const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: 'light'
    }
});

// Configuration axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';

// Configuration du routeur
const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', name: 'home', component: Welcome },
        { path: '/recipes', name: 'recipes', component: RecipeIndex },
        { path: '/recipes/:id', name: 'recipe', component: RecipeShow, props: true },
        { path: '/:pathMatch(.*)*', name: 'not-found', component: NotFound }
    ]
});

const app = createApp(RouterView);
app.use(router);
app.use(vuetify);
app.config.globalProperties.$http = axios;
app.mount('#app');

// This will set light / dark mode on page load...
initializeTheme();
