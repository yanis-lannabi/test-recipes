<template>
  <Layout>
    <v-container>
      <h1 class="text-h3 mb-6">Nos Recettes</h1>

      <v-alert v-if="message" type="info" class="mb-6">
        {{ message }}
      </v-alert>

      <v-alert v-if="Object.keys(formErrors).length > 0" type="error" class="mb-6">
        <div v-for="(error, field) in formErrors" :key="field" class="mb-1">
          {{ error }}
        </div>
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

      <v-alert v-else-if="recipes.data && recipes.data.length === 0" type="info" class="mb-6">
        Aucune recette trouvée.
      </v-alert>

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
              <v-text-field 
                v-model="editForm.title" 
                label="Titre" 
                required
                :error-messages="formErrors.title"
                @input="clearError('title')"
              ></v-text-field>

              <v-textarea 
                v-model="editForm.description" 
                label="Description" 
                required
                :error-messages="formErrors.description"
                @input="clearError('description')"
              ></v-textarea>

              <v-textarea 
                v-model="editForm.ingredients" 
                label="Ingrédients" 
                required
                :error-messages="formErrors.ingredients"
                @input="clearError('ingredients')"
              ></v-textarea>
            </v-form>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="grey-darken-1" variant="text" @click="closeModal">
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
import { ref, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Layout from '@/components/Layout.vue'

const router = useRouter()

const recipes = ref({
  data: [],
  current_page: 1,
  last_page: 1
})
const message = ref('')
const formErrors = ref<Record<string, string>>({})
const currentPage = ref(1)
const showEditModal = ref(false)
const isSubmitting = ref(false)
const editForm = ref({
  id: 0,
  title: '',
  description: '',
  ingredients: ''
})

const fetchRecipes = async (page = 1) => {
  try {
    const res = await axios.get(`/api/recipes?page=${page}`)
    recipes.value = res.data
    currentPage.value = res.data.current_page
  } catch (err: any) {
    message.value = 'Erreur lors du chargement des recettes.'
  }
}

onMounted(() => {
  fetchRecipes()
})

const handlePageChange = (page: number) => {
  fetchRecipes(page)
}

const openEditModal = (recipe: any) => {
  editForm.value = {
    id: recipe.id,
    title: recipe.title,
    description: recipe.description,
    ingredients: recipe.ingredients
  }
  showEditModal.value = true
  formErrors.value = {}
}

const closeModal = () => {
  showEditModal.value = false
  formErrors.value = {}
}

const clearError = (field: string) => {
  if (formErrors.value[field]) {
    delete formErrors.value[field]
  }
}

const submitEditForm = async () => {
  isSubmitting.value = true
  formErrors.value = {}
  try {
    await axios.put(`/api/recipes/${editForm.value.id}`, editForm.value)
    showEditModal.value = false
    isSubmitting.value = false
    fetchRecipes(currentPage.value)
  } catch (err: any) {
    if (err.response && err.response.data && err.response.data.errors) {
      formErrors.value = err.response.data.errors
    } else {
      message.value = 'Erreur lors de la modification.'
    }
    isSubmitting.value = false
  }
}

const deleteRecipe = async (id: number) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette recette ?')) {
    try {
      await axios.delete(`/api/recipes/${id}`)
      fetchRecipes(currentPage.value)
    } catch (err) {
      message.value = 'Erreur lors de la suppression.'
    }
  }
}
</script>

<style scoped>
.v-alert {
  margin-bottom: 1rem;
}
</style>