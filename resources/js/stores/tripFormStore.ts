import {defineStore} from "pinia";
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import StopInterface from "@/interfaces/stopInterface";
import TripInterface from "@/interfaces/tripInterface";

export const useTripFormStore = defineStore('tripForm', () => {
    const trip = ref<TripInterface>({
        label: null,
        description: null,
        distance: null,
        duration: null,
        isPublic: null,
        geojson: null,
        stops: [// TODO: Fake data pour éviter les calls API lors du dev
            {
                arrival_date: null,
                departure_date: null,
                description: null,
                duration: null,
                label: null,
                latitude: null,
                longitude: null,
                order_index: 0,
                trip_id: null,
                notes: [
                    {
                       user_id: null,
                       stop_id: null,
                       content: null,
                    }
                ],
                marker: {},
            },
        ],
        author_id: null,
        participants: [],
    });

    const isLoading: boolean = ref(false);
    const page = usePage();

    function addStop(stopData: StopInterface) {
        // If it's the first "real" stop. Update the existing placeholder.
        if (trip.value.stops.length === 1 && trip.value.stops[0].label === null && trip.value.stops[0].latitude === null) {
            const firstStop = trip.value.stops[0];
            Object.assign(firstStop, {
                ...stopData,
                order_index: 0, // Ensure the first stop always has order_index 0
            });
            if (stopData.markers) {
                firstStop.markers = stopData.markers;
            }
        } else {// For all subsequent stops, push them into the array
            stopData.order_index = trip.value.stops.length;
            if (!stopData.label) {
                stopData.label = `Destination ${stopData.order_index + 1}`;
            }
            trip.value.stops.push(stopData);
        }
    }

    function removeStop(stopIndex: number) {
        if (stopIndex >= 0 && stopIndex < trip.value.stops.length) {
            trip.value.stops.splice(stopIndex, 1);
            // Calculate order_index after stop deletion
            trip.value.stops.forEach((stop, index) => {
                stop.order_index = index;
            });
        }
    }

    return {
        // States
        trip,

        // Actions
        addStop,
        removeStop,
    }
})
