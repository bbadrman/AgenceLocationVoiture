// src/pages/SearchResults.jsx
import React, { useEffect, useState } from 'react';
import { useSearchParams, useNavigate } from 'react-router-dom';
import { useCars } from '../hooks/useCars';
import SearchForm from '../components/Search/SearchForm';
import CarList from '../components/Car/CarList';
import { formatDate, calculateDays } from '../utils/date';
import { ChevronDown } from 'lucide-react';

const SearchResults = () => {
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();
  const { cars, loading, error, searchInfo, searchCars } = useCars();
  const [showFilters, setShowFilters] = useState(false);

  // Extraire les paramètres de recherche de l'URL
  const params = {
    cityId: searchParams.get('cityId'),
    pickupPointId: searchParams.get('pickupPointId'),
    startDate: searchParams.get('startDate'),
    endDate: searchParams.get('endDate'),
  };

  useEffect(() => {
    if (params.cityId && params.startDate && params.endDate) {
      searchCars(params);
    }
  }, [searchParams]);

  const handleSearch = (newParams) => {
    const queryString = new URLSearchParams(newParams).toString();
    navigate(`/search?${queryString}`);
  };

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header avec formulaire de recherche */}
        <div className="mb-8">
          <button
            onClick={() => setShowFilters(!showFilters)}
            className="mb-4 flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium"
          >
            <ChevronDown className={`h-5 w-5 transition-transform ${showFilters ? 'rotate-180' : ''}`} />
            {showFilters ? 'Masquer' : 'Modifier'} la recherche
          </button>

          {showFilters && (
            <SearchForm
              onSearch={handleSearch}
              initialValues={params}
            />
          )}
        </div>

        {/* Informations de recherche */}
        {searchInfo && (
          <div className="bg-white rounded-lg shadow p-4 mb-6">
            <div className="flex flex-wrap items-center justify-between gap-4">
              <div>
                <h2 className="text-2xl font-bold text-gray-900">
                  {searchInfo.total} voiture{searchInfo.total > 1 ? 's' : ''} disponible{searchInfo.total > 1 ? 's' : ''}
                </h2>
                <p className="text-gray-600 mt-1">
                  Du {formatDate(searchInfo.period.startDate)} au {formatDate(searchInfo.period.endDate)}
                  {' '}({searchInfo.period.numberOfDays} jour{searchInfo.period.numberOfDays > 1 ? 's' : ''})
                </p>
              </div>
            </div>
          </div>
        )}

        {/* Erreur */}
        {error && (
          <div className="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 mb-6">
            <p className="font-semibold">Erreur lors de la recherche</p>
            <p>{error}</p>
          </div>
        )}

        {/* Liste des voitures */}
        <CarList cars={cars} searchParams={params} loading={loading} />
      </div>
    </div>
  );
};

export default SearchResults;