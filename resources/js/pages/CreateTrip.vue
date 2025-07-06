<script setup lang="ts">
import Navbar from "@/components/Navbar.vue";
import { Save } from "lucide-vue-next";
import HeroBanner from "@/components/HeroBanner.vue";
import {useI18n} from "vue-i18n";
import {ref, watch} from "vue";
import {storeToRefs} from "pinia";
import {useUserStore} from "@/stores/userStore";
import InteractiveMap from "@/components/Trip/InteractiveMap.vue";
import Destinations from "@/components/Trip/Destinations.vue";
import TripDetails from "@/components/Trip/TripDetails.vue";
import TripParticipants from "@/components/Trip/TripParticipants.vue";
import {useNotification} from "@/composables/useNotification";
import {useTripFormStore} from "@/stores/tripFormStore";
import {usePage} from "@inertiajs/vue3";
import Notification from "@/components/Notification.vue";
import axios from "axios";

const page = usePage();
const {t} = useI18n();

const userStore = useUserStore();
const { user } = storeToRefs(userStore);

const tripFormStore = useTripFormStore();
const { trip } = storeToRefs(tripFormStore);

const errors = ref({});
const { notification, showNotification } = useNotification();

async function createTrip(e: Event) {
    e.preventDefault();
    errors.value = {};

    try {
        const tripResponse = await axios.post('/api/trips', {//TODO
            _token: page.props.csrf_token,
            label: trip.value.label,
            description: trip.value.description,
            is_public: trip.value.isPublic,
            distance: trip.value.distance,
            duration: trip.value.duration,
            geojson: JSON.stringify(trip.value.geojson),
            author: trip.value.author,
        }, {
            headers: {
                'Content-Type': 'application/ld+json'
            }
        });

        const tripId = tripResponse.data.id;

        for (const stop of trip.value.stops) {
            await axios.post('/api/stops', {
                _token: page.props.csrf_token,
                label: stop.label,
                description: stop.description,
                latitude: stop.latitude,
                longitude: stop.longitude,
                arrivalDate: stop.arrival_date,
                departureDate: stop.departure_date,
                orderIndex: stop.order_index,
                trip: `/api/trips/${tripId}`
            }, {
                headers: {
                    'Content-Type': 'application/ld+json'
                }
            });
        }

        if (trip.value.participants && trip.value.participants.length > 0) {
            await axios.post(`/api/trips/${tripId}/participants`, {
                _token: page.props.csrf_token,
                participants: trip.value.participants
            });
        }


        showNotification(t("trip.form.create_trip.notification.success"), 'success');
        //TODO: Redirect to edit page
    } catch (error: any) {
        console.log(error);
        //TODO: Manage errors
        //if (error.response && error.response.status === 422) {// Validation errors
        //    errors.value = error.response.data.errors;
        //    showNotification(t("friend.form.notification.error.form"), 'error');
        //} else if (error.response && error.response.status === 401) {// TODO: error user not found
        //    showNotification(t("form.auth.notification.error.credentials"), 'error');
        //} else {// Other errors
        //    showNotification(t("friend.form.add_friend.notification.error.server"), 'error');
        //}
    }
}

/**
 * Trigger when user id is defined
 */
watch(
    () => user.value,
    () => {
        if (user.value && user.value.id && !trip.value.author) {
            tripFormStore.setAuthor(user.value.id);
        }
    },
    { deep: true, immediate: true }
);
</script>

<template>
    <Navbar/>
    <div class="bg-light flex flex-col items-center justify-center">
        <div class="w-10/12 xl:w-8/12 mx-auto my-8">
            <HeroBanner
                :title="t('trip.form.create_trip.title')"
                :subtitle="t('trip.form.create_trip.subtitle')"
                :button-text="t('trip.form.create_trip.save')"
                :icon="Save"
                @click="createTrip"
            />

            <div class="flex flex-col items-center lg:flex-row justify-center lg:gap-2 w-full">
                <TripDetails />
                <TripParticipants />
            </div>
            <div class="flex flex-col items-start lg:flex-row justify-center lg:gap-2 w-full">
                <InteractiveMap />
                <Destinations />
            </div>

        </div>
    </div>
    <Notification :notification="notification"/>

</template>

<style scoped>

</style>
