<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { 
  fetchRecipes, 
  updateRecipe, 
  deleteRecipe, 
  formatValidationErrors,
  createDebouncedSearch 
} from '@/lib/api'
import type { PaginatedRecipes, CreateRecipeData } from '@/lib/api'
import Layout from '@/components/Layout.vue'

const router = useRouter()

const recipes = ref<PaginatedRecipes>({
  data: [],
  current_page: 1,
  last_page: 1,
  per_page: 10,
  total: 0
})

const message = ref('')
const formErrors = ref<Record<string, string>>({})
const currentPage = ref(1)
const showEditModal = ref(false)
const isSubmitting = ref(false)
const searchQuery = ref('')

const editForm = ref<CreateRecipeData & { id: number }>({
  id: 0,
  title: '',
  description: '',
  ingredients: ''
})

const loadRecipes = async (page = 1, search = '') => {
  try {
    const data = await fetchRecipes(page, search)
    recipes.value = data
    currentPage.value = data.current_page
    message.value = ''
  } catch (error: any) {
    message.value = error.message || 'Erreur lors du chargement des recettes.'
    console.error('Erreur lors du chargement:', error)
  }
}

// Création d'une fonction de recherche avec debouncing
const debouncedSearch = createDebouncedSearch(async (query: string) => {
  currentPage.value = 1
  await loadRecipes(1, query)
}, 500)

const handleSearch = () => {
  debouncedSearch(searchQuery.value)
}

const clearSearch = () => {
  searchQuery.value = ''
  currentPage.value = 1
  loadRecipes(1)
}

const handlePageChange = (page: number) => {
  loadRecipes(page, searchQuery.value)
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
    await updateRecipe(editForm.value.id, {
      title: editForm.value.title,
      description: editForm.value.description,
      ingredients: editForm.value.ingredients
    })
    
    showEditModal.value = false
    await loadRecipes(currentPage.value, searchQuery.value)
    message.value = ''
  } catch (error: any) {
    formErrors.value = formatValidationErrors(error)
    if (!Object.keys(formErrors.value).length) {
      message.value = error.message || 'Erreur lors de la modification.'
    }
  } finally {
    isSubmitting.value = false
  }
}

const confirmDeleteRecipe = async (id: number) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette recette ?')) {
    try {
      await deleteRecipe(id)
      await loadRecipes(currentPage.value, searchQuery.value)
      message.value = ''
    } catch (error: any) {
      message.value = error.message || 'Erreur lors de la suppression.'
      console.error('Erreur lors de la suppression:', error)
    }
  }
}

const navigateToRecipe = (recipeId: number) => {
  router.push(`/recipes/${recipeId}`)
}

onMounted(() => {
  loadRecipes()
})
</script>

