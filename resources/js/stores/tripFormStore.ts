import {defineStore} from "pinia";
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import StopInterface from "@/interfaces/stopInterface";
import TripInterface from "@/interfaces/tripInterface";
import UserInterface from "@/interfaces/userInterface";

export const useTripFormStore = defineStore('tripForm', () => {
    const trip = ref<TripInterface>({// TODO: Use TripFormInterface
        label: null,
        description: null,
        distance: null,
        duration: null,
        isPublic: null,
        geojson: null,
        stops: [
            {
                arrival_date: null,
                departure_date: null,
                description: null,
                duration: null,
                label: null,
                latitude: null,
                longitude: null,
                order_index: 1,
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
                order_index: 1, // First stop always has order_index 1
            });
            if (stopData.markers) {
                firstStop.markers = stopData.markers;
            }
        } else {// For all subsequent stops, push them into the array
            stopData.order_index = trip.value.stops.length + 1;
            if (!stopData.label) {
                stopData.label = `Destination ${stopData.order_index + 1}`;
            }
            trip.value.stops.push(stopData);
        }
    }

    function removeStop(stopToRemove: StopInterface) {
        console.log(stopToRemove);
        trip.value.stops = trip.value.stops.filter(stop =>
            !(stop.latitude === stopToRemove.latitude && stop.longitude === stopToRemove.longitude)
        );

        // Recalcul des index après suppression
        trip.value.stops.forEach((stop, index) => {
            stop.order_index = index + 1;
        });
    }

    /**
     * Updates the order of trip stops based on a reordered list of local stops.
     * For each stop in the reordered list, this function finds the corresponding original stop
     * in the store, updates its `order_index` and reorders it.
     *
     * @param reorderedLocalStops
     */
    function updateStopOrder(reorderedLocalStops: StopInterface[]) {
        trip.value.stops = reorderedLocalStops.map((reorderedStop: StopInterface, index: number) => {
            const originalStop = trip.value.stops.find(s =>
                s.latitude === reorderedStop.latitude &&
                s.longitude === reorderedStop.longitude &&
                s.label === reorderedStop.label
            );

            if (originalStop) {
                originalStop.order_index = index + 1;
            } else {
                console.warn('Stop not found in trip store:', reorderedStop);
            }

            return originalStop;
        });
    }

    function addParticipant(friend: UserInterface, permission: string) {
        trip.value.participants.push(
            {
                user_id: friend.id,
                permission: permission
            }
        );
    }

    function updateParticipantPermission(participantId: string, newPermission: string) {
        trip.value.participants.forEach((participant) => {
            if (participant.user_id === participantId) {
                participant.permission = newPermission;
            }
        })
    }

    function removeGeoJson() {
        trip.value.geojson = null;
    }

    return {
        // States
        trip,

        // Actions
        addStop,
        removeStop,
        updateStopOrder,
        removeGeoJson,
        addParticipant,
        updateParticipantPermission,
    }
})
