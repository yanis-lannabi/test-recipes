import '../css/app.css';

import { createApp } from 'vue';
import { createRouter, createWebHistory, RouterView } from 'vue-router';
import axios from 'axios';
import { initializeTheme } from './composables/useAppearance';
import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import { aliases, mdi } from 'vuetify/iconsets/mdi';
import '@mdi/font/css/materialdesignicons.css';

// Import des composants
import Welcome from './pages/Welcome.vue';
import RecipeIndex from './pages/Recipes/Index.vue';
import RecipeShow from './pages/Recipes/Recipe.vue';
import NotFound from './pages/404.vue';

// Configuration Vuetify optimisée pour le responsive
const vuetify = createVuetify({
    components,
    directives,
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        },
    },
    display: {
        mobileBreakpoint: 'sm',
        thresholds: {
            xs: 0,
            sm: 600,
            md: 960,
            lg: 1280,
            xl: 1920,
        },
    },
    defaults: {
        global: {
            ripple: false,
        },
        VBtn: {
            style: 'text-transform: none;',
        },
        VCard: {
            flat: false,
            elevation: 1,
        },
    },
    theme: {
        defaultTheme: 'light',
        themes: {
            light: {
                colors: {
                    primary: '#FF6B35',
                    secondary: '#2E86AB',
                    accent: '#F24236',
                    error: '#F24236',
                    warning: '#FFA726',
                    info: '#42A5F5',
                    success: '#66BB6A'
                }
            }
        }
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
        { path: '/:pathMatch(.*)*', name: 'not-found', component: NotFound },
    ],
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { top: 0 }
        }
    }
});

const app = createApp(RouterView);
app.use(router);
app.use(vuetify);
app.config.globalProperties.$http = axios;
app.mount('#app');

// This will set light / dark mode on page load...
initializeTheme();
