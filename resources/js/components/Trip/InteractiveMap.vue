<script setup lang="ts">
import TitleIcon from "@/components/TitleIcon.vue";
import {Map} from "lucide-vue-next";
import "leaflet/dist/leaflet.css"
import * as L from 'leaflet';
import {useI18n} from "vue-i18n";
import {onMounted, ref} from "vue";
import {useTripFormStore} from "@/stores/tripFormStore";
import {storeToRefs} from "pinia";
import axios from "axios";

const {t} = useI18n();

const tripFormStore = useTripFormStore();

const { trip } = storeToRefs(tripFormStore);

const mapEl = ref<HTMLElement>();
const interactiveMap = ref(undefined);

async function addStopToTrip(coords: L.LatLng) {
    const response = await axios.post('/api/ors/reverse-search', {
        lat: coords.lat,
        lon: coords.lng,
    });

    const stop = {
        label: response.data[0].label,
        description: null,
        trip_id: null,
        duration: null,
        latitude: response.data[0].latitude,
        longitude: response.data[0].longitude,
        arrival_date: null,
        departure_date: null,
        order_index: null,
        notes: [
            {
                user_id: null,
                stop_id: null,
                content: null,
            }
        ]
    };
    tripFormStore.addStop(stop);
}

async function handleMapClick(e: L.LeafletMouseEvent) {
    const coords = e.latlng;
    L.marker(coords).addTo(interactiveMap.value);
    await addStopToTrip(coords);
    // TODO: Calculer itineraire et gérer affichage
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
}

onMounted(() => {
    initMap();
});
</script>

<template>
    <div class="bg-white border border-gray-200 mt-8 rounded-sm px-4 py-4 shadow-xs w-8/12">
        <TitleIcon
            :title="t('trip.form.create_trip.interactive_map.title')"
            :icon="Map"
        />

        <div ref="mapEl" class="h-[70vh] mt-4 rounded-sm"></div>

    </div>
</template>

<style scoped>

</style>
