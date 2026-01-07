// src/components/Car/CarCard.jsx
import React from 'react';
import { useNavigate } from 'react-router-dom';
import { Users, Fuel, Settings } from 'lucide-react';
import { formatPrice } from '../../utils/price';
import Button from '../UI/Button';

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
    <div className="card hover:shadow-lg transition-shadow duration-200">
      {/* Image */}
      <div className="aspect-video bg-gray-200 relative overflow-hidden">
        {car.mainImage ? (
          <img
            src={`/uploads/cars/${car.mainImage}`}
            alt={`${car.brand} ${car.model}`}
            className="w-full h-full object-cover"
          />
        ) : (
          <div className="w-full h-full flex items-center justify-center text-gray-400">
            <Users className="h-16 w-16" />
          </div>
        )}
        <div className="absolute top-2 right-2 bg-primary-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
          {formatPrice(car.pricePerDay)} / jour
        </div>
      </div>

      {/* Content */}
      <div className="p-4">
        <h3 className="text-xl font-semibold text-gray-900 mb-2">
          {car.brand} {car.model}
        </h3>

        {/* Features */}
        <div className="flex items-center gap-4 text-sm text-gray-600 mb-4">
          <div className="flex items-center gap-1">
            <Users className="h-4 w-4" />
            <span>{car.seats} places</span>
          </div>
          <div className="flex items-center gap-1">
            <Fuel className="h-4 w-4" />
            <span>{fuelTypeLabels[car.fuelType]}</span>
          </div>
          <div className="flex items-center gap-1">
            <Settings className="h-4 w-4" />
            <span>{transmissionLabels[car.transmission]}</span>
          </div>
        </div>

        {/* Prix total */}
        {car.numberOfDays && car.totalPrice && (
          <div className="bg-gray-50 rounded-lg p-3 mb-4">
            <div className="flex justify-between items-center">
              <span className="text-sm text-gray-600">
                Prix total ({car.numberOfDays} jour{car.numberOfDays > 1 ? 's' : ''})
              </span>
              <span className="text-lg font-bold text-primary-600">
                {formatPrice(car.totalPrice)}
              </span>
            </div>
          </div>
        )}

        {/* Boutons */}
        <div className="flex gap-2">
          <Button
            variant="outline"
            className="flex-1"
            onClick={handleViewDetails}
          >
            Détails
          </Button>
          <Button
            className="flex-1"
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