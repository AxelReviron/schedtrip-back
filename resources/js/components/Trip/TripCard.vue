<script setup lang="ts">
import {MapPinned, UsersRound, CalendarArrowDown, CalendarArrowUp, Eye} from "lucide-vue-next";
import {onMounted, ref} from "vue";
import TripInterface from "@/interfaces/tripInterface";
import {useI18n} from "vue-i18n";
import axios from "axios";
import UserInterface from "@/interfaces/userInterface";
import {useUserStore} from "@/stores/userStore";
import {storeToRefs} from "pinia";

const {t} = useI18n();

const props = defineProps<{
    trip: TripInterface
}>();
const {trip} = props;

const userStore = useUserStore();
const { user } = storeToRefs(userStore);

const tripParticipantsCount = ref(1);
const tripAuthor: UserInterface|null = ref(null);
const tripArrivalDate = ref(null);
const tripDepartureDate = ref(null);

function getTripParticipantsCount() {
    tripParticipantsCount.value = trip.participants && trip.participants.length > 0 ? trip.participants.length : '1';
}

function formatDate(dateStr: string) {
    const date = new Date(dateStr);
    return new Intl.DateTimeFormat('fr-FR').format(date);
}

function getTripDates(): void {
    const stopsCount = trip.stops.length;
    if (trip.stops[0]) {
        tripArrivalDate.value = formatDate(trip.stops[0].arrivalDate);
        tripDepartureDate.value = formatDate(trip.stops[stopsCount - 1].departureDate);
    }
}

async function getTripAuthor() {
    const response = await axios.get(trip.author);
    return response.data;
}

onMounted(async () => {
    getTripParticipantsCount();
    tripAuthor.value = await getTripAuthor();
    getTripDates();
})

// TODO: Bouton pour voir les details du trip
</script>

<template>
    <div class="bg-gray-100/20 border border-gray-200 rounded-sm shadow-xs w-100">

        <div class="relative">
            <img
                src="../../../assets/trip_card_cover.jpg" alt="Trip card cover"
                class="w-full h-50 object-cover"
            />
        </div>

        <div class="flex flex-col items-start max-h-80 px-4 py-4 w-full">
            <h3 class="text-dark text-xl font-medium truncate">
                {{ trip.label }}
            </h3>
            <h4 class="text-sm mt-2 w-full truncate text-dark">
                {{ trip.description ? trip.description : $t("trip.trip_no_description") }}
            </h4>
            <div class="flex flex-col w-full">

                <div class="flex flex-row justify-between gap-4 mt-2 w-full">
                    <div class="flex flex-row items-center gap-2 mt-4 border-1 border-warm bg-white/90 px-2 rounded-sm px-2 shadow-sm">
                        <MapPinned
                            size="16"
                            class="text-warm"
                        />
                        <h4 class="text-[1rem] text-warm">
                            {{ trip.stops ? trip.stops.length : '0' }} {{ $t("trip.stops") }}
                        </h4>
                    </div>
                    <div class="flex flex-row items-center gap-2 mt-4 border-1 border-warm bg-white/90 px-2 rounded-sm px-2 shadow-sm">
                        <UsersRound
                            size="16"
                            class="text-warm"
                        />
                        <h4 class="text-[1rem] text-warm">
                            {{ tripParticipantsCount }}
                            {{ tripParticipantsCount > 1 ? $t("trip.participants") : $t("trip.participants") }}
                        </h4>
                    </div>
                    <div class="flex flex-row items-center gap-2 mt-4 border-1 border-warm bg-white/90 px-2 rounded-sm px-2 shadow-sm">
                        <Eye
                            size="16"
                            class="text-warm"
                        />
                        <h4 class="text-[1rem] text-warm">
                            {{ trip.isPublic ? $t("trip.public") : $t("trip.private") }}
                        </h4>
                    </div>
                </div>

                <div class="flex flex-row justify-start gap-4 w-full">
                    <div v-if="tripArrivalDate" class="flex flex-row items-center gap-2 mt-4 border-1 border-warm bg-white/90 px-2 rounded-sm px-2 shadow-sm">
                        <CalendarArrowDown
                            size="14"
                            class="text-warm"
                        />
                        <h4 class="text-[1rem] text-warm">
                            {{ tripArrivalDate }}
                        </h4>
                    </div>
                    <div v-if="tripDepartureDate" class="flex flex-row items-center gap-2 mt-4 border-1 border-warm bg-white/90 px-2 rounded-sm px-2 shadow-sm">
                        <CalendarArrowUp
                            size="14"
                            class="text-warm"
                        />
                        <h4 class="text-[1rem] text-warm">
                            {{ tripDepartureDate }}
                        </h4>
                    </div>
                </div>

                <div class="flex flex-row items-center gap-2 mt-6" v-if="tripAuthor">
                    <div class="relative inline-flex items-center justify-center w-8 h-8 overflow-hidden bg-cream rounded-full">
                        <span class="font-medium text-dark text-lg">{{ tripAuthor.pseudo.charAt(0).toUpperCase() }}</span>
                    </div>
                    <h4 class="text-[1.2rem] font-medium text-dark">
                        {{ tripAuthor.pseudo }}
                    </h4>
                </div>


                <div class="flex flex-row justify-center items-center gap-2 mt-6 w-full" v-if="tripAuthor">
                    <a :href="`/trip/view/${trip.id}`" class="w-full">
                        <button
                            class="w-full flex flex-row gap-2 justify-center items-center border py-2 bg-warm text-light font-medium rounded-sm px-4 cursor-pointer hover:bg-warmer"
                        >
                            {{ $t("trip.view_trip") }}
                        </button>
                    </a>
                    <!--TODO: Check permission, if user can't edit trip, disable btn-->
                    <a :href="`/trip/edit/${trip.id}`" class="w-full">
                        <button
                            class="w-full flex flex-row gap-2 justify-center items-center border py-2 bg-warm text-light font-medium rounded-sm px-4 cursor-pointer hover:bg-warmer"
                        >
                            {{ $t("trip.edit_trip.edit_btn") }}
                        </button>
                    </a>
                </div>

            </div>

        </div>
    </div>
</template>

<style scoped>

</style>
