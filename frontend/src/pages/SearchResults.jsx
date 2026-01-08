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
    <div className="min-h-screen bg-gray-100 py-12">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {/* Header Section */}
        <div className="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
          <div>
            <h1 className="text-3xl font-bold text-gray-900">Résultats de recherche</h1>
            {searchInfo && (
              <p className="text-gray-600 mt-2 flex items-center gap-2">
                <span className="font-medium text-primary-600 bg-primary-50 px-3 py-1 rounded-full text-sm">
                  {searchInfo.total} voiture{searchInfo.total > 1 ? 's' : ''} disponible{searchInfo.total > 1 ? 's' : ''}
                </span>
                <span className="text-sm">
                  Du {formatDate(searchInfo.period.startDate)} au {formatDate(searchInfo.period.endDate)}
                </span>
              </p>
            )}
          </div>

          <button
            onClick={() => setShowFilters(!showFilters)}
            className="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm font-medium"
          >
            <ChevronDown className={`h-5 w-5 transition-transform duration-200 ${showFilters ? 'rotate-180' : ''}`} />
            {showFilters ? 'Masquer' : 'Modifier'} la recherche
          </button>
        </div>

        {/* Search Form Panel */}
        <div className={`transition-all duration-300 ease-in-out overflow-hidden ${showFilters ? 'max-h-[500px] opacity-100 mb-8' : 'max-h-0 opacity-0'}`}>
          <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <SearchForm
              onSearch={handleSearch}
              initialValues={params}
            />
          </div>
        </div>

        {/* Error Message */}
        {error && (
          <div className="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 mb-8 flex items-center gap-3">
            <div className="bg-red-100 p-2 rounded-full">
              <span className="text-xl">⚠️</span>
            </div>
            <div>
              <p className="font-bold">Erreur lors de la recherche</p>
              <p className="text-sm">{error}</p>
            </div>
          </div>
        )}

        {/* Car List */}
        <CarList cars={cars} searchParams={params} loading={loading} />
      </div>
    </div>
  );
};

export default SearchResults;