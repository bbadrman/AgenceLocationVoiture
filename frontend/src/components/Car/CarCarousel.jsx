// src/components/Car/CarCarousel.jsx
import React, { useRef } from 'react';
import { ChevronLeft, ChevronRight, Loader } from 'lucide-react';
import { useAllCars } from '../../hooks/useCars';
import CarCard from './CarCard';

const CarCarousel = () => {
    const { cars, loading, error } = useAllCars();
    const scrollContainerRef = useRef(null);

    const scroll = (direction) => {
        const container = scrollContainerRef.current;
        if (container) {
            const scrollAmount = container.clientWidth;
            container.scrollBy({
                left: direction === 'left' ? -scrollAmount : scrollAmount,
                behavior: 'smooth',
            });
        }
    };

    if (loading) {
        return (
            <div className="flex justify-center items-center h-64">
                <Loader className="w-8 h-8 animate-spin text-primary-600" />
            </div>
        );
    }

    if (error) {
        return (
            <div className="text-center text-red-600 py-8">
                Une erreur est survenue lors du chargement des voitures.
            </div>
        );
    }

    if (!cars || cars.length === 0) {
        return null;
    }

    return (
        <div className="relative group">
            {/* Navigation Buttons */}
            <button
                onClick={() => scroll('left')}
                className="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 z-10 bg-white p-2 rounded-full shadow-lg text-gray-800 hover:text-primary-600 focus:outline-none hidden md:flex opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                aria-label="Previous"
            >
                <ChevronLeft className="w-6 h-6" />
            </button>

            <button
                onClick={() => scroll('right')}
                className="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 z-10 bg-white p-2 rounded-full shadow-lg text-gray-800 hover:text-primary-600 focus:outline-none hidden md:flex opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                aria-label="Next"
            >
                <ChevronRight className="w-6 h-6" />
            </button>

            {/* Carousel Container */}
            <div
                ref={scrollContainerRef}
                className="flex overflow-x-auto gap-6 pb-8 snap-x snap-mandatory scrollbar-hide -mx-4 px-4 md:mx-0 md:px-0"
                style={{ scrollbarWidth: 'none', msOverflowStyle: 'none' }}
            >
                {cars.map((car) => (
                    <div
                        key={car.id}
                        className="flex-none w-[85%] sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] snap-start"
                    >
                        <CarCard car={car} />
                    </div>
                ))}
            </div>
        </div>
    );
};

export default CarCarousel;
