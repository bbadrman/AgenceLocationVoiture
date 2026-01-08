// src/hooks/useCars.js
import { useState, useEffect } from 'react';
import { carsAPI } from '../api/client';

export const useCars = () => {
  const [cars, setCars] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [searchInfo, setSearchInfo] = useState(null);

  const searchCars = async (searchParams) => {
    try {
      setLoading(true);
      setError(null);
      const response = await carsAPI.search(searchParams);
      setCars(response.data.cars);
      setSearchInfo({
        total: response.data.total,
        period: response.data.period,
        filters: response.data.filters,
      });
    } catch (err) {
      setError(err.response?.data?.message || err.message);
      setCars([]);
    } finally {
      setLoading(false);
    }
  };

  return { cars, loading, error, searchInfo, searchCars };
};

export const useCar = (carId, searchParams = {}) => {
  const [car, setCar] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const fetchCar = async () => {
    try {
      setLoading(true);
      const response = await carsAPI.getById(carId, searchParams);
      setCar(response.data);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (carId) {
      fetchCar();
    }
  }, [carId]);

  return { car, loading, error, fetchCar };
};

export const useAllCars = () => {
  const [cars, setCars] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchCars = async () => {
      try {
        setLoading(true);
        const response = await carsAPI.getAll();
        setCars(response.data);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };
    fetchCars();
  }, []);

  return { cars, loading, error };
};