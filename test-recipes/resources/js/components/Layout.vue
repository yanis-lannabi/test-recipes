<template>
  <v-app>
    <!-- Header responsive -->
    <v-app-bar color="primary" elevation="0">
      <v-container class="d-flex align-center">
        <!-- Menu mobile -->
        <v-app-bar-nav-icon 
          v-if="$vuetify.display.mobile" 
          @click="drawer = !drawer"
          class="mr-2"
        ></v-app-bar-nav-icon>
        
        <v-app-bar-title class="text-h6 text-md-h5 font-weight-bold">
          <router-link to="/" class="text-decoration-none text-white">
            <v-icon class="mr-1">mdi-chef-hat</v-icon>
            <span class="d-none d-sm-inline">Les recettes de Fullstack</span>
            <span class="d-inline d-sm-none">Recettes</span>
          </router-link>
        </v-app-bar-title>
        
        <v-spacer></v-spacer>
        
        <!-- Boutons desktop -->
        <div v-if="!$vuetify.display.mobile" class="d-flex gap-2">
          <v-btn color="white" :to="{ name: 'recipes' }" variant="outlined" size="small">
            <v-icon left>mdi-book-open-page-variant</v-icon>
            Recettes
          </v-btn>
          <v-btn color="white" @click="showCreateModal = true" size="small">
            <v-icon left>mdi-plus</v-icon>
            Ajouter
          </v-btn>
        </div>
      </v-container>
    </v-app-bar>

    <!-- Navigation mobile (drawer) -->
    <v-navigation-drawer v-model="drawer" temporary>
      <v-list>
        <v-list-item :to="{ name: 'home' }" @click="drawer = false">
          <template v-slot:prepend>
            <v-icon>mdi-home</v-icon>
          </template>
          <v-list-item-title>Accueil</v-list-item-title>
        </v-list-item>
        
        <v-list-item :to="{ name: 'recipes' }" @click="drawer = false">
          <template v-slot:prepend>
            <v-icon>mdi-book-open-page-variant</v-icon>
          </template>
          <v-list-item-title>Voir les recettes</v-list-item-title>
        </v-list-item>
        
        <v-list-item @click="showCreateModal = true; drawer = false">
          <template v-slot:prepend>
            <v-icon>mdi-plus</v-icon>
          </template>
          <v-list-item-title>Ajouter une recette</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-main>
      <slot></slot>
    </v-main>

    <!-- Footer responsive -->
    <v-footer class="bg-grey-lighten-4">
      <v-container>
        <v-row>
          <v-col cols="12" md="6">
            <h3 class="text-h6 font-weight-bold mb-2 mb-md-4">Les recettes de Fullstack</h3>
            <p class="text-body-2 mb-2">
              Votre destination pour découvrir des recettes exceptionnelles.
            </p>
          </v-col>
          <v-col cols="12" md="6">
            <h3 class="text-h6 font-weight-bold mb-2 mb-md-4">Liens Rapides</h3>
            <v-list density="compact" class="bg-transparent">
              <router-link to="/recipes" class="text-decoration-none">
                <v-list-item>Recettes</v-list-item>
              </router-link>
            </v-list>
          </v-col>
        </v-row>
        <v-divider class="my-4"></v-divider>
        <div class="text-center text-body-2">
          © {{ new Date().getFullYear() }} Les recettes de Fullstack. Tous droits réservés.
        </div>
      </v-container>
    </v-footer>

    <!-- Modal de création responsive -->
    <v-dialog v-model="showCreateModal" :max-width="$vuetify.display.mobile ? '95%' : '600px'" :fullscreen="$vuetify.display.xs">
      <v-card>
        <v-card-title class="text-h5 d-flex align-center">
          Ajouter une nouvelle recette
          <v-spacer></v-spacer>
          <v-btn 
            v-if="$vuetify.display.mobile" 
            icon="mdi-close" 
            variant="text" 
            size="small"
            @click="closeCreateModal"
          ></v-btn>
        </v-card-title>

        <v-card-text class="pb-2">
          <v-form @submit.prevent="submitForm">
            <v-text-field 
              v-model="form.title" 
              label="Titre" 
              required
              :error-messages="formErrors.title"
              density="comfortable"
            ></v-text-field>

            <v-textarea 
              v-model="form.description" 
              label="Description" 
              required
              :error-messages="formErrors.description"
              rows="3"
              density="comfortable"
            ></v-textarea>

            <v-textarea 
              v-model="form.ingredients" 
              label="Ingrédients" 
              required
              :error-messages="formErrors.ingredients"
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
            @click="closeCreateModal"
            :block="$vuetify.display.mobile"
            class="mb-2 mb-sm-0"
          >
            Annuler
          </v-btn>
          <v-btn 
            color="primary" 
            :loading="isSubmitting" 
            @click="submitForm"
            :block="$vuetify.display.mobile"
          >
            Créer
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { createRecipe, formatValidationErrors } from '@/lib/api'
import type { CreateRecipeData } from '@/lib/api'

const router = useRouter()
const drawer = ref(false)
const showCreateModal = ref(false)
const isSubmitting = ref(false)
const formErrors = ref<Record<string, string>>({})
const form = ref<CreateRecipeData>({
  title: '',
  description: '',
  ingredients: ''
})

const closeCreateModal = () => {
  showCreateModal.value = false
  formErrors.value = {}
  form.value = {
    title: '',
    description: '',
    ingredients: ''
  }
}

const submitForm = async () => {
  isSubmitting.value = true
  formErrors.value = {}
  
  try {
    await createRecipe(form.value)
    closeCreateModal()
    
    // Rafraîchir la page des recettes si on y est
    if (router.currentRoute.value.name === 'recipes') {
      router.go(0)
    }
  } catch (error: any) {
    formErrors.value = formatValidationErrors(error)
    console.error('Erreur lors de la création:', error)
  } finally {
    isSubmitting.value = false
  }
}
</script>