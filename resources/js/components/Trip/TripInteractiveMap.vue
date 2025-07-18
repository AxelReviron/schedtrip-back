<script setup lang="ts">
import TitleIcon from "@/components/TitleIcon.vue";
import {Map} from "lucide-vue-next";
import "leaflet/dist/leaflet.css"
import * as L from 'leaflet';
import {useI18n} from "vue-i18n";
import {onMounted, ref, watch} from "vue";
import {useTripFormStore} from "@/stores/tripFormStore";
import {storeToRefs} from "pinia";
import axios from "axios";
import {useNotification} from "@/composables/useNotification";
import Notification from "@/components/Notification.vue";
import useCreateStopForStore from "@/composables/useCreateStopForStore";
import {usePage} from "@inertiajs/vue3";

const {t} = useI18n();
const { notification, showNotification } = useNotification();

const tripFormStore = useTripFormStore();
const { trip } = storeToRefs(tripFormStore);

const isLoading = ref(false);

const mapEl = ref<HTMLElement>();
const interactiveMap = ref(undefined);
const displayedMarkers = ref<L.Marker[]>([]);
const displayedRoute = ref<L.geoJSON>(null);

const props = defineProps<{
    isEditable: boolean,
}>();
const { isEditable } = props;

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

function clearRoute() {
    if (interactiveMap.value && displayedRoute.value && interactiveMap.value.hasLayer(displayedRoute.value)) {
        interactiveMap.value.removeLayer(displayedRoute.value);
    }
    displayedRoute.value = null;
}

function displayRouteFromStore() {
    if (!interactiveMap.value || !trip.value?.stops) return;

    clearRoute();
    if (trip.value.geojson) {
        const storedGeoJsonLayer = L.geoJSON(trip.value.geojson, {style: geoStyler});
        displayedRoute.value = storedGeoJsonLayer;
        storedGeoJsonLayer.addTo(interactiveMap.value);

        interactiveMap.value.fitBounds(storedGeoJsonLayer.getBounds());
    } else if (trip.value.stops && trip.value.stops.length >= 2) {
        getRoute();
    }
}

function geoStyler() {
    return {
        color: '#8B4513',
        weight: 4,
        opacity: 0.8,
    };
}

function createCustomMarkerIcon(orderIndex: number | null): L.DivIcon {
    return L.divIcon({
        className: '', // On met tout dans `html` directement
        html: `
            <div class="relative w-8 h-8 bg-dark text-cream font-bold border border-warm rounded-full text-center drop-shadow-lg flex items-center justify-center text-[1rem]">
                <span class="relative z-20">${orderIndex ?? ''}</span>
                <div class="w-6 h-6 bg-dark absolute left-[0.2rem] top-[1.55rem] z-10" style="clip-path: polygon(50% 100%, 0% 0%, 100% 0%);"></div>
            </div>
        `,
        iconSize: [31, 31],
        iconAnchor: [15, 31],
        popupAnchor: [0, -30]
    });
}

function displayMarkersFromStore() {
    if (!interactiveMap.value || !trip.value?.stops) return;

    clearAllMarkers();
    const hasCalculatedRoute = ref(false);

    trip.value.stops.forEach(stop => {
        const hasNoMarker = ref(false);
        if (stop.markers && stop.markers.length > 0) {
            stop.markers.forEach(marker => {
                const coords = {
                    lat: stop.latitude,
                    lng: stop.longitude,
                }
                const icon = createCustomMarkerIcon(stop.order_index);
                if (!marker) {// Stops added from search bar (Destinations) doesn't have marker
                    marker = L.marker(coords);
                    hasNoMarker.value = true;
                }
                marker.setIcon(icon);
                marker.addTo(interactiveMap.value);
                displayedMarkers.value.push(marker);
                interactiveMap.value.setView(coords, 5);
                if (hasNoMarker.value && !hasCalculatedRoute.value) {// We need to get only once the route
                    displayRouteFromStore();
                    hasCalculatedRoute.value = true;
                    hasNoMarker.value = false;
                }
            });
        } else if (stop.markers) {
            const icon = createCustomMarkerIcon(stop.order_index);
            const coords = {
                lat: stop.latitude,
                lng: stop.longitude,
            }
            const marker = L.marker(coords);
            marker.setIcon(icon);
            marker.addTo(interactiveMap.value);
            displayedMarkers.value.push(marker);
            interactiveMap.value.setView(coords, 5);
        }
    });
}

