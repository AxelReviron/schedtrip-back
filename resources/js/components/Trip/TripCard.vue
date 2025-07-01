<script setup lang="ts">
import {MapPinned, UsersRound, CalendarDays} from "lucide-vue-next";
import {onMounted, ref} from "vue";
import TripInterface from "@/interfaces/tripInterface";
import {useI18n} from "vue-i18n";
import axios from "axios";
import UserInterface from "@/interfaces/userInterface";
import {useUserStore} from "@/stores/userStore";
import {storeToRefs} from "pinia";

const isModalVisible = ref(false);
const {t} = useI18n();

const props = defineProps<{
    trip: TripInterface
}>();

const userStore = useUserStore();
const { user } = storeToRefs(userStore);

const {trip} = props;

function handleRemoveModalVisibility() {
    isModalVisible.value = !isModalVisible.value;
}

const tripParticipantsCount = ref(1);
const tripAuthor: UserInterface|null = ref(null);

function getTripParticipantsCount() {
    tripParticipantsCount.value = trip.participants && trip.participants.length > 0 ? trip.participants.length : '1';
}

function formatDate(dateStr: string) {
    const date = new Date(dateStr);
    return new Intl.DateTimeFormat('fr-FR').format(date);
}

function getTripDates() {
    const stopsCount = trip.stops.length;
    const firstDate = formatDate(trip.stops[0].arrivalDate);
    const lastDate = formatDate(trip.stops[stopsCount - 1].departureDate);

    return `${firstDate} → ${lastDate}`;
}

async function getTripAuthor() {
    const response = await axios.get(trip.author);
    return response.data;
}

onMounted(async () => {
    getTripParticipantsCount();
    tripAuthor.value = await getTripAuthor();
})

// TODO: petit badge public private en haut a droite de l'image
// TODO: Bouton pour voir les details du trip
</script>

<template>
    <!--    <RemoveTripModal-->
    <!--        :friend-id="friend.id"-->
    <!--        v-if="isModalVisible"-->
    <!--        @toggle-visibility="handleRemoveOrBlockModalVisibility"-->
    <!--    />-->
    <div class="bg-gray-100/20 border border-gray-200 rounded-sm shadow-xs w-100">

        <img
            src="../../../assets/trip_card_cover.jpg" alt="Trip card cover"
            class="w-full h-50 object-cover"
        />

        <div class="flex flex-col items-start max-h-80 px-4 py-4 w-full">
            <h3 class="text-dark text-xl font-medium truncate">
                {{ trip.label }}
            </h3>
            <h4 class="text-sm mt-2 w-full truncate">
                {{ trip.description ? trip.description : $t("trip.trip_no_description") }}
            </h4>
            <div class="flex flex-col w-full">

                <div class="flex flex-row justify-start gap-4 mt-2 w-full">
                    <div class="flex flex-row items-center gap-2 mt-4">
                        <MapPinned
                            size="14"
                            class="text-warmer"
                        />
                        <h4 class="text-[1rem] text-warmer">
                            {{ trip.stops ? trip.stops.length : '0' }} {{ $t("trip.stops") }}
                        </h4>
                    </div>
                    <div class="flex flex-row items-center gap-2 mt-4">
                        <UsersRound
                            size="14"
                            class="text-warmer"
                        />
                        <h4 class="text-[1rem] text-warmer">
                            {{ tripParticipantsCount }}
                            {{ tripParticipantsCount > 1 ? $t("trip.participants") : $t("trip.participants") }}
                        </h4>
                    </div>
                </div>
                <div class="flex flex-row items-center gap-2 mt-4">
                    <CalendarDays
                        size="14"
                        class="text-warmer"
                    />
                    <h4 class="text-[1rem] text-warmer">
                        {{ getTripDates() }}
                    </h4>
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
                    <a href="#" class="w-full">
                        <button
                            class="w-full flex flex-row gap-2 justify-center items-center border py-2 bg-warm text-light font-medium rounded-sm px-4 cursor-pointer hover:bg-warmer"

                        >
                                {{ $t("trip.view_trip") }}
                        </button>
                    </a>

                    <a href="#" class="w-full">
                        <button
                            v-if="tripAuthor && tripAuthor.id === user.id"
                            class="w-full flex flex-row gap-2 justify-center items-center border py-2 bg-red-500 text-light font-medium rounded-sm px-4 cursor-pointer hover:bg-red-600"
                        >
                            {{ tripAuthor.id === user.id ? $t("trip.delete_trip") : $t("trip.quit_trip")}}
                        </button>
                    </a>
                </div>

            </div>

        </div>
    </div>
</template>

<style scoped>

</style>
