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
              <v-btn color="primary" @click="router.visit(`/recipes/${recipe.id}`)">
                Voir la recette
              </v-btn>
            </v-card-actions>
            <v-card-actions>
              <v-btn color="primary" @click="openEditModal(recipe)">
                Modifier
              </v-btn>
            </v-card-actions>
            <v-card-actions>
              <v-btn color="error" @click="deleteRecipe(recipe.id)">
                Supprimer
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

      <!-- Modal de modification -->
      <v-dialog v-model="showEditModal" max-width="600px">
        <v-card>
          <v-card-title class="text-h5">
            Modifier la recette
          </v-card-title>

          <v-card-text>
            <v-form @submit.prevent="submitEditForm">
              <v-text-field v-model="editForm.title" label="Titre" required></v-text-field>

              <v-textarea v-model="editForm.description" label="Description" required></v-textarea>

              <v-textarea v-model="editForm.ingredients" label="Ingrédients" required></v-textarea>
            </v-form>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="grey-darken-1" variant="text" @click="showEditModal = false">
              Annuler
            </v-btn>
            <v-btn color="primary" :loading="isSubmitting" @click="submitEditForm">
              Enregistrer
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
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
const showEditModal = ref(false)
const isSubmitting = ref(false)
const editForm = ref({
  id: 0,
  title: '',
  description: '',
  ingredients: ''
})

const handlePageChange = (page: number) => {
  router.get(`/recipes?page=${page}`)
}

const openEditModal = (recipe: any) => {
  editForm.value = {
    id: recipe.id,
    title: recipe.title,
    description: recipe.description,
    ingredients: recipe.ingredients
  }
  showEditModal.value = true
}

const submitEditForm = () => {
  isSubmitting.value = true

  router.put(`/recipes/${editForm.value.id}`, editForm.value, {
    onSuccess: () => {
      showEditModal.value = false
      isSubmitting.value = false
    },
    onError: (errors) => {
      console.error('Erreur lors de la modification:', errors)
      isSubmitting.value = false
    }
  })
}

const deleteRecipe = (id: number) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette recette ?')) {
    router.delete(`/recipes/${id}`)
  }
}
</script>