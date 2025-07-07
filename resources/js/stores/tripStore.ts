import {defineStore} from "pinia";
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import TripInterface from "@/interfaces/tripInterface";

export const useTripStore = defineStore('trip', () => {
    const trips: TripInterface[] | [] = ref([]);

    const isLoading: boolean = ref(false);
    const page = usePage();

    async function setTrips(tripData: TripInterface[]) {
        trips.value = tripData;
    }

    function addTrip(newTrips: TripInterface[]): void {
        trips.value.push(...newTrips);
    }

    function removeTrip(): void {
        //
    }

    function updateTrip(): void {
        //
    }

    return {
        // States
        trips,
        isLoading,

        // Actions
        setTrips,
        addTrip,
        removeTrip,
        updateTrip,
    }
}, {
    persist: {
        storage: localStorage,
        paths: ['trips'],
    }
});
