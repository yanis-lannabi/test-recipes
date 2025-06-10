<template>
  <Layout>
    <v-container>
      <h1 class="text-h3 mb-6">Nos Recettes</h1>

      <v-alert v-if="message" type="info" class="mb-6">
        {{ message }}
      </v-alert>

      <v-row v-if="recipes.data && recipes.data.length > 0">
        <v-col v-for="recipe in recipes.data" :key="recipe.id" cols="12" md="4">
          <v-card>
            <v-card-title>{{ recipe.title }}</v-card-title>
            <v-card-text>
              <p>{{ recipe.description }}</p>
              <p class="text-caption">Ingrédients: {{ recipe.ingredients }}</p>
            </v-card-text>
            <v-card-actions>
              <v-btn color="primary" :to="`/recipes/${recipe.id}`">
                Voir la recette
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>

      <!-- Pagination -->
      <div class="text-center mt-6" v-if="recipes.data && recipes.data.length > 0">
        <v-pagination v-model="currentPage" :length="recipes.last_page"
          @update:model-value="handlePageChange"></v-pagination>
      </div>
    </v-container>
  </Layout>
</template>

<script setup lang="ts">
import { defineProps, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Layout from '@/components/Layout.vue'

const props = defineProps<{
  recipes: {
    data: Array<{
      id: number
      title: string
      description: string
      ingredients: string
    }>
    current_page: number
    last_page: number
  }
  message?: string
}>()

const currentPage = ref(props.recipes.current_page)

const handlePageChange = (page: number) => {
  router.get(`/recipes?page=${page}`)
}
</script>