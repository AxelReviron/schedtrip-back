<script setup lang="ts">

import {usePage} from "@inertiajs/vue3";
import Navbar from "@/components/Navbar.vue";
import { MapPin } from "lucide-vue-next";
import {useUserStore} from "@/stores/userStore";
import {storeToRefs} from "pinia";
import {watch, onMounted} from "vue";
import TripCard from "@/components/Trip/TripCard.vue";
import {useTripStore} from "@/stores/tripStore";
import axios from "axios";
import HeroBanner from "@/components/HeroBanner.vue";
import {useI18n} from "vue-i18n";

const page = usePage()
const {t} = useI18n();

const userStore = useUserStore();
const tripStore = useTripStore();

const { user } = storeToRefs(userStore);
const { trips } = storeToRefs(tripStore);

onMounted(async () => {
    const stopWatcher = watch(user, async (newUser) => {
        if (newUser && newUser.trips) {
            for (const trip of newUser.trips) {
                const response = await axios.get(trip);
                await tripStore.setTrips(response.data);
            }
            stopWatcher();
        }
    });
})

</script>

<template>
    <Navbar/>
    <div class="bg-light flex flex-col items-center justify-center">
        <div class="w-8/12 mx-auto my-8">
            <HeroBanner
                :title="t('trip.title')"
                :subtitle="t('trip.subtitle')"
                :button-text="t('trip.new_trip')"
                :icon="MapPin"
                button-link="/trip/create"
            />

            <!--Trip-->
            <div class="bg-white border border-gray-200 mt-8 rounded-sm px-4 py-4 shadow-xs">
                <div class="flex flex-row items-center gap-2">
                    <MapPin
                        size="24"
                        class="text-dark"
                    />
                    <div class="flex flex-row gap-2 items-center">
                        <h3 class="text-dark font-medium text-[1.4rem]">
                            {{ $t("trip.my_trips") }}
                        </h3>
                        <div
                            class="relative inline-flex items-center justify-center w-6 h-6 overflow-hidden bg-warm rounded-full">
                            <span class="font-medium text-white text-sm">
                                {{ trips ? trips.length : '0' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div v-if="trips && trips.length > 0" class="flex flex-row justify-between flex-wrap">
                    <div v-for="trip in trips" :key="trip.id" class="mt-4">
                        <TripCard :trip="trip"/>
                    </div>
                </div>
                <div v-else class="flex flex-col items-center justify-center mt-4">
                    <MapPin
                        size="42"
                        class="text-warm"
                    />
                    <h3 class="text-xl text-warm">
                        {{ $t("trip.no_trip") }}
                    </h3>
                    <h4 class="text-lg text-warm">
                        {{ $t("trip.no_trip_text") }}
                    </h4>
                </div>
            </div>

        </div>
    </div>
</template>

<style scoped>

</style>
