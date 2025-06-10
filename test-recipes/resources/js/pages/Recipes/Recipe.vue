<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import Layout from '@/components/Layout.vue'
import type { Recipe } from '@/types'

const route = useRoute()
const recipe = ref<Recipe | null>(null)
const loading = ref(true)
const error = ref('')

const fetchRecipe = async () => {
  try {
    loading.value = true
    const res = await axios.get(`/api/recipes/${route.params.id}`)
    recipe.value = res.data
  } catch (err: any) {
    error.value = 'Erreur lors du chargement de la recette.'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchRecipe()
})
</script>

<template>
  <Layout>
    <v-container class="py-8">
      <v-row>
        <v-col cols="12">
          <div v-if="loading" class="text-center">
            <v-progress-circular indeterminate color="primary"></v-progress-circular>
          </div>
          <v-alert v-else-if="error" type="error">{{ error }}</v-alert>
          <div v-else-if="recipe">
            <h1 class="text-h3 font-weight-bold mb-4">{{ recipe.title }}</h1>
            <p class="text-body-1 mb-4">{{ recipe.description }}</p>
            <p class="text-subtitle-1 text-medium-emphasis">Ingrédients : {{ recipe.ingredients }}</p>
          </div>
        </v-col>
      </v-row>
    </v-container>
  </Layout>
</template>
