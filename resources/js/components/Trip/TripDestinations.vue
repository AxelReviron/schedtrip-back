<script setup lang="ts">
import {MapPinned, Search, Grip, Trash2, CalendarArrowUp, CalendarArrowDown} from "lucide-vue-next";
import {useI18n} from "vue-i18n";
import TitleIcon from "@/components/TitleIcon.vue";
import {useTripFormStore} from "@/stores/tripFormStore";
import {storeToRefs} from "pinia";
import {VueDraggable} from "vue-draggable-plus";
import {ref, watch} from "vue";
import axios from "axios";
import {useNotification} from "@/composables/useNotification";
import Notification from "@/components/Notification.vue";
import RemoveStopModal from "@/components/Trip/Modals/RemoveStopModal.vue";
import SearchDestinationResultInterface from "@/interfaces/searchDestinationResultInterface";
import useCreateStopForStore from "@/composables/useCreateStopForStore";

const props = defineProps<{
    errors: object,
    isEditable: boolean,
}>();

const { errors, isEditable } = props;

const {t} = useI18n();
const { notification, showNotification } = useNotification();

const tripFormStore = useTripFormStore();
const {trip} = storeToRefs(tripFormStore);
const localStops = ref([]);
const placeSearched = ref<string>(null);
const isModalVisible = ref(false);
const selectedStopToRemove = ref(null);
const searchResults = ref<SearchDestinationResultInterface[]|[]>([]);

