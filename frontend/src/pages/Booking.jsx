// src/pages/Booking.jsx
import React, { useEffect, useState } from 'react';
import { useParams, useSearchParams, useNavigate } from 'react-router-dom';
import { useForm } from 'react-hook-form';
import { useCar } from '../hooks/useCars';
import { useBooking } from '../hooks/useBooking';
import { formatPrice } from '../utils/price';
import { formatDateTime } from '../utils/date';
import Button from '../components/UI/Button';
import Input from '../components/UI/Input';
import Loading from '../components/UI/Loading';

const Booking = () => {
  const { id } = useParams();
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();

  const params = {
    startDate: searchParams.get('startDate'),
    endDate: searchParams.get('endDate'),
  };

  const { car, loading: carLoading, fetchCar } = useCar(id, params);
  const { createBooking, loading: bookingLoading, error: bookingError } = useBooking();

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm();

  useEffect(() => {
    if (!params.startDate || !params.endDate) {
      navigate(`/car/${id}`);
      return;
    }
    fetchCar();
  }, [id]);

  const onSubmit = async (data) => {
    try {
      const bookingData = {
        carId: parseInt(id),
        pickupPointId: car.city.pickupPoints?.[0]?.id || 1, // À améliorer
        startDate: params.startDate,
        endDate: params.endDate,
        firstName: data.firstName,
        lastName: data.lastName,
        email: data.email,
        phone: data.phone,
        notes: data.notes || '',
      };

      const booking = await createBooking(bookingData);
      navigate(`/confirmation/${booking.bookingNumber}`);
    } catch (error) {
      console.error('Erreur lors de la création de la réservation:', error);
    }
  };

  if (carLoading) {
    return (
      <div className="min-h-screen bg-gray-50 py-8">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <Loading text="Chargement..." />
        </div>
      </div>
    );
  }

  if (!car) {
    return (
      <div className="min-h-screen bg-gray-50 py-8">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">
            Voiture non trouvée
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 className="text-3xl font-bold text-gray-900 mb-8">
          Finaliser votre réservation
        </h1>

        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Formulaire */}
          <div className="lg:col-span-2">
            <form onSubmit={handleSubmit(onSubmit)} className="bg-white rounded-lg shadow-md p-6">
              <h2 className="text-xl font-semibold mb-6">Vos informations</h2>

              {bookingError && (
                <div className="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">
                  <p className="font-semibold">Erreur</p>
                  <p>{bookingError}</p>
                </div>
              )}

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <Input
                  label="Prénom"
                  required
                  error={errors.firstName?.message}
                  {...register('firstName', {
                    required: 'Le prénom est requis',
                    minLength: { value: 2, message: 'Minimum 2 caractères' },
                  })}
                />

                <Input
                  label="Nom"
                  required
                  error={errors.lastName?.message}
                  {...register('lastName', {
                    required: 'Le nom est requis',
                    minLength: { value: 2, message: 'Minimum 2 caractères' },
                  })}
                />
              </div>

              <Input
                label="Email"
                type="email"
                required
                error={errors.email?.message}
                {...register('email', {
                  required: 'L\'email est requis',
                  pattern: {
                    value: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i,
                    message: 'Email invalide',
                  },
                })}
              />

              <Input
                label="Téléphone"
                type="tel"
                required
                placeholder="+212 6 XX XX XX XX"
                error={errors.phone?.message}
                {...register('phone', {
                  required: 'Le téléphone est requis',
                  pattern: {
                    value: /^[0-9\s\+\-\(\)]+$/,
                    message: 'Téléphone invalide',
                  },
                })}
              />

              <div className="mb-4">
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Notes (optionnel)
                </label>
                <textarea
                  className="input-field"
                  rows="3"
                  placeholder="Informations supplémentaires..."
                  {...register('notes')}
                />
              </div>

              <div className="flex items-start mb-6">
                <input
                  type="checkbox"
                  className="mt-1 mr-2"
                  required
                  {...register('terms', {
                    required: 'Vous devez accepter les conditions',
                  })}
                />
                <label className="text-sm text-gray-700">
                  J'accepte les{' '}
                  <a href="#" className="text-primary-600 hover:underline">
                    conditions générales
                  </a>{' '}
                  et la{' '}
                  <a href="#" className="text-primary-600 hover:underline">
                    politique de confidentialité
                  </a>
                </label>
              </div>

              <Button
                type="submit"
                className="w-full"
                size="lg"
                loading={bookingLoading}
                disabled={bookingLoading}
              >
                Confirmer la réservation
              </Button>
            </form>
          </div>

          {/* Résumé */}
          <div className="lg:col-span-1">
            <div className="bg-white rounded-lg shadow-md p-6 sticky top-4">
              <h2 className="text-xl font-semibold mb-4">Récapitulatif</h2>

              <div className="mb-4">
                <img
                  src={car.mainImage ? `/uploads/cars/${car.mainImage}` : '/placeholder-car.jpg'}
                  alt={`${car.brand} ${car.model}`}
                  className="w-full h-32 object-cover rounded-lg mb-3"
                />
                <h3 className="font-semibold text-lg">
                  {car.brand} {car.model}
                </h3>
              </div>

              <div className="space-y-3 mb-4 text-sm">
                <div className="flex justify-between">
                  <span className="text-gray-600">Prise en charge</span>
                  <span className="font-medium">{formatDateTime(params.startDate)}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-gray-600">Retour</span>
                  <span className="font-medium">{formatDateTime(params.endDate)}</span>
                </div>
                <div className="flex justify-between">
                  <span className="text-gray-600">Durée</span>
                  <span className="font-medium">
                    {car.numberOfDays} jour{car.numberOfDays > 1 ? 's' : ''}
                  </span>
                </div>
              </div>

              <div className="border-t pt-4 space-y-2">
                <div className="flex justify-between text-sm">
                  <span className="text-gray-600">Prix par jour</span>
                  <span>{formatPrice(car.pricePerDay)}</span>
                </div>
                <div className="flex justify-between font-semibold text-lg">
                  <span>Total</span>
                  <span className="text-primary-600">{formatPrice(car.totalPrice)}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Booking;