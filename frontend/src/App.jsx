// src/App.jsx
import React from 'react';
import { Routes, Route } from 'react-router-dom';
import Layout from './components/Layout/Layout';
import Home from './pages/Home';
import SearchResults from './pages/SearchResults';
import CarDetail from './pages/CarDetail';
import Booking from './pages/Booking';
import Confirmation from './pages/Confirmation';

function App() {
  return (
    <Layout>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/search" element={<SearchResults />} />
        <Route path="/car/:id" element={<CarDetail />} />
        <Route path="/booking/:id" element={<Booking />} />
        <Route path="/confirmation/:bookingNumber" element={<Confirmation />} />
      </Routes>
    </Layout>
  );
}

export default App;