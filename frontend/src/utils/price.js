// src/utils/price.js
export const formatPrice = (price) => {
  return new Intl.NumberFormat('fr-MA', {
    style: 'currency',
    currency: 'MAD',
    minimumFractionDigits: 0,
  }).format(price);
};

export const calculateTotalPrice = (pricePerDay, numberOfDays) => {
  return pricePerDay * numberOfDays;
};