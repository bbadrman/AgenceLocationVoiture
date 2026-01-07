// src/hooks/useBooking.js
import { useState } from 'react';
import { bookingsAPI } from '../api/client';

export const useBooking = () => {
  const [booking, setBooking] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const createBooking = async (bookingData) => {
    try {
      setLoading(true);
      setError(null);
      const response = await bookingsAPI.create(bookingData);
      setBooking(response.data.booking);
      return response.data.booking;
    } catch (err) {
      const errorMessage = err.response?.data?.message || err.message;
      setError(errorMessage);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  const getBooking = async (bookingNumber) => {
    try {
      setLoading(true);
      setError(null);
      const response = await bookingsAPI.getByNumber(bookingNumber);
      setBooking(response.data);
      return response.data;
    } catch (err) {
      setError(err.message);
      throw err;
    } finally {
      setLoading(false);
    }
  };

  return { booking, loading, error, createBooking, getBooking };
};