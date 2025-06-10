<script setup lang="ts">
import Layout from '@/components/Layout.vue'
import { Head } from '@inertiajs/vue3'
import type { Recipe } from '@/types'

interface Props {
  recipe: Recipe
}

defineProps<Props>()
</script>

<template>
  <Layout>

    <Head :title="recipe.title" />

    <v-container class="py-8">
      <v-row>
        <v-col cols="12">
          <h1 class="text-h3 font-weight-bold mb-4">{{ recipe.title }}</h1>
          <p class="text-body-1 mb-4">{{ recipe.description }}</p>
          <p class="text-subtitle-1 text-medium-emphasis">Ingrédients : {{ recipe.ingredients }}</p>
        </v-col>
      </v-row>
    </v-container>
  </Layout>
</template>