<template>
  <Layout>
    <v-container class="py-4 py-md-8">
      <h1 class="text-h4 text-md-h3 mb-6 text-center text-md-left">Nos Recettes</h1>

      <!-- Barre de recherche responsive -->
      <v-row class="mb-6">
        <v-col cols="12" md="8" lg="6">
          <v-text-field
            v-model="searchQuery"
            label="Rechercher par ingrédient"
            prepend-inner-icon="mdi-magnify"
            clearable
            variant="outlined"
            @input="handleSearch"
            @click:clear="clearSearch"
            placeholder="Ex: tomate, fromage, chocolat..."
            density="comfortable"
          ></v-text-field>
        </v-col>
      </v-row>

      <v-alert v-if="message" type="info" class="mb-6">
        {{ message }}
      </v-alert>

      <v-alert v-if="Object.keys(formErrors).length > 0" type="error" class="mb-6">
        <div v-for="(error, field) in formErrors" :key="field" class="mb-1">
          {{ error }}
        </div>
      </v-alert>

      <!-- Grille de recettes responsive -->
      <v-row v-if="recipes.data && recipes.data.length > 0">
        <v-col 
          v-for="recipe in recipes.data" 
          :key="recipe.id" 
          cols="12" 
          sm="6" 
          md="4" 
          lg="3"
        >
          <v-card 
            class="h-100 d-flex flex-column recipe-card" 
            hover 
            elevation="2"
            @click="navigateToRecipe(recipe.id)"
          >
            <v-card-title class="text-h6">{{ recipe.title }}</v-card-title>
            <v-card-text class="flex-grow-1">
              <p class="text-body-2 mb-2">{{ recipe.description }}</p>
              <p class="text-caption text-medium-emphasis">
                <v-icon size="small" class="mr-1">mdi-food-variant</v-icon>
                {{ recipe.ingredients }}
              </p>
            </v-card-text>
            
            <!-- Actions responsive -->
            <v-card-actions class="pt-0" @click.stop>
              <div class="d-flex flex-column flex-sm-row gap-2 w-100">
                <v-btn 
                  color="primary" 
                  :to="`/recipes/${recipe.id}`"
                  size="small"
                  variant="outlined"
                  :block="$vuetify.display.xs"
                >
                  <v-icon left size="small">mdi-eye</v-icon>
                  Voir
                </v-btn>
                <v-btn 
                  color="primary" 
                  @click="openEditModal(recipe)"
                  size="small"
                  :block="$vuetify.display.xs"
                >
                  <v-icon left size="small">mdi-pencil</v-icon>
                  <span class="d-none d-sm-inline">Modifier</span>
                  <span class="d-inline d-sm-none">Éditer</span>
                </v-btn>
                <v-btn 
                  color="error" 
                  @click="confirmDeleteRecipe(recipe.id)"
                  size="small"
                  variant="outlined"
                  :block="$vuetify.display.xs"
                >
                  <v-icon left size="small">mdi-delete</v-icon>
                  <span class="d-none d-sm-inline">Supprimer</span>
                  <span class="d-inline d-sm-none">Sup.</span>
                </v-btn>
              </div>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>

      <v-alert v-else-if="recipes.data && recipes.data.length === 0" type="info" class="mb-6 text-center">
        <v-icon class="mb-2">mdi-information-outline</v-icon>
        <div>Aucune recette trouvée.</div>
        <div v-if="searchQuery" class="text-caption mt-2">
          Essayez un autre terme de recherche
        </div>
      </v-alert>

      <!-- Pagination responsive -->
      <div class="text-center mt-8" v-if="recipes.data && recipes.data.length > 0 && recipes.last_page > 1">
        <v-pagination 
          v-model="currentPage" 
          :length="recipes.last_page"
          :total-visible="$vuetify.display.mobile ? 5 : 7"
          @update:model-value="handlePageChange"
          size="small"
        ></v-pagination>
      </div>

      <!-- Modal de modification responsive -->
      <v-dialog 
        v-model="showEditModal" 
        :max-width="$vuetify.display.mobile ? '95%' : '600px'" 
        :fullscreen="$vuetify.display.xs"
      >
        <v-card>
          <v-card-title class="text-h5 d-flex align-center">
            Modifier la recette
            <v-spacer></v-spacer>
            <v-btn 
              v-if="$vuetify.display.mobile" 
              icon="mdi-close" 
              variant="text" 
              size="small"
              @click="closeModal"
            ></v-btn>
          </v-card-title>

          <v-card-text class="pb-2">
            <v-form @submit.prevent="submitEditForm">
              <v-text-field 
                v-model="editForm.title" 
                label="Titre" 
                required
                :error-messages="formErrors.title"
                @input="clearError('title')"
                density="comfortable"
              ></v-text-field>

              <v-textarea 
                v-model="editForm.description" 
                label="Description" 
                required
                :error-messages="formErrors.description"
                @input="clearError('description')"
                rows="3"
                density="comfortable"
              ></v-textarea>

              <v-textarea 
                v-model="editForm.ingredients" 
                label="Ingrédients" 
                required
                :error-messages="formErrors.ingredients"
                @input="clearError('ingredients')"
                rows="3"
                density="comfortable"
              ></v-textarea>
            </v-form>
          </v-card-text>

          <v-card-actions class="px-6 pb-4">
            <v-spacer v-if="!$vuetify.display.mobile"></v-spacer>
            <v-btn 
              color="grey-darken-1" 
              variant="text" 
              @click="closeModal"
              :block="$vuetify.display.mobile"
              class="mb-2 mb-sm-0"
            >
              Annuler
            </v-btn>
            <v-btn 
              color="primary" 
              :loading="isSubmitting" 
              @click="submitEditForm"
              :block="$vuetify.display.mobile"
            >
              Enregistrer
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </Layout>
</template>

<style scoped>
.recipe-card {
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.recipe-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

@media (max-width: 600px) {
  .recipe-card:hover {
    transform: none;
  }
}
</style>