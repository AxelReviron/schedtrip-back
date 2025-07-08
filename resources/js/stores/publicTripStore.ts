import {defineStore} from "pinia";
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import TripInterface from "@/interfaces/tripInterface";

export const usePublicTripStore = defineStore('publicTrip', () => {
    const trips: TripInterface[] | [] = ref([]);

    const isLoading: boolean = ref(false);
    const page = usePage();

    function setTrips(tripData: TripInterface[]) {
        trips.value = tripData;
    }

    return {
        // States
        trips,
        isLoading,

        // Actions
        setTrips,
    }
});
