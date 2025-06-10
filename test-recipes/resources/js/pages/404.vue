<script setup lang="ts">
import Layout from '@/components/Layout.vue'
</script>

<template>
  <Layout>
    <v-container class="d-flex align-center justify-center" style="min-height: 70vh;">
      <div class="text-center px-4">
        <v-icon 
          :size="$vuetify.display.mobile ? 80 : 120" 
          color="error" 
          class="mb-4 mb-md-6"
        >
          mdi-alert-circle-outline
        </v-icon>
        
        <h1 class="text-h2 text-sm-h1 font-weight-bold mb-4 mb-md-6">404</h1>
        
        <p class="text-h6 text-md-h4 mb-4 mb-md-6">Page non trouvée</p>
        
        <p class="text-body-1 text-md-h6 mb-6 mb-md-8 text-medium-emphasis">
          Désolé, la page que vous recherchez n'existe pas.
        </p>
        
        <div class="d-flex flex-column flex-sm-row gap-4 justify-center align-center">
          <v-btn 
            color="primary" 
            size="large" 
            :to="{ name: 'home' }"
            :block="$vuetify.display.xs"
            elevation="2"
          >
            <v-icon left>mdi-home</v-icon>
            Retour à l'accueil
          </v-btn>
          
          <v-btn 
            color="primary" 
            variant="outlined" 
            size="large" 
            :to="{ name: 'recipes' }" 
            :block="$vuetify.display.xs"
          >
            <v-icon left>mdi-food</v-icon>
            Voir les recettes
          </v-btn>
        </div>
      </div>
    </v-container>
  </Layout>
</template>

<style scoped>
@media (max-width: 600px) {
  .v-container {
    padding-top: 2rem;
    padding-bottom: 2rem;
  }
}
</style> 