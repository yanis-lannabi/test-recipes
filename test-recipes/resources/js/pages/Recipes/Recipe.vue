<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { 
  fetchRecipe, 
  updateRecipe, 
  deleteRecipe, 
  formatValidationErrors 
} from '@/lib/api'
import type { Recipe } from '@/types'
import type { CreateRecipeData } from '@/lib/api'
import Layout from '@/components/Layout.vue'

const route = useRoute()
const router = useRouter()
const recipe = ref<Recipe | null>(null)
const loading = ref(true)
const error = ref('')

// État pour l'édition
const showEditModal = ref(false)
const isSubmitting = ref(false)
const formErrors = ref<Record<string, string>>({})
const editForm = ref<CreateRecipeData>({
  title: '',
  description: '',
  ingredients: ''
})

// État pour la suppression
const showDeleteDialog = ref(false)
const isDeleting = ref(false)

const loadRecipe = async () => {
  try {
    loading.value = true
    error.value = ''
    const data = await fetchRecipe(route.params.id as string)
    recipe.value = data
  } catch (err: any) {
    error.value = err.message || 'Erreur lors du chargement de la recette.'
    console.error('Erreur lors du chargement:', err)
  } finally {
    loading.value = false
  }
}

const goBack = () => {
  router.push({ name: 'recipes' })
}

// Fonctions pour l'édition
const openEditModal = () => {
  if (recipe.value) {
    editForm.value = {
      title: recipe.value.title,
      description: recipe.value.description,
      ingredients: recipe.value.ingredients
    }
    formErrors.value = {}
    showEditModal.value = true
  }
}

const closeEditModal = () => {
  showEditModal.value = false
  formErrors.value = {}
  editForm.value = {
    title: '',
    description: '',
    ingredients: ''
  }
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
    const updatedRecipe = await updateRecipe(route.params.id as string, editForm.value)
    recipe.value = updatedRecipe
    closeEditModal()
  } catch (error: any) {
    formErrors.value = formatValidationErrors(error)
    console.error('Erreur lors de la modification:', error)
  } finally {
    isSubmitting.value = false
  }
}

// Fonctions pour la suppression
const confirmDelete = () => {
  showDeleteDialog.value = true
}

const performDelete = async () => {
  isDeleting.value = true
  
  try {
    await deleteRecipe(route.params.id as string)
    router.push({ name: 'recipes' })
  } catch (err: any) {
    console.error('Erreur lors de la suppression:', err)
    error.value = err.message || 'Erreur lors de la suppression de la recette.'
  } finally {
    isDeleting.value = false
    showDeleteDialog.value = false
  }
}

onMounted(() => {
  loadRecipe()
})
</script>

<template>
  <Layout>
    <v-container class="py-4 py-md-8">
      <!-- Bouton retour responsive -->
      <v-row class="mb-4">
        <v-col cols="12">
          <v-btn 
            @click="goBack"
            color="primary"
            variant="outlined"
            size="small"
            class="mb-4"
          >
            <v-icon left>mdi-arrow-left</v-icon>
            <span class="d-none d-sm-inline">Retour aux recettes</span>
            <span class="d-inline d-sm-none">Retour</span>
          </v-btn>
        </v-col>
      </v-row>

      <v-row justify="center">
        <v-col cols="12" md="10" lg="8">
          <!-- Loading state responsive -->
          <div v-if="loading" class="text-center py-8 py-md-16">
            <v-progress-circular 
              indeterminate 
              color="primary" 
              :size="$vuetify.display.mobile ? 60 : 80"
            ></v-progress-circular>
            <p class="text-h6 mt-4">Chargement de la recette...</p>
          </div>

          <!-- Error state responsive -->
          <v-alert v-else-if="error" type="error" class="text-center">
            <v-icon class="mb-2">mdi-alert-circle</v-icon>
            <div class="text-h6 mb-2">Oups !</div>
            <div>{{ error }}</div>
            <v-btn 
              class="mt-4" 
              color="error" 
              variant="outlined" 
              @click="loadRecipe"
            >
              <v-icon left>mdi-refresh</v-icon>
              Réessayer
            </v-btn>
          </v-alert>

          <!-- Recipe content responsive -->
          <v-card v-else-if="recipe" class="pa-4 pa-md-8" elevation="3">
            <article>
              <!-- Title responsive -->
              <header class="mb-6">
                <h1 class="text-h4 text-md-h3 font-weight-bold mb-4 text-center text-md-left">
                  {{ recipe.title }}
                </h1>
              </header>

              <!-- Description section -->
              <section class="mb-8">
                <v-card class="pa-4 bg-grey-lighten-5" flat>
                  <h2 class="text-h6 text-md-h5 font-weight-medium mb-3 d-flex align-center">
                    <v-icon class="mr-2" color="primary">mdi-text-box-outline</v-icon>
                    Description
                  </h2>
                  <p class="text-body-1 text-md-h6 mb-0 font-weight-regular">
                    {{ recipe.description }}
                  </p>
                </v-card>
              </section>

              <!-- Ingredients section -->
              <section>
                <v-card class="pa-4 bg-green-lighten-5" flat>
                  <h2 class="text-h6 text-md-h5 font-weight-medium mb-3 d-flex align-center">
                    <v-icon class="mr-2" color="green">mdi-food-variant</v-icon>
                    Ingrédients
                  </h2>
                  <p class="text-body-1 text-md-h6 mb-0 font-weight-regular">
                    {{ recipe.ingredients }}
                  </p>
                </v-card>
              </section>

              <!-- Action buttons responsive -->
              <footer class="mt-8">
                <div class="d-flex flex-column flex-sm-row gap-4 justify-center justify-md-start">
                  <v-btn 
                    @click="goBack"
                    color="primary"
                    variant="outlined"
                    :block="$vuetify.display.xs"
                  >
                    <v-icon left>mdi-arrow-left</v-icon>
                    Retour aux recettes
                  </v-btn>
                  
                  <v-btn 
                    @click="openEditModal"
                    color="primary"
                    :block="$vuetify.display.xs"
                  >
                    <v-icon left>mdi-pencil</v-icon>
                    Modifier cette recette
                  </v-btn>
                  
                  <v-btn 
                    @click="confirmDelete"
                    color="error"
                    variant="outlined"
                    :block="$vuetify.display.xs"
                  >
                    <v-icon left>mdi-delete</v-icon>
                    Supprimer
                  </v-btn>
                </div>
              </footer>
            </article>
          </v-card>
        </v-col>
      </v-row>

      <!-- Modal d'édition responsive -->
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
              @click="closeEditModal"
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
              @click="closeEditModal"
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

      <!-- Dialog de confirmation de suppression -->
      <v-dialog v-model="showDeleteDialog" max-width="400px">
        <v-card>
          <v-card-title class="text-h5">
            Confirmer la suppression
          </v-card-title>
          <v-card-text>
            Êtes-vous sûr de vouloir supprimer cette recette ? Cette action est irréversible.
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn 
              color="grey-darken-1" 
              variant="text" 
              @click="showDeleteDialog = false"
            >
              Annuler
            </v-btn>
            <v-btn 
              color="error" 
              :loading="isDeleting"
              @click="performDelete"
            >
              Supprimer
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </Layout>
</template>

<style scoped>
article {
  line-height: 1.6;
}

section {
  margin-bottom: 2rem;
}

@media (max-width: 600px) {
  article {
    line-height: 1.5;
  }
  
  section {
    margin-bottom: 1.5rem;
  }
}
</style>
