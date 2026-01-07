// src/hooks/useCities.js
import { useState, useEffect } from 'react';
import { citiesAPI } from '../api/client';

export const useCities = () => {
  const [cities, setCities] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchCities = async () => {
      try {
        setLoading(true);
        const response = await citiesAPI.getAll();
        setCities(response.data);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchCities();
  }, []);

  return { cities, loading, error };
};

export const usePickupPoints = (cityId) => {
  const [pickupPoints, setPickupPoints] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (!cityId) {
      setPickupPoints([]);
      return;
    }

    const fetchPickupPoints = async () => {
      try {
        setLoading(true);
        const response = await citiesAPI.getPickupPoints(cityId);
        setPickupPoints(response.data);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchPickupPoints();
  }, [cityId]);

  return { pickupPoints, loading, error };
};