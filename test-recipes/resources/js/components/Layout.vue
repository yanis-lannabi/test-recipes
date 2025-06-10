<template>
  <v-app>
    <v-app-bar color="primary" elevation="0">
      <v-container class="d-flex align-center">
        <v-app-bar-title class="text-h5 font-weight-bold">
          <router-link to="/" class="text-decoration-none text-white">
          <v-icon>mdi-chef-hat</v-icon>
          Les recettes de Fullstack
          </router-link>
        </v-app-bar-title>
        <v-spacer></v-spacer>
        <v-btn color="white" :to="{ name: 'recipes' }">
          Voir les recettes
        </v-btn>
        <v-btn color="white" @click="showCreateModal = true">
          Ajouter une recette
        </v-btn>
      </v-container>
    </v-app-bar>

    <v-main>
      <slot></slot>
    </v-main>

    <v-footer class="bg-grey-lighten-4">
      <v-container>
        <v-row>
          <v-col cols="12" md="4">
            <h3 class="text-h6 font-weight-bold mb-4">Les recettes de Fullstack</h3>
            <p class="text-body-2">
              Votre destination pour découvrir des recettes exceptionnelles.
            </p>
          </v-col>
          <v-col cols="12" md="4">
            <h3 class="text-h6 font-weight-bold mb-4">Liens Rapides</h3>
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
    <v-dialog v-model="showCreateModal" max-width="600px">
      <v-card>
        <v-card-title class="text-h5">
          Ajouter une nouvelle recette
        </v-card-title>

        <v-card-text>
          <v-form @submit.prevent="submitForm">
            <v-text-field 
              v-model="form.title" 
              label="Titre" 
              required
              :error-messages="formErrors.title"
            ></v-text-field>

            <v-textarea 
              v-model="form.description" 
              label="Description" 
              required
              :error-messages="formErrors.description"
            ></v-textarea>

            <v-textarea 
              v-model="form.ingredients" 
              label="Ingrédients" 
              required
              :error-messages="formErrors.ingredients"
            ></v-textarea>
          </v-form>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="grey-darken-1" variant="text" @click="closeCreateModal">
            Annuler
          </v-btn>
          <v-btn color="primary" :loading="isSubmitting" @click="submitForm">
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
import axios from 'axios'

const router = useRouter()
const showCreateModal = ref(false)
const isSubmitting = ref(false)
const formErrors = ref<Record<string, string>>({})
const form = ref({
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
    await axios.post('/api/recipes', form.value)
    closeCreateModal()
    isSubmitting.value = false
    // Rafraîchir la page des recettes si on y est
    if (router.currentRoute.value.name === 'recipes') {
      router.go(0)
    }
  } catch (err: any) {
    if (err.response && err.response.status === 422 && err.response.data.errors) {
      formErrors.value = err.response.data.errors
    } else {
      console.error('Erreur lors de la création:', err)
    }
    isSubmitting.value = false
  }
}
</script>