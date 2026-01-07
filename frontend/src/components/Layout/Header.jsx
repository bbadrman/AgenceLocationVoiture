// src/components/Layout/Header.jsx
import React from 'react';
import { Link } from 'react-router-dom';
import { Car } from 'lucide-react';

const Header = () => {
  return (
    <header className="bg-white shadow-sm">
      <nav className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between h-16">
          <div className="flex items-center">
            <Link to="/" className="flex items-center gap-2 text-primary-600 font-bold text-xl">
              <Car className="h-8 w-8" />
              <span>Location Auto</span>
            </Link>
          </div>
          
          <div className="flex items-center gap-6">
            <Link to="/" className="text-gray-700 hover:text-primary-600 transition">
              Accueil
            </Link>
            <Link to="/search" className="text-gray-700 hover:text-primary-600 transition">
              Recherche
            </Link>
            <Link to="/about" className="text-gray-700 hover:text-primary-600 transition">
              À propos
            </Link>
            <Link to="/contact" className="text-gray-700 hover:text-primary-600 transition">
              Contact
            </Link>
          </div>
        </div>
      </nav>
    </header>
  );
};

export default Header;