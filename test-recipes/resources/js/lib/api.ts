import axios from 'axios'
import type { Recipe } from '@/types'

export interface PaginatedRecipes {
  data: Recipe[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}

export interface ApiError {
  message: string
  errors?: Record<string, string[]>
}

export interface CreateRecipeData {
  title: string
  description: string
  ingredients: string
}

export interface UpdateRecipeData extends CreateRecipeData {
  id: number
}

const apiClient = axios.create({
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    console.error('API Error:', error)
    return Promise.reject(error)
  }
)


export const fetchRecipes = async (
  page: number = 1, 
  search: string = ''
): Promise<PaginatedRecipes> => {
  try {
    const params = new URLSearchParams({ page: page.toString() })
    if (search.trim()) {
      params.append('search', search.trim())
    }
    
    const response = await apiClient.get(`/api/recipes?${params.toString()}`)
    return response.data
  } catch (error: any) {
    throw new Error(error.response?.data?.message || 'Erreur lors du chargement des recettes')
  }
}

export const fetchRecipe = async (id: string | number): Promise<Recipe> => {
  try {
    const response = await apiClient.get(`/api/recipes/${id}`)
    return response.data
  } catch (error: any) {
    if (error.response?.status === 404) {
      throw new Error('Recette non trouvée')
    }
    throw new Error(error.response?.data?.message || 'Erreur lors du chargement de la recette')
  }
}

export const createRecipe = async (data: CreateRecipeData): Promise<Recipe> => {
  try {
    const response = await apiClient.post('/api/recipes', data)
    return response.data
  } catch (error: any) {
    if (error.response?.status === 422) {
      const validationError: ApiError = {
        message: 'Erreurs de validation',
        errors: error.response.data.errors
      }
      throw validationError
    }
    throw new Error(error.response?.data?.message || 'Erreur lors de la création de la recette')
  }
}

export const updateRecipe = async (id: string | number, data: CreateRecipeData): Promise<Recipe> => {
  try {
    const response = await apiClient.put(`/api/recipes/${id}`, data)
    return response.data
  } catch (error: any) {
    if (error.response?.status === 422) {
      const validationError: ApiError = {
        message: 'Erreurs de validation',
        errors: error.response.data.errors
      }
      throw validationError
    }
    if (error.response?.status === 404) {
      throw new Error('Recette non trouvée')
    }
    throw new Error(error.response?.data?.message || 'Erreur lors de la modification de la recette')
  }
}

export const deleteRecipe = async (id: string | number): Promise<void> => {
  try {
    await apiClient.delete(`/api/recipes/${id}`)
  } catch (error: any) {
    if (error.response?.status === 404) {
      throw new Error('Recette non trouvée')
    }
    throw new Error(error.response?.data?.message || 'Erreur lors de la suppression de la recette')
  }
}


export const isValidationError = (error: any): error is ApiError => {
  return error.errors && typeof error.errors === 'object'
}


export const getFieldErrors = (error: any, field: string): string[] => {
  if (isValidationError(error) && error.errors?.[field]) {
    return error.errors[field]
  }
  return []
}


export const getFirstFieldError = (error: any, field: string): string => {
  const errors = getFieldErrors(error, field)
  return errors.length > 0 ? errors[0] : ''
}

export const formatValidationErrors = (error: any): Record<string, string> => {
  const formattedErrors: Record<string, string> = {}
  
  if (isValidationError(error) && error.errors) {
    Object.keys(error.errors).forEach(field => {
      formattedErrors[field] = getFirstFieldError(error, field)
    })
  }
  
  return formattedErrors
}


export const debounce = <T extends (...args: any[]) => any>(
  func: T, 
  wait: number
): ((...args: Parameters<T>) => void) => {
  let timeout: ReturnType<typeof setTimeout>
  
  return (...args: Parameters<T>) => {
    clearTimeout(timeout)
    timeout = setTimeout(() => func(...args), wait)
  }
}


export const createDebouncedSearch = (
  searchFunction: (query: string, page?: number) => Promise<void>,
  delay: number = 500
) => {
  return debounce(searchFunction, delay)
} 