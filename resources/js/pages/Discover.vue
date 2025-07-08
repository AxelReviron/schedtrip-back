<script setup lang="ts">
import {usePage} from "@inertiajs/vue3";
import Navbar from "@/components/Navbar.vue";
import Footer from "@/components/Footer.vue";
import {useI18n} from "vue-i18n";
import {MapPin} from "lucide-vue-next";
import HeroBanner from "@/components/HeroBanner.vue";
import TripCard from "@/components/Trip/Cards/TripCard.vue";
import {storeToRefs} from "pinia";
import {usePublicTripStore} from "@/stores/publicTripStore";
import {onMounted} from "vue";
import axios from "axios";

const page = usePage()
const {t} = useI18n();

const publicTripStore = usePublicTripStore();
const { trips } = storeToRefs(publicTripStore);

onMounted(async () => {
    const response = await axios.get('/api/trips?is_public=1');
    publicTripStore.setTrips(response.data.member);
})
</script>

<template>
    <Navbar/>
    <div class="bg-light flex flex-col items-center justify-center">
        <div class="w-8/12 mx-auto my-8">
            <HeroBanner
                :title="t('discover.title')"
                :subtitle="t('discover.subtitle')"
                :button-text="t('trip.new_trip')"
                :icon="MapPin"
                button-link="/trip/create"
                :is-btn-disabled="false"
            />

            <!--Public Trips-->
            <div class="bg-white border border-gray-200 mt-8 rounded-sm px-4 py-4 shadow-xs">
                <div class="flex flex-row items-center gap-2">
                    <MapPin
                        size="24"
                        class="text-dark"
                    />
                    <div class="flex flex-row gap-2 items-center">
                        <h3 class="text-dark font-medium text-[1.2rem] md:text-[1.4rem]">
                            {{ $t("discover.public_trips.title") }}
                        </h3>
                        <div
                            class="relative inline-flex items-center justify-center w-5 h-5 md:w-6 md:h-6 overflow-hidden bg-warm rounded-full">
                            <span class="font-medium text-white text-sm">
                                {{ trips ? trips.length : '0' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div v-if="trips && trips.length > 0" class="flex flex-row justify-around gap-4 flex-wrap">
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
                        {{ $t("discover.public_trips.no_public_trips") }}
                    </h3>
                    <h4 class="text-lg text-warm">
                        {{ $t("discover.public_trips.no_public_trips_text") }}
                    </h4>
                </div>
            </div>

        </div>
    </div>

    <Footer/>
</template>

<style scoped>

</style>
