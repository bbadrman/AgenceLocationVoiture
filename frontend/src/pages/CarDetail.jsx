// src/pages/CarDetail.jsx
import React, { useEffect } from 'react';
import { useParams, useSearchParams, useNavigate } from 'react-router-dom';
import { useCar } from '../hooks/useCars';
import { Users, Fuel, Settings, MapPin, Check } from 'lucide-react';
import { formatPrice } from '../utils/price';
import { formatDate, calculateDays } from '../utils/date';
import Button from '../components/UI/Button';
import Loading from '../components/UI/Loading';

const CarDetail = () => {
  const { id } = useParams();
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();

  const params = {
    startDate: searchParams.get('startDate'),
    endDate: searchParams.get('endDate'),
  };

  const { car, loading, error, fetchCar } = useCar(id, params);

  useEffect(() => {
    fetchCar();
  }, [id]);

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

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 py-8">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <Loading text="Chargement des détails..." />
        </div>
      </div>
    );
  }

  if (error || !car) {
    return (
      <div className="min-h-screen bg-gray-50 py-8">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">
            <p className="font-semibold">Erreur</p>
            <p>{error || 'Voiture non trouvée'}</p>
            <Button onClick={() => navigate(-1)} className="mt-4">
              Retour
            </Button>
          </div>
        </div>
      </div>
    );
  }

  const handleBooking = () => {
    const bookingParams = new URLSearchParams({
      startDate: params.startDate,
      endDate: params.endDate,
    });
    navigate(`/booking/${car.id}?${bookingParams.toString()}`);
  };

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Breadcrumb */}
        <nav className="mb-6 text-sm">
          <button
            onClick={() => navigate(-1)}
            className="text-primary-600 hover:text-primary-700"
          >
            ← Retour aux résultats
          </button>
        </nav>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Colonne principale */}
          <div className="lg:col-span-2">
            {/* Images */}
            <div className="bg-white rounded-lg shadow-md overflow-hidden mb-6">
              <div className="aspect-video bg-gray-200">
                {car.mainImage ? (
                  <img
                    src={`/uploads/cars/${car.mainImage}`}
                    alt={`${car.brand} ${car.model}`}
                    className="w-full h-full object-cover"
                  />
                ) : (
                  <div className="w-full h-full flex items-center justify-center text-gray-400">
                    <Users className="h-24 w-24" />
                  </div>
                )}
              </div>

              {/* Galerie d'images */}
              {car.images && car.images.length > 0 && (
                <div className="p-4 grid grid-cols-4 gap-2">
                  {car.images.map((image, index) => (
                    <div key={index} className="aspect-square bg-gray-200 rounded overflow-hidden">
                      <img
                        src={`/uploads/cars/${image}`}
                        alt={`${car.brand} ${car.model} - ${index + 1}`}
                        className="w-full h-full object-cover"
                      />
                    </div>
                  ))}
                </div>
              )}
            </div>

            {/* Informations */}
            <div className="bg-white rounded-lg shadow-md p-6 mb-6">
              <h1 className="text-3xl font-bold text-gray-900 mb-2">
                {car.brand} {car.model}
              </h1>
              {car.year && (
                <p className="text-gray-600 mb-4">Année: {car.year}</p>
              )}

              {/* Caractéristiques */}
              <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div className="flex items-center gap-2 text-gray-700">
                  <Users className="h-5 w-5 text-primary-600" />
                  <div>
                    <p className="text-sm text-gray-500">Places</p>
                    <p className="font-semibold">{car.seats}</p>
                  </div>
                </div>
                <div className="flex items-center gap-2 text-gray-700">
                  <Fuel className="h-5 w-5 text-primary-600" />
                  <div>
                    <p className="text-sm text-gray-500">Carburant</p>
                    <p className="font-semibold">{fuelTypeLabels[car.fuelType]}</p>
                  </div>
                </div>
                <div className="flex items-center gap-2 text-gray-700">
                  <Settings className="h-5 w-5 text-primary-600" />
                  <div>
                    <p className="text-sm text-gray-500">Transmission</p>
                    <p className="font-semibold">{transmissionLabels[car.transmission]}</p>
                  </div>
                </div>
                <div className="flex items-center gap-2 text-gray-700">
                  <MapPin className="h-5 w-5 text-primary-600" />
                  <div>
                    <p className="text-sm text-gray-500">Ville</p>
                    <p className="font-semibold">{car.city?.name}</p>
                  </div>
                </div>
              </div>

              {/* Description */}
              {car.description && (
                <div className="mb-6">
                  <h2 className="text-xl font-semibold mb-3">Description</h2>
                  <p className="text-gray-700 leading-relaxed">{car.description}</p>
                </div>
              )}

              {/* Équipements */}
              {car.features && car.features.length > 0 && (
                <div>
                  <h2 className="text-xl font-semibold mb-3">Équipements inclus</h2>
                  <div className="grid grid-cols-2 md:grid-cols-3 gap-3">
                    {car.features.map((feature, index) => (
                      <div key={index} className="flex items-center gap-2 text-gray-700">
                        <Check className="h-5 w-5 text-green-600 flex-shrink-0" />
                        <span>{feature}</span>
                      </div>
                    ))}
                  </div>
                </div>
              )}
            </div>
          </div>

          {/* Sidebar de réservation */}
          <div className="lg:col-span-1">
            <div className="bg-white rounded-lg shadow-md p-6 sticky top-4">
              <div className="mb-6">
                <div className="flex items-baseline gap-2">
                  <span className="text-3xl font-bold text-primary-600">
                    {formatPrice(car.pricePerDay)}
                  </span>
                  <span className="text-gray-600">/ jour</span>
                </div>
              </div>

              {/* Résumé de la période */}
              {car.numberOfDays && car.totalPrice && (
                <div className="mb-6">
                  <div className="bg-gray-50 rounded-lg p-4 space-y-2">
                    <div className="flex justify-between text-sm">
                      <span className="text-gray-600">Durée</span>
                      <span className="font-semibold">
                        {car.numberOfDays} jour{car.numberOfDays > 1 ? 's' : ''}
                      </span>
                    </div>
                    <div className="flex justify-between text-sm">
                      <span className="text-gray-600">Prix par jour</span>
                      <span className="font-semibold">{formatPrice(car.pricePerDay)}</span>
                    </div>
                    <div className="border-t pt-2 flex justify-between">
                      <span className="font-semibold">Total</span>
                      <span className="text-xl font-bold text-primary-600">
                        {formatPrice(car.totalPrice)}
                      </span>
                    </div>
                  </div>

                  {car.isAvailable === false && (
                    <div className="mt-4 bg-red-50 border border-red-200 text-red-800 rounded-lg p-3 text-sm">
                      Cette voiture n'est pas disponible pour cette période.
                    </div>
                  )}
                </div>
              )}

              <Button
                className="w-full"
                size="lg"
                onClick={handleBooking}
                disabled={car.isAvailable === false}
              >
                {car.isAvailable === false ? 'Non disponible' : 'Réserver maintenant'}
              </Button>

              <p className="text-xs text-gray-500 text-center mt-4">
                Confirmation immédiate • Annulation gratuite
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default CarDetail;