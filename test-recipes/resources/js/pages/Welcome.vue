<script setup lang="ts">
import Layout from '@/components/Layout.vue'
</script>

<template>
    <Layout>
        <v-container fluid class="pa-0">
            <v-row no-gutters>
                <v-col cols="12" md="6" class="d-flex align-center">
                    <v-container>
                        <h1 class="text-h2 font-weight-bold mb-4">
                            Découvrez des recettes exceptionnelles
                        </h1>
                        <p class="text-h6 text-medium-emphasis mb-6">
                            Explorez notre collection de recettes et inspirez-vous des meilleurs chefs.
                        </p>
                        <v-btn size="large" color="primary" class="mr-4" href="/recipes">
                            Explorer les recettes
                        </v-btn>
                    </v-container>
                </v-col>
                <v-col cols="12" md="6" class="d-none d-md-flex">
                    <v-img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836" height="100%"
                        cover></v-img>
                </v-col>
            </v-row>
        </v-container>

        <v-container class="py-16">
            <h2 class="text-h3 text-center mb-12">Pourquoi nous choisir ?</h2>
            <v-row>
                <v-col cols="12" md="4">
                    <v-card class="h-100" elevation="0">
                        <v-card-item>
                            <template v-slot:prepend>
                                <v-icon size="large" color="primary" icon="mdi-food"></v-icon>
                            </template>
                            <v-card-title>Recettes Variées</v-card-title>
                            <v-card-text>
                                Des milliers de recettes pour tous les goûts et toutes les occasions.
                            </v-card-text>
                        </v-card-item>
                    </v-card>
                </v-col>
                <v-col cols="12" md="4">
                    <v-card class="h-100" elevation="0">
                        <v-card-item>
                            <template v-slot:prepend>
                                <v-icon size="large" color="primary" icon="mdi-account-group"></v-icon>
                            </template>
                            <v-card-title>Recettes de Qualité</v-card-title>
                            <v-card-text>
                                Des recettes soigneusement sélectionnées pour vous garantir le meilleur.
                            </v-card-text>
                        </v-card-item>
                    </v-card>
                </v-col>
                <v-col cols="12" md="4">
                    <v-card class="h-100" elevation="0">
                        <v-card-item>
                            <template v-slot:prepend>
                                <v-icon size="large" color="primary" icon="mdi-star"></v-icon>
                            </template>
                            <v-card-title>Qualité Premium</v-card-title>
                            <v-card-text>
                                Des recettes testées et approuvées par notre équipe d'experts.
                            </v-card-text>
                        </v-card-item>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </Layout>
</template>

<style scoped>
.v-main {
    background-color: #ffffff;
}
</style>
