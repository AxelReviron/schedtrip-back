import {defineStore} from "pinia";
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import StopInterface from "@/interfaces/stopInterface";
import TripParticipantsFormInterface from "@/interfaces/tripParticipantsForm";
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
                label: "61 Avenue du Point du Jour, Lyon, France",
                latitude: 45.756117,
                longitude: 4.800602,
                order_index: 0,
                trip_id: null,
                notes: [
                    {
                       user_id: null,
                       stop_id: null,
                       content: null,
                    }
                ],
            },
            {
                arrival_date: null,
                departure_date: null,
                description: null,
                duration: null,
                label: "Avenue des Champs-Élysées, Paris, France",
                latitude: 48.865855,
                longitude: 2.320158,
                order_index: 0,
                trip_id: null,
                notes: [
                    {
                       user_id: null,
                       stop_id: null,
                       content: null,
                    }
                ],
            }
        ],
        author_id: null,
        participants: [],
    });

    const isLoading: boolean = ref(false);
    const page = usePage();

    function addStop(stopData: StopInterface) {
        stopData.order_index = trip.value.stops.length;
        trip.value.stops.push(stopData);
        console.log(trip.value);
    }

    return {
        // States
        trip,

        // Actions
        addStop,
    }
})
