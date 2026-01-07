// src/components/Search/SearchForm.jsx
import React, { useState, useEffect } from 'react';
import { Search } from 'lucide-react';
import { useCities, usePickupPoints } from '../../hooks/useCities';
import Button from '../UI/Button';
import { formatDateForAPI } from '../../utils/date';

const SearchForm = ({ onSearch, initialValues = {} }) => {
  const { cities, loading: citiesLoading } = useCities();
  
  const [formData, setFormData] = useState({
    cityId: initialValues.cityId || '',
    pickupPointId: initialValues.pickupPointId || '',
    startDate: initialValues.startDate || '',
    startTime: initialValues.startTime || '10:00',
    endDate: initialValues.endDate || '',
    endTime: initialValues.endTime || '10:00',
  });

  const { pickupPoints, loading: pickupLoading } = usePickupPoints(formData.cityId);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value,
      // Reset pickup point when city changes
      ...(name === 'cityId' ? { pickupPointId: '' } : {})
    }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    
    if (!formData.cityId || !formData.startDate || !formData.endDate) {
      alert('Veuillez remplir tous les champs obligatoires');
      return;
    }

    const searchParams = {
      cityId: formData.cityId,
      pickupPointId: formData.pickupPointId || undefined,
      startDate: `${formData.startDate} ${formData.startTime}:00`,
      endDate: `${formData.endDate} ${formData.endTime}:00`,
    };

    onSearch(searchParams);
  };

  // Set minimum dates
  const today = new Date().toISOString().split('T')[0];
  const minEndDate = formData.startDate || today;

  return (
    <form onSubmit={handleSubmit} className="bg-white rounded-lg shadow-lg p-6">
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {/* Ville */}
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-2">
            Ville <span className="text-red-500">*</span>
          </label>
          <select
            name="cityId"
            value={formData.cityId}
            onChange={handleChange}
            className="input-field"
            required
            disabled={citiesLoading}
          >
            <option value="">Sélectionnez une ville</option>
            {cities.map(city => (
              <option key={city.id} value={city.id}>
                {city.name}
              </option>
            ))}
          </select>
        </div>

        {/* Point de collecte */}
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-2">
            Lieu de prise en charge
          </label>
          <select
            name="pickupPointId"
            value={formData.pickupPointId}
            onChange={handleChange}
            className="input-field"
            disabled={!formData.cityId || pickupLoading}
          >
            <option value="">Tous les lieux</option>
            {pickupPoints.map(point => (
              <option key={point.id} value={point.id}>
                {point.name} ({point.type === 'agency' ? 'Agence' : 'Aéroport'})
              </option>
            ))}
          </select>
        </div>

        {/* Date de début */}
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-2">
            Date de début <span className="text-red-500">*</span>
          </label>
          <div className="flex gap-2">
            <input
              type="date"
              name="startDate"
              value={formData.startDate}
              onChange={handleChange}
              min={today}
              className="input-field flex-1"
              required
            />
            <input
              type="time"
              name="startTime"
              value={formData.startTime}
              onChange={handleChange}
              className="input-field w-24"
              required
            />
          </div>
        </div>

        {/* Date de fin */}
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-2">
            Date de fin <span className="text-red-500">*</span>
          </label>
          <div className="flex gap-2">
            <input
              type="date"
              name="endDate"
              value={formData.endDate}
              onChange={handleChange}
              min={minEndDate}
              className="input-field flex-1"
              required
            />
            <input
              type="time"
              name="endTime"
              value={formData.endTime}
              onChange={handleChange}
              className="input-field w-24"
              required
            />
          </div>
        </div>

        {/* Bouton de recherche */}
        <div className="md:col-span-2 lg:col-span-1 flex items-end">
          <Button
            type="submit"
            className="w-full flex items-center justify-center gap-2"
            disabled={citiesLoading}
          >
            <Search className="h-5 w-5" />
            Rechercher
          </Button>
        </div>
      </div>
    </form>
  );
};

export default SearchForm;