// src/components/Car/CarList.jsx
import React from 'react';
import CarCard from './CarCard';

const CarList = ({ cars, searchParams, loading }) => {
  if (loading) {
    return (
      <div className="text-center py-12">
        <div className="animate-spin h-12 w-12 border-4 border-primary-600 border-t-transparent rounded-full mx-auto mb-4"></div>
        <p className="text-gray-600">Recherche en cours...</p>
      </div>
    );
  }

  if (!cars || cars.length === 0) {
    return (
      <div className="text-center py-12">
        <p className="text-gray-600 text-lg">
          Aucune voiture disponible pour ces critères.
        </p>
        <p className="text-gray-500 mt-2">
          Essayez de modifier vos dates ou votre ville.
        </p>
      </div>
    );
  }

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      {cars.map(car => (
        <CarCard key={car.id} car={car} searchParams={searchParams} />
      ))}
    </div>
  );
};

export default CarList;