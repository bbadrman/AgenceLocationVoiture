// src/pages/Home.jsx
import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Calendar, MapPin, Car } from 'lucide-react';
import SearchForm from '../components/Search/SearchForm';

const Home = () => {
  const navigate = useNavigate();

  const handleSearch = (searchParams) => {
    const queryString = new URLSearchParams(searchParams).toString();
    navigate(`/search?${queryString}`);
  };

  return (
    <div className="min-h-screen">
      {/* Hero Section */}
      <div className="bg-gradient-to-r from-primary-600 to-primary-800 text-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
          <div className="text-center">
            <h1 className="text-4xl md:text-6xl font-bold mb-6">
              Louez votre voiture idéale
            </h1>
            <p className="text-xl md:text-2xl mb-12 text-primary-100">
              Des véhicules de qualité dans tout le Maroc
            </p>
          </div>

          {/* Formulaire de recherche */}
          <div className="max-w-4xl mx-auto">
            <SearchForm onSearch={handleSearch} />
          </div>
        </div>
      </div>

      {/* Features Section */}
      <div className="py-16 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-gray-900 mb-4">
              Pourquoi nous choisir ?
            </h2>
            <p className="text-gray-600">
              Une expérience de location simple et sans tracas
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div className="bg-white p-8 rounded-lg shadow-md text-center">
              <div className="inline-flex items-center justify-center w-16 h-16 bg-primary-100 text-primary-600 rounded-full mb-4">
                <Car className="h-8 w-8" />
              </div>
              <h3 className="text-xl font-semibold mb-2">Large choix</h3>
              <p className="text-gray-600">
                Une sélection variée de véhicules pour tous vos besoins
              </p>
            </div>

            <div className="bg-white p-8 rounded-lg shadow-md text-center">
              <div className="inline-flex items-center justify-center w-16 h-16 bg-primary-100 text-primary-600 rounded-full mb-4">
                <MapPin className="h-8 w-8" />
              </div>
              <h3 className="text-xl font-semibold mb-2">Plusieurs villes</h3>
              <p className="text-gray-600">
                Disponible dans les principales villes du Maroc
              </p>
            </div>

            <div className="bg-white p-8 rounded-lg shadow-md text-center">
              <div className="inline-flex items-center justify-center w-16 h-16 bg-primary-100 text-primary-600 rounded-full mb-4">
                <Calendar className="h-8 w-8" />
              </div>
              <h3 className="text-xl font-semibold mb-2">Réservation facile</h3>
              <p className="text-gray-600">
                Réservez en quelques clics, confirmez instantanément
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* CTA Section */}
      <div className="bg-primary-600 text-white py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h2 className="text-3xl font-bold mb-4">
            Prêt à partir à l'aventure ?
          </h2>
          <p className="text-xl mb-8 text-primary-100">
            Trouvez la voiture parfaite pour votre prochain voyage
          </p>
          <button
            onClick={() => navigate('/search')}
            className="bg-white text-primary-600 font-semibold px-8 py-3 rounded-lg hover:bg-gray-100 transition"
          >
            Voir toutes les voitures
          </button>
        </div>
      </div>
    </div>
  );
};

export default Home;