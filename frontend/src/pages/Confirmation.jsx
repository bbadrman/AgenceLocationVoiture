// src/pages/Confirmation.jsx
import React, { useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { CheckCircle, Download, Home } from 'lucide-react';
import { useBooking } from '../hooks/useBooking';
import { formatPrice } from '../utils/price';
import { formatDateTime } from '../utils/date';
import Button from '../components/UI/Button';
import Loading from '../components/UI/Loading';

const Confirmation = () => {
  const { bookingNumber } = useParams();
  const navigate = useNavigate();
  const { booking, loading, error, getBooking } = useBooking();

  useEffect(() => {
    if (bookingNumber) {
      getBooking(bookingNumber);
    }
  }, [bookingNumber]);

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 py-8">
        <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
          <Loading text="Chargement de votre réservation..." />
        </div>
      </div>
    );
  }

  if (error || !booking) {
    return (
      <div className="min-h-screen bg-gray-50 py-8">
        <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="bg-red-50 border border-red-200 text-red-800 rounded-lg p-6">
            <p className="font-semibold mb-2">Erreur</p>
            <p>{error || 'Réservation non trouvée'}</p>
            <Button onClick={() => navigate('/')} className="mt-4">
              Retour à l'accueil
            </Button>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Message de succès */}
        <div className="text-center mb-8">
          <div className="inline-flex items-center justify-center w-20 h-20 bg-green-100 text-green-600 rounded-full mb-4">
            <CheckCircle className="h-12 w-12" />
          </div>
          <h1 className="text-3xl font-bold text-gray-900 mb-2">
            Réservation confirmée !
          </h1>
          <p className="text-gray-600">
            Un email de confirmation a été envoyé à {booking.customer?.email}
          </p>
        </div>

        {/* Détails de la réservation */}
        <div className="bg-white rounded-lg shadow-md p-6 mb-6">
          <div className="flex justify-between items-start mb-6">
            <div>
              <h2 className="text-xl font-semibold mb-1">
                Numéro de réservation
              </h2>
              <p className="text-2xl font-bold text-primary-600">
                {booking.bookingNumber}
              </p>
            </div>
            <span className="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
              {booking.status === 'pending' ? 'En attente' : 'Confirmée'}
            </span>
          </div>

          {/* Informations client */}
          <div className="mb-6">
            <h3 className="font-semibold mb-3">Vos informations</h3>
            <div className="grid grid-cols-2 gap-4 text-sm">
              <div>
                <p className="text-gray-600">Nom complet</p>
                <p className="font-medium">{booking.customer?.firstName} {booking.customer?.lastName}</p>
              </div>
              <div>
                <p className="text-gray-600">Email</p>
                <p className="font-medium">{booking.customer?.email}</p>
              </div>
              <div>
                <p className="text-gray-600">Téléphone</p>
                <p className="font-medium">{booking.customer?.phone}</p>
              </div>
            </div>
          </div>

          {/* Détails de la voiture */}
          <div className="mb-6">
            <h3 className="font-semibold mb-3">Voiture</h3>
            <p className="text-lg font-medium">
              {booking.car?.brand} {booking.car?.model}
            </p>
          </div>

          {/* Dates et lieu */}
          <div className="mb-6">
            <h3 className="font-semibold mb-3">Dates et lieu</h3>
            <div className="space-y-2 text-sm">
              <div className="flex justify-between">
                <span className="text-gray-600">Prise en charge</span>
                <span className="font-medium">{formatDateTime(booking.startDate)}</span>
              </div>
              <div className="flex justify-between">
                <span className="text-gray-600">Retour</span>
                <span className="font-medium">{formatDateTime(booking.endDate)}</span>
              </div>
              <div className="flex justify-between">
                <span className="text-gray-600">Lieu</span>
                <span className="font-medium">{booking.pickupPoint?.name}</span>
              </div>
              <div className="flex justify-between">
                <span className="text-gray-600">Durée</span>
                <span className="font-medium">
                  {booking.numberOfDays} jour{booking.numberOfDays > 1 ? 's' : ''}
                </span>
              </div>
            </div>
          </div>

          {/* Prix */}
          <div className="border-t pt-4">
            <div className="flex justify-between items-center">
              <span className="text-gray-600">Prix total</span>
              <span className="text-2xl font-bold text-primary-600">
                {formatPrice(booking.totalPrice)}
              </span>
            </div>
          </div>
        </div>

        {/* Actions */}
        <div className="flex flex-col sm:flex-row gap-4">
          <Button
            variant="outline"
            className="flex-1 flex items-center justify-center gap-2"
            onClick={() => window.print()}
          >
            <Download className="h-5 w-5" />
            Télécharger le reçu
          </Button>
          <Button
            className="flex-1 flex items-center justify-center gap-2"
            onClick={() => navigate('/')}
          >
            <Home className="h-5 w-5" />
            Retour à l'accueil
          </Button>
        </div>

        {/* Informations supplémentaires */}
        <div className="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
          <h3 className="font-semibold text-blue-900 mb-2">
            Prochaines étapes
          </h3>
          <ul className="text-sm text-blue-800 space-y-1">
            <li>• Vous recevrez un email de confirmation avec tous les détails</li>
            <li>• Présentez-vous au point de collecte avec votre pièce d'identité</li>
            <li>• En cas de questions, contactez-nous au +212 5 37 XX XX XX</li>
          </ul>
        </div>
      </div>
    </div>
  );
};

export default Confirmation;