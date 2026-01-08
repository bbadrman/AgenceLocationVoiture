// src/components/Car/CarCard.jsx
import React from 'react';
import { useNavigate } from 'react-router-dom';
import { Users, Fuel, Settings } from 'lucide-react';
import { formatPrice } from '../../utils/price';
import Button from '../UI/Button';
import carPlaceholder from '../../assets/car-placeholder.svg';

const CarCard = ({ car, searchParams }) => {
  const navigate = useNavigate();

  const fuelTypeLabels = {
    gasoline: 'Essence',
    diesel: 'Diesel',
    hybrid: 'Hybride',
    electric: 'Électrique',
  };

  const transmissionLabels = {
    manual: 'Manuelle',
    automatic: 'Automatique',
  };

  const handleViewDetails = () => {
    const params = new URLSearchParams({
      ...searchParams,
      carId: car.id,
    });
    navigate(`/car/${car.id}?${params.toString()}`);
  };

  return (
    <div className="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col h-full">
      {/* Image Container */}
      <div className="aspect-[4/3] bg-gray-100 relative overflow-hidden">
        <img
          src={car.mainImage ? `/uploads/cars/${car.mainImage}` : carPlaceholder}
          alt={`${car.brand} ${car.model}`}
          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
          onError={(e) => {
            e.target.src = carPlaceholder;
          }}
        />

        {/* Price Tag */}
        <div className="absolute top-4 right-4 bg-white/90 backdrop-blur-md px-4 py-2 rounded-full shadow-lg border border-white/50">
          <div className="flex items-baseline gap-1">
            <span className="text-lg font-bold text-primary-600">{formatPrice(car.pricePerDay)}</span>
            <span className="text-sm text-gray-500 font-medium">/jour</span>
          </div>
        </div>
      </div>

      {/* Content */}
      <div className="p-5 flex flex-col flex-grow">
        <div className="mb-4">
          <h3 className="text-xl font-bold text-gray-900 mb-1 group-hover:text-primary-600 transition-colors">
            {car.brand} {car.model}
          </h3>
          <p className="text-sm text-gray-500 font-medium">
            Disponible immédiatement
          </p>
        </div>

        {/* Features Grid */}
        <div className="grid grid-cols-2 gap-3 mb-6">
          <div className="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 p-2 rounded-lg">
            <Users className="h-4 w-4 text-primary-500" />
            <span className="font-medium">{car.seats} places</span>
          </div>
          <div className="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 p-2 rounded-lg">
            <Fuel className="h-4 w-4 text-primary-500" />
            <span className="font-medium">{fuelTypeLabels[car.fuelType]}</span>
          </div>
          <div className="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 p-2 rounded-lg">
            <Settings className="h-4 w-4 text-primary-500" />
            <span className="font-medium">{transmissionLabels[car.transmission]}</span>
          </div>
        </div>

        <div className="mt-auto pt-4 border-t border-gray-100 flex gap-3">
          <Button
            variant="outline"
            className="flex-1 py-2.5 font-medium border-gray-200 hover:border-primary-600 hover:text-primary-600"
            onClick={handleViewDetails}
          >
            Détails
          </Button>
          <Button
            className="flex-1 py-2.5 font-medium bg-primary-600 hover:bg-primary-700 text-white shadow-lg shadow-primary-600/20"
            onClick={() => navigate(`/booking/${car.id}?${new URLSearchParams(searchParams).toString()}`)}
          >
            Réserver
          </Button>
        </div>
      </div>
    </div>
  );
};

export default CarCard;