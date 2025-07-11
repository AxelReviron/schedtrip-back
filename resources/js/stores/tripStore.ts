import {defineStore} from "pinia";
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import TripInterface from "@/interfaces/tripInterface";

export const useTripStore = defineStore('trip', () => {
    const trips: TripInterface[] | [] = ref([]);

    async function setTrips(tripData: TripInterface[]) {
        trips.value = tripData;
    }

    function addTrip(newTrips: TripInterface[]): void {
        trips.value.push(...newTrips);
    }

    return {
        // States
        trips,

        // Actions
        setTrips,
        addTrip,
    }
}, {
    persist: {
        storage: localStorage,
        paths: ['trips'],
    }
});
