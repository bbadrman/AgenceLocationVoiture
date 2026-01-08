// src/api/client.js
import axios from 'axios';

const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

const apiClient = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Intercepteur pour gérer les erreurs
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    console.error('API Error:', error.response?.data || error.message);
    return Promise.reject(error);
  }
);

// API des villes
export const citiesAPI = {
  getAll: () => apiClient.get('/cities'),
  getById: (id) => apiClient.get(`/cities/${id}`),
  getPickupPoints: (cityId) => apiClient.get(`/cities/${cityId}/pickup-points`),
};

// API des voitures
export const carsAPI = {
  search: (params) => apiClient.get('/cars/search', { params }),
  getById: (id, params = {}) => apiClient.get(`/cars/${id}`, { params }),
  checkAvailability: (id, params) => apiClient.get(`/cars/${id}/availability`, { params }),
  getBrands: () => apiClient.get('/cars/brands/list'),
  getFuelTypes: () => apiClient.get('/cars/fuel-types/list'),
  getAll: () => apiClient.get('/cars'),
};

// API des réservations
export const bookingsAPI = {
  create: (data) => apiClient.post('/bookings', data),
  getByNumber: (bookingNumber) => apiClient.get(`/bookings/${bookingNumber}`),
  cancel: (id) => apiClient.post(`/bookings/${id}/cancel`),
};

// API des points de collecte
export const pickupPointsAPI = {
  getAll: () => apiClient.get('/pickup-points'),
  getByCity: (cityId) => apiClient.get(`/cities/${cityId}/pickup-points`),
  getById: (id) => apiClient.get(`/pickup-points/${id}`),
};

export default apiClient;