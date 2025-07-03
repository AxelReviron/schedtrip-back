<script setup lang="ts">
import {MapPinned, Search, Globe, Grip, Trash2} from "lucide-vue-next";
import {useI18n} from "vue-i18n";
import TitleIcon from "@/components/TitleIcon.vue";
import {useTripFormStore} from "@/stores/tripFormStore";
import {storeToRefs} from "pinia";
import {VueDraggable} from "vue-draggable-plus";
import {ref, watch} from "vue";
import axios from "axios";
import {useNotification} from "@/composables/useNotification";
import Notification from "@/components/Notification.vue";
import RemoveStopModal from "@/components/Trip/RemoveStopModal.vue";

const {t} = useI18n();

const { notification, showNotification } = useNotification();

const tripFormStore = useTripFormStore();
const {trip} = storeToRefs(tripFormStore);
const localStops = ref([]);
const placeSearched = ref<string>(null);
const isModalVisible = ref(false);

function handleRemoveStopModalVisibility() {
    isModalVisible.value = !isModalVisible.value;
}

/**
 * Extract markers from store stops before save it locally
 * @param stop
 */
function cloneStopData(stop) {
    if (!stop) return null;
    const { markers, ...rest } = stop;
    return { ...rest };
}

/**
 * Update local stops on store stops changes
 */
watch(() => trip.value.stops, (newStops) => {
    localStops.value = newStops.map(cloneStopData);
}, { immediate: true, deep: true });

/**
 * Update stop order_index and order in array (locally and store)
 */
function updateStopOrder() {
    localStops.value.forEach((stop, index) => {
        stop.order_index = index;// Update local order_index
    });

    const newOrderedTripStops = [];
    localStops.value.forEach(reorderedStopData => {
        // Find original stop in the store
        const originalStopWithMarker = trip.value.stops.find(s =>
            s.latitude === reorderedStopData.latitude &&
            s.longitude === reorderedStopData.longitude &&
            s.label === reorderedStopData.label
        );

        newOrderedTripStops.push(originalStopWithMarker);
    });

    newOrderedTripStops.forEach((stop, index) => {
        stop.order_index = index;// Update store order_index
    });

    tripFormStore.trip.stops = newOrderedTripStops;
    // TODO: Mettre a jour itineraire
}

// TODO: Recherche et gérer affichage
async function handlePlaceSearch(e) {
    e.preventDefault();
    try {
        const response = await axios.get(`/api/ors/search/${placeSearched.value}`);
    } catch (error: any) {
        if (error.response && error.response.status === 422) {// Validation errors
            errors.value = error.response.data.errors;
            showNotification(t("trip.form.create_trip.notification.error.form"), 'error', 5000);
        } else {// Other errors
            showNotification(t("trip.form.create_trip.notification.error.server"), 'error', 5000);
        }
    }
}

</script>

<template>
    <div class="bg-white border border-gray-200 mt-8 rounded-sm px-4 py-4 shadow-xs w-full lg:w-4/12 lg:h-[79vh]">
        <div class="flex flex-row justify-between items-center">
            <TitleIcon
                :title="t('trip.form.create_trip.destinations.title')"
                :icon="MapPinned"
            />
            <h4 class="text-dark text-[1rem]">
                {{ trip.stops.length }} {{ $t("trip.form.create_trip.destinations.stops") }}
            </h4>
        </div>

        <form class="mt-4 flex flex-row gap-2" name="searchPlace" method="GET" @submit="handlePlaceSearch">
            <input
                type="search"
                v-model="placeSearched"
                :placeholder="t('trip.form.create_trip.destinations.search_placeholder')"
                class="bg-white/70 border border-warm p-2 rounded-sm text-warm focus:outline-warm w-full"
                name="search" required
                id="search"
            >
            <button
                class="flex flex-row gap-2 items-center border border-warm py-2 bg-warm font-medium rounded-sm px-4 cursor-pointer hover:bg-warmer"
                @click="handlePlaceSearch"
            >
                <Search
                    class="text-white"
                    size="20"
                />
            </button>
        </form>

        <div class="w-full border border-1 my-4 rounded-sm text-warm opacity-30"></div>

        <div v-if="localStops && localStops.length > 0 && localStops[0].latitude">
        <VueDraggable
                v-model="localStops" @end="updateStopOrder"
                handle=".handle" ghostClass="ghost"
            >
                <div
                    v-for="(stop, index) in localStops" :key="`${stop.latitude}-${stop.longitude}`"
                    class="bg-white border border-warm mt-2 rounded-sm px-4 py-4 shadow-xs flex flex-row gap-2"
                >
                    <RemoveStopModal
                        :stop-index="index"
                        v-if="isModalVisible"
                        @toggle-visibility="handleRemoveStopModalVisibility"
                    />
                    <div class="flex flex-col gap-4">
                        <Grip
                            class="text-dark hover:text-dark/50 cursor-grab handle"
                        />
                        <Trash2
                            class="text-red-500 hover:text-red-600 cursor-pointer"
                            @click="handleRemoveStopModalVisibility"
                        />
                    </div>
                    <div class="w-full">
                        <input
                            type="text"
                            v-model="stop.label"
                            :placeholder="t('trip.form.create_trip.destinations.destination_placeholder')"
                            class="bg-white/70 border border-warm p-2 rounded-sm text-dark focus:outline-warm w-full"
                            name="destination"
                            id="destination"
                        />
                        <div v-if="stop.notes && stop.notes.length > 0">
                            <div v-for="note in stop.notes" :key="note.id">
                            <textarea
                                rows="2"
                                v-model="note.content"
                                :placeholder="t('trip.form.create_trip.destinations.note_placeholder')"
                                class="mt-2 w-full border border-warm p-2 rounded-lg text-dark focus:outline-warm resize-none"
                                name="note" required
                                id="note"
                            >
                            </textarea>
                            </div>
                        </div>
                        <div class="text-warm text-sm flex flex-row items-center gap-2">
                            <Globe
                                size="18"
                            />
                            <p>{{ stop.latitude }}, {{ stop.longitude }}</p>
                        </div>
                    </div>
                </div>
            </VueDraggable>
        </div>

        <div v-else class="flex flex-col items-center justify-center mt-8">
            <MapPinned
                size="42"
                class="text-warm"
            />
            <h3 class="text-xl text-warm">
                {{ $t("trip.form.create_trip.destinations.no_destinations") }}
            </h3>
            <h4 class="text-[1rem] text-warm text-center">
                {{ $t("trip.form.create_trip.destinations.start_destinations") }}
            </h4>
        </div>
    </div>
    <Notification :notification="notification"/>
</template>

<style scoped>
.ghost {
    opacity: 0.5;
    background: #FAEDCD;
}
input[type="search"]::-webkit-search-cancel-button {
    -webkit-appearance: none;
    appearance: none;
    display: none;
}

</style>
