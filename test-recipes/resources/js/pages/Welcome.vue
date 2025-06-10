<script setup lang="ts">
import Layout from '@/components/Layout.vue'

const features = [
    {
        icon: 'mdi-food',
        title: 'Recettes Variées',
        description: 'Des milliers de recettes pour tous les goûts et toutes les occasions.'
    },
    {
        icon: 'mdi-account-group',
        title: 'Recettes de Qualité',
        description: 'Des recettes soigneusement sélectionnées pour vous garantir le meilleur.'
    },
    {
        icon: 'mdi-star',
        title: 'Qualité Premium',
        description: 'Des recettes testées et approuvées par notre équipe d\'experts.'
    }
]
</script>

<template>
    <Layout>
        <v-container fluid class="pa-0">
            <!-- Section hero responsive -->
            <v-row no-gutters>
                <v-col cols="12" md="6" class="d-flex align-center order-2 order-md-1">
                    <v-container class="py-8 py-md-12">
                        <h1 class="text-h4 text-sm-h3 text-md-h2 font-weight-bold mb-4 text-center text-md-left">
                            Découvrez des recettes exceptionnelles
                        </h1>
                        <p class="text-body-1 text-sm-h6 text-medium-emphasis mb-6 text-center text-md-left">
                            Explorez notre collection de recettes et inspirez-vous des meilleurs chefs.
                        </p>
                        <div class="d-flex flex-column flex-sm-row gap-4 justify-center justify-md-start">
                            <v-btn 
                                size="large" 
                                color="primary" 
                                :to="{ name: 'recipes' }"
                                :block="$vuetify.display.xs"
                            >
                                <v-icon left>mdi-book-open-page-variant</v-icon>
                                Explorer les recettes
                            </v-btn>
                        </div>
                    </v-container>
                </v-col>
                <v-col cols="12" md="6" class="order-1 order-md-2">
                    <v-img 
                        src="https://images.unsplash.com/photo-1504674900247-0877df9cc836" 
                        :height="$vuetify.display.mobile ? '250px' : '100vh'"
                        cover
                        class="d-flex align-center justify-center"
                    >
                        <!-- Overlay pour mobile -->
                        <div v-if="$vuetify.display.mobile" class="overlay"></div>
                    </v-img>
                </v-col>
            </v-row>
        </v-container>

        <!-- Section fonctionnalités responsive -->
        <v-container class="py-8 py-md-16">
            <h2 class="text-h4 text-md-h3 text-center mb-8 mb-md-12">Pourquoi nous choisir ?</h2>
            <v-row>
                <v-col cols="12" sm="6" md="4" v-for="feature in features" :key="feature.title">
                    <v-card class="h-100 text-center pa-4" elevation="2" hover>
                        <v-card-item>
                            <div class="mb-4">
                                <v-icon :icon="feature.icon" size="x-large" color="primary"></v-icon>
                            </div>
                            <v-card-title class="text-h6 mb-3">{{ feature.title }}</v-card-title>
                            <v-card-text class="text-body-2">
                                {{ feature.description }}
                            </v-card-text>
                        </v-card-item>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </Layout>
</template>

<style scoped>
.overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    z-index: 1;
}

.v-main {
    background-color: #ffffff;
}
</style>
