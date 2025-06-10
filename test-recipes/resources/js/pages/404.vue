<script setup lang="ts">
import Layout from '@/components/Layout.vue'
</script>

<template>
  <Layout>
    <v-container class="d-flex align-center justify-center" style="min-height: 60vh;">
      <div class="text-center">
        <v-icon size="100" color="error" class="mb-4">mdi-alert-circle-outline</v-icon>
        <h1 class="text-h2 font-weight-bold mb-4">404</h1>
        <p class="text-h5 mb-4">Page non trouvée</p>
        <p class="text-body-1 mb-6">
          Désolé, la page que vous recherchez n'existe pas.
        </p>
        <v-btn color="primary" size="large" :to="{ name: 'home' }">
          <v-icon left>mdi-home</v-icon>
          Retour à l'accueil
        </v-btn>
        <v-btn color="primary" variant="outlined" size="large" :to="{ name: 'recipes' }" class="ml-4">
          <v-icon left>mdi-food</v-icon>
          Voir les recettes
        </v-btn>
      </div>
    </v-container>
  </Layout>
</template>


<style scoped>
/* Styles pour la page 404 */
</style> 