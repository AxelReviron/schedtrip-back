<script setup lang="ts">
import Navbar from "@/components/Navbar.vue";
import { ArrowUpRight  } from "lucide-vue-next";
import HeroBanner from "@/components/HeroBanner.vue";
import {useI18n} from "vue-i18n";
import {onMounted, ref} from "vue";
import {storeToRefs} from "pinia";
import {useUserStore} from "@/stores/userStore";
import InteractiveMap from "@/components/Trip/TripInteractiveMap.vue";
import Destinations from "@/components/Trip/TripDestinations.vue";
import TripDetails from "@/components/Trip/TripDetails.vue";
import TripParticipants from "@/components/Trip/TripParticipants.vue";
import {useNotification} from "@/composables/useNotification";
import {useTripFormStore} from "@/stores/tripFormStore";
import {usePage} from "@inertiajs/vue3";
import Notification from "@/components/Notification.vue";
import Footer from "@/components/Footer.vue";

const page = usePage();
const {t} = useI18n();

const userStore = useUserStore();
const { user } = storeToRefs(userStore);

const tripFormStore = useTripFormStore();
const { trip } = storeToRefs(tripFormStore);

const errors = ref({
    trip_details: null,
    trip_destinations: []
});
const { notification, showNotification } = useNotification();

function editTrip() {
    //
}

onMounted(async () => {
    await tripFormStore.setTrip(page.props.trip);
})
</script>

<template>
    <Navbar/>
    <div class="bg-light flex flex-col items-center justify-center">
        <div class="w-11/12 md:w-10/12 xl:w-8/12 mx-auto my-8">
            <HeroBanner
                :title="t('trip.view_trip.title')"
                :subtitle="t('trip.view_trip.subtitle')"
                :button-text="t('trip.view_trip.edit')"
                :icon="ArrowUpRight"
                @click="editTrip"
                :is-btn-disabled="true"
            />

            <div class="flex flex-col items-center lg:flex-row justify-center lg:gap-2 w-full">
                <TripDetails :isEditable="false" :errors="errors"/>
                <TripParticipants :isEditable="false"/>
            </div>
            <div class="flex flex-col items-start lg:flex-row justify-center lg:gap-2 w-full">
                <InteractiveMap :isEditable="false"/>
                <Destinations :isEditable="false" :errors="errors"/>
            </div>

        </div>
    </div>
    <Footer />
    <Notification :notification="notification"/>

</template>

<style scoped>

</style>