function handleRemoveStopModalVisibility(stop = null) {
    if (isEditable) {
        selectedStopToRemove.value = stop;
        isModalVisible.value = !!stop;
    }
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
 * Trigger when user start a new search.
 */
watch(
    () => placeSearched.value,
    () => {
        if (searchResults.value && searchResults.value.length > 0) {
            searchResults.value = [];
        }
    }
);

/**
 * Trigger when marker is added to the map.
 * Initially the store contains a stop with null values.
 * When the first marker is added, this first stop is updated.
 * For the others, we check the length of the stops array.
 */
watch(
    () => trip.value.stops.map(s => [s.label, s.latitude, s.longitude, s.order_index]),
    () => {
        localStops.value = trip.value.stops.map(cloneStopData);
    },
    { immediate: true }
);

/**
 * Update stop order_index after a drag and drop.
 * VueDraggable component reorder the localStops for us.
 */
function updateStopOrder() {
    localStops.value.forEach((stop, index) => {
       stop.order_index = index + 1;// Update local order_index
    });

    tripFormStore.updateStopOrder(localStops.value);
    tripFormStore.removeGeoJson();
}

function filterSearchResult(results: SearchDestinationResultInterface): SearchDestinationResultInterface {
    const uniqueResults = [];
    const seenCombinations = new Set();

    results.forEach((result) => {
        const combination = `${result.locality}-${result.region}-${result.country}`;

        if (!seenCombinations.has(combination)) {
            uniqueResults.push(result);
            seenCombinations.add(combination);
        }
    });

    return uniqueResults;
}

async function handlePlaceSearch(e) {
    e.preventDefault();
    if (!placeSearched.value || placeSearched.value === '') return;

    try {
        const response = await axios.get(`/api/ors/search/${placeSearched.value}`);
        searchResults.value = filterSearchResult(response.data);
        if (!searchResults.value.length > 0) {
            showNotification(t("trip.form.create_trip.notification.error.search_place_error"), 'error', 5000);
        }
    } catch (error: any) {
        if (error.response && error.response.status === 422) {// Validation errors
            errors.value = error.response.data.errors;
            showNotification(t("trip.form.create_trip.notification.error.form"), 'error', 5000);
        } else if (error.response && error.response.status === 429) {// Rate Limit
            showNotification(t("trip.form.create_trip.notification.error.rate_limit"), 'error', 10000);
        } else {// Other errors
            showNotification(t("trip.form.create_trip.notification.error.server"), 'error', 5000);
        }
    }
}

function addStopToTrip(searchResult: SearchDestinationResultInterface) {
    const label = `${searchResult.locality ? searchResult.locality : searchResult.name},  ${searchResult.region},  ${searchResult.country}`;
    const stop = useCreateStopForStore(label, searchResult.latitude, searchResult.longitude);
    tripFormStore.addStop(stop);
    placeSearched.value = null;
}

function openDatePicker(e: any, isInputElement: bool) {
    if (isEditable) {
        if (isInputElement) {
            e.target.focus()
            e.target.showPicker()
        } else {
            e.target.nextElementSibling.focus()
            e.target.nextElementSibling.showPicker()
        }
    }
}

function updateDate(event: Event, field: string, stop) {
    const target = event.target as HTMLInputElement;
    tripFormStore.updateStopDates(target.value, field, stop);
}

function updateLabel(event: Event, stop) {
    const target = event.target as HTMLInputElement;
    tripFormStore.updateStopLabel(target.value, stop);
}
</script>

<template>
    <div class="bg-white border border-gray-200 mt-8 rounded-sm px-4 py-4 shadow-xs w-full lg:w-4/12 min-h-[79vh]">
        <div class="flex flex-row justify-between items-center">
            <TitleIcon
                :title="t('trip.form.create_trip.destinations.title')"
                :icon="MapPinned"
            />
            <h4 class="text-dark text-[1rem]">
                {{ localStops[0] && localStops[0].latitude ? trip.stops.length : '0' }} {{ $t("trip.form.create_trip.destinations.stops") }}
            </h4>
        </div>

        <form class="mt-4 flex flex-row gap-2" name="searchPlace" method="GET" @submit="handlePlaceSearch">
            <input
                :disabled="!isEditable"
                type="search"
                v-model="placeSearched"
                :placeholder="t('trip.form.create_trip.destinations.search_placeholder')"
                class="bg-white/70 border border-warm p-2 rounded-sm text-warm focus:outline-warm w-full disabled:cursor-not-allowed"
                name="search" required
                id="search"
            >
            <button
                :disabled="!isEditable"
                class="flex flex-row gap-2 items-center border border-warm py-2 bg-warm font-medium rounded-sm px-4 cursor-pointer hover:bg-warmer disabled:cursor-not-allowed disabled:bg-gray-200 disabled:border-gray-200"
                @click="handlePlaceSearch"
            >
                <Search
                    class="text-white"
                    size="20"
                />
            </button>
        </form>

        <transition name="max-height-expand">
            <div v-if="searchResults && searchResults.length > 0"
                 class="bg-white border border-warm mt-2 rounded-sm shadow-xs flex flex-col h-40 overflow-scroll"
            >
                <div v-for="searchResult in searchResults" :key="`${searchResult.latitude}-${searchResult.longitude}`"
                     class="cursor-pointer hover:bg-warm hover:text-light px-2 py-2"
                     @click="addStopToTrip(searchResult)"
                >
                    <p v-if="(searchResult.locality || searchResult.name) && searchResult.region && searchResult.country">
                        {{ searchResult.locality ? searchResult.locality : searchResult.name }}, {{ searchResult.region }}, {{ searchResult.country }}
                    </p>
                    <p v-else-if="searchResult.name">
                        {{ searchResult.name }}
                    </p>
                </div>
            </div>
        </transition>

        <div class="w-full border border-1 my-4 rounded-sm text-warm opacity-30"></div>

        <div v-if="localStops && localStops.length > 0 && localStops[0].latitude">
        <VueDraggable @update="updateStopOrder"
                v-model="localStops"
                handle=".handle" ghostClass="ghost"
            >
                <RemoveStopModal
                    :stop="selectedStopToRemove"
                    v-if="isModalVisible && isEditable"
                    @toggle-visibility="handleRemoveStopModalVisibility"
                />
                <div
                    v-for="(stop, index) in localStops" :key="`${stop.latitude}-${stop.longitude}`"
                    class="bg-white border border-warm mt-2 rounded-sm px-4 py-4 shadow-xs flex flex-row gap-2"
                >
                    <div class="flex flex-col items-center gap-4">
                        <Grip
                            :class="isEditable ? 'handle text-dark hover:text-dark/50 cursor-grab' : 'cursor-not-allowed text-gray-200'"
                        />
                        <div class="relative w-7 h-7 bg-dark text-cream font-bold border border-warm rounded-full flex items-center justify-center text-[1rem]">
                            <span class="relative z-20">{{ stop.order_index }}</span>
                        </div>
                        <Trash2
                            :class="isEditable ? 'text-red-500 hover:text-red-600 cursor-pointer' : 'cursor-not-allowed text-gray-200'"
                            @click="handleRemoveStopModalVisibility(stop)"
                        />
                    </div>
                    <div class="w-full">
                        <input
                            :disabled="!isEditable"
                            type="text"
                            :value="stop.label"
                            @change="(e) => updateLabel(e, stop)"
                            :placeholder="t('trip.form.create_trip.destinations.destination_placeholder')"
                            class="bg-white/70 border border-warm p-2 rounded-sm text-dark focus:outline-warm w-full disabled:cursor-not-allowed"
                            name="destination"
                            id="destination"
                        />
                        <div
                            v-if="props.errors && props.errors.trip_destinations && props.errors.trip_destinations.length > 0"
                            v-for="(errors, errorIndex) in props.errors.trip_destinations"
                        >
                            <div
                                v-if="errorIndex === index && errors.label"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ errors.label }}
                            </div>
                        </div>
                        <div v-if="stop.notes && stop.notes.length > 0">
                            <div v-for="note in stop.notes" :key="note.id">
                            <textarea
                                :disabled="!isEditable"
                                rows="3 "
                                v-model="note.content"
                                :placeholder="t('trip.form.create_trip.destinations.note_placeholder')"
                                class="mt-2 w-full border border-warm p-2 rounded-lg text-dark focus:outline-warm resize-none disabled:cursor-not-allowed"
                                name="note" required
                                id="note"
                            >
                            </textarea>
                            </div>
                        </div>
                        <div class="mt-2 flex flex-row justify-between">
                            <div class="flex flex-row gap-2 items-center relative">
                                <CalendarArrowDown
                                    size="18"
                                    :class="isEditable ? 'text-warm cursor-pointer' : 'cursor-not-allowed text-gray-200'"
                                    @click="(e) => {openDatePicker(e, false)}"
                                />
                                <input
                                    :disabled="!isEditable"
                                    type="date"
                                    :value="stop.arrival_date"
                                    @change="(e) => updateDate(e, 'arrival_date', stop)"
                                    class="w-18 md:w-20 text-center text-xs md:text-sm text-warm date-input-custom cursor-pointer focus:outline-warm disabled:cursor-not-allowed"
                                    @click="(e) => {openDatePicker(e, true)}"
                                />
                            </div>
                            <div class="flex flex-row gap-2 items-center">
                                <CalendarArrowUp
                                    size="18"
                                    :class="isEditable ? 'text-warm cursor-pointer' : 'cursor-not-allowed text-gray-200'"
                                    @click="(e) => {openDatePicker(e, false)}"
                                />
                                <input
                                    :disabled="!isEditable"
                                    type="date"
                                    :value="stop.departure_date"
                                    @change="(e) => updateDate(e, 'departure_date', stop)"
                                    class="w-18 md:w-20 text-center text-warm text-xs md:text-sm date-input-custom cursor-pointer focus:outline-warm disabled:cursor-not-allowed"
                                    @click="(e) => {openDatePicker(e, true)}"
                                />
                            </div>
                        </div>
                        <div
                            v-if="props.errors && props.errors.trip_destinations && props.errors.trip_destinations.length > 0"
                            v-for="(errors, errorIndex) in props.errors.trip_destinations"
                        >
                            <div
                                v-if="errorIndex === index && errors.date"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ errors.date }}
                            </div>
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
.ghost {/* DnD background*/
    opacity: 0.5;
    background: #FAEDCD;
}
input[type="search"]::-webkit-search-cancel-button {
    /* Hide default apparence */
    -webkit-appearance: none;
    appearance: none;

    width: 20px;
    height: 20px;

    cursor: pointer;

    /* Replace with SVG and another color */
    background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23D4A574"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>') no-repeat center center;
    background-size: contain;
}
input[type="search"]::-webkit-search-cancel-button:hover {
    background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%238B4513"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>') no-repeat center center;
}
/* Search result animations */
.max-height-expand-enter-active, .max-height-expand-leave-active {
    transition: max-height 0.5s ease-out, opacity 0.3s ease;
}
.max-height-expand-enter-from, .max-height-expand-leave-to {
    max-height: 0;
    opacity: 0;
}
.max-height-expand-enter-to, .max-height-expand-leave-from {
    max-height: 220px; /* Ou une valeur plus grande, par exemple 500px, selon votre contenu max */
    opacity: 1;
}
.date-input-custom::-webkit-calendar-picker-indicator {
    display: none;
    -webkit-appearance: none;
}

.date-input-custom::-webkit-inner-spin-button,
.date-input-custom::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>
