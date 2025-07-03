<script setup lang="ts">
import TitleIcon from "@/components/TitleIcon.vue";
import {Map} from "lucide-vue-next";
import "leaflet/dist/leaflet.css"
import * as L from 'leaflet';
import {useI18n} from "vue-i18n";
import {onMounted, ref, watchEffect} from "vue";
import {useTripFormStore} from "@/stores/tripFormStore";
import {storeToRefs} from "pinia";
import axios from "axios";
import {useNotification} from "@/composables/useNotification";
import Notification from "@/components/Notification.vue";

const {t} = useI18n();
const { notification, showNotification } = useNotification();

const tripFormStore = useTripFormStore();
const { trip } = storeToRefs(tripFormStore);

const mapEl = ref<HTMLElement>();
const interactiveMap = ref(undefined);
const displayedMarkers = ref<L.Marker[]>([]);

function roundCoord(value: number): number {
    return Number(value.toFixed(6));
}

function clearAllMarkers() {
    displayedMarkers.value.forEach(marker => {
        if (interactiveMap.value && interactiveMap.value.hasLayer(marker)) {
            interactiveMap.value.removeLayer(marker);
        }
    });
    displayedMarkers.value = [];
}

function displayMarkersFromStore() {
    if (!interactiveMap.value || !trip.value?.stops) return;

    clearAllMarkers();
    trip.value.stops.forEach(stop => {
        if (stop.markers && stop.markers.length > 0) {
            stop.markers.forEach(marker => {
                marker.addTo(interactiveMap.value);
                displayedMarkers.value.push(marker);
            });
        }
    });
}

async function addStopToTrip(coords: L.LatLng, marker: L.Marker) {
    try {
        const response = await axios.post('/api/ors/reverse-search', {
            lat: coords.lat,
            lon: coords.lng,
        });

        const stop = {
            label: response.data.label,
            description: null,
            trip_id: null,
            duration: null,
            latitude: response.data.latitude,
            longitude: response.data.longitude,
            arrival_date: null,
            departure_date: null,
            order_index: null,
            notes: [
                {
                    user_id: null,
                    stop_id: null,
                    content: null,
                }
            ],
            markers: [
                marker
            ]
        };
        tripFormStore.addStop(stop);
    } catch (error: any) {
        const stop = {
            label: null,
            description: null,
            trip_id: null,
            duration: null,
            latitude: roundCoord(coords.lat),
            longitude: roundCoord(coords.lng),
            arrival_date: null,
            departure_date: null,
            order_index: null,
            notes: [
                {
                    user_id: null,
                    stop_id: null,
                    content: null,
                }
            ],
            markers: [
                marker
            ]
        };
        tripFormStore.addStop(stop);
        if (error.response && error.response.status === 422) {// Validation errors
            errors.value = error.response.data.errors;
            showNotification(t("trip.form.create_trip.notification.error.form"), 'error', 5000);
        } else {// Other errors
            showNotification(t("trip.form.create_trip.notification.error.reverse_search_error"), 'error', 5000);
        }
    }
}

async function getRoute() {
    if (!trip.value || !trip.value.stops || trip.value.stops.length < 2) {
        return;
    }

    const StopCoordinates = trip.value.stops.map(stop => [stop.longitude, stop.latitude]);

    try {
        const response = await axios.post('/api/ors/route', {
            coordinates: StopCoordinates
        });
        console.log(response.data);
    } catch (error:any) {
        console.error("Error calculating route:", error);
        // Handle error, e.g., show a notification
        showNotification(t("trip.form.create_trip.notification.error.route_error"), 'error', 5000);
    }
}

async function handleMapClick(e: L.LeafletMouseEvent) {
    const coords = e.latlng;
    const marker = L.marker(coords);

    await addStopToTrip(coords, marker);
    // TODO: Calculer itineraire et gérer affichage
    await getRoute();
}


function initMap() {
    interactiveMap.value = L.map(mapEl.value!, {
        zoom: 5,
        maxZoom: 18,
        minZoom: 2,
        center: [45.7580032,4.7939244],
    })
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(interactiveMap.value);

    interactiveMap.value.on('click', handleMapClick);
    displayMarkersFromStore();
}

watchEffect(() => {
    if (trip.value?.stops) {
        displayMarkersFromStore();
    }
});

onMounted(() => {
    initMap();
});
</script>

<template>
    <div class="bg-white border border-gray-200 mt-8 rounded-sm px-4 py-4 shadow-xs w-full lg:w-8/12">
        <TitleIcon
            :title="t('trip.form.create_trip.interactive_map.title')"
            :icon="Map"
        />

        <div ref="mapEl" class="h-[40vh] lg:h-[70vh] mt-4 rounded-sm"></div>

    </div>
    <Notification :notification="notification"/>
</template>

<style scoped>

</style>