async function addStopToTrip(coords: L.LatLng, marker: L.Marker) {
    try {
        const response = await axios.post('/api/ors/reverse-search', {
            lat: coords.lat,
            lon: coords.lng,
        });

        const stop = useCreateStopForStore(response.data.label, response.data.latitude, response.data.longitude, marker);
        tripFormStore.addStop(stop);
    } catch (error: any) {
        const stop = createStopForStore(null, roundCoord(coords.lat), roundCoord(coords.lng), marker);

        tripFormStore.addStop(stop);
        if (error.response && error.response.status === 422) {// Validation errors
            showNotification(t("trip.form.create_trip.notification.error.form"), 'error', 5000);
        } else if (error.response && error.response.status === 429) {// Rate Limit
            showNotification(t("trip.form.create_trip.notification.error.rate_limit"), 'error', 10000);
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

        tripFormStore.addGeoJson(response.data);
        clearRoute();

        const newRouteLayer = L.geoJSON(trip.value.geojson, {style: geoStyler});
        newRouteLayer.addTo(interactiveMap.value);
        displayedRoute.value = newRouteLayer;
    } catch (error:any) {
        if (error.response && error.response.status === 429) {// Rate Limit
            showNotification(t("trip.form.create_trip.notification.error.rate_limit"), 'error', 10000);
        } else {// Other errors
            showNotification(t("trip.form.create_trip.notification.error.route_error"), 'error', 5000);
        }
    }
}

async function handleMapClick(e: L.LeafletMouseEvent) {
    if (isEditable && !isLoading.value) {
        isLoading.value = true;
        const coords = e.latlng;
        const marker = L.marker(coords);

        try {
            await addStopToTrip(coords, marker);
            await getRoute();

            interactiveMap.value.setView(coords, 5);
        } finally {
            isLoading.value = false;
        }
    }
}

function getTileLayerByLocale(locale) {
    const tileLayers = {
        'fr': {
            url: 'https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png',
            attribution: '© OpenStreetMap France'
        },
        'en': {
            url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            attribution: '© OpenStreetMap contributors'
        }
    };

    return tileLayers[locale] || tileLayers['en'];
}

function initMap() {
    interactiveMap.value = L.map(mapEl.value!, {
        zoom: 5,
        maxZoom: 18,
        minZoom: 2,
        center: [45.7580032,4.7939244],// TODO: Se baser sur les coordonées du geojson
        dragging: isEditable,
        scrollWheelZoom: isEditable,
        doubleClickZoom: isEditable,
        touchZoom: isEditable,
        boxZoom: isEditable,
        keyboard: isEditable,
    })

    const locale = usePage().props.locale || 'en';
    const tileConfig = getTileLayerByLocale(locale);

    L.tileLayer(tileConfig.url, {
        attribution: tileConfig.attribution
    }).addTo(interactiveMap.value);

    interactiveMap.value.on('click', handleMapClick);
    displayMarkersFromStore();
}

function setMapInteractivity(enabled: boolean) {
    const map = interactiveMap.value;
    if (!map) return;

    const action = enabled ? 'enable' : 'disable';

    map.dragging[action]();
    map.scrollWheelZoom[action]();
    map.doubleClickZoom[action]();
    map.touchZoom[action]();
    map.boxZoom[action]();
    map.keyboard[action]();
}

/**
 * Trigger when user clicked on the map
 */
watch(() => isLoading.value, (val) => {
    setMapInteractivity(!val);
});

/**
 * Trigger when marker is added to the map.
 * Initially the store contains a stop with null values.
 * When the first marker is added, this first stop is updated.
 * For the others, we check the length of the stops array.
 */
watch(
    () => [trip.value.stops[0]?.latitude, trip.value.stops.length],
    () => {
        displayMarkersFromStore();
    }
);

/**
 * Trigger when the geojson value of a trip is removed from store.
 */
watch(
    () => trip.value.geojson,
    () => {
        displayRouteFromStore();
    }
);

onMounted(() => {
    initMap();
    if (trip.value && trip.value.stops && trip.value.stops.length > 0) {
        displayMarkersFromStore();
    }
});

</script>

<template>
    <div class="bg-white border border-gray-200 mt-8 rounded-sm px-4 py-4 shadow-xs w-full lg:w-8/12">
        <TitleIcon
            :title="t('trip.form.create_trip.interactive_map.title')"
            :icon="Map"
        />
        <div class="relative">
            <div ref="mapEl" class="h-[40vh] lg:h-[70vh] mt-4 rounded-sm"></div>
            <div
                v-if="isLoading"
                class="z-999 absolute inset-0 z-50 bg-white/70 flex items-center justify-center pointer-events-none"
            >
                <div class="w-16 h-16 border-4 border-dark border-t-transparent rounded-full animate-spin"></div>
            </div>
        </div>
    </div>
    <Notification :notification="notification"/>
</template>

<style scoped>

</style>
