<script setup lang="ts">
import Navbar from "@/components/Navbar.vue";
import { Save } from "lucide-vue-next";
import HeroBannerButton from "@/components/HeroBannerButton.vue";
import {useI18n} from "vue-i18n";
import {onMounted, ref, watch} from "vue";
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
import axios from "axios";
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

onMounted(async () => {
    await tripFormStore.setTrip(page.props.trip);
})

function isTripDetailsValid(): void {
    if (!trip.value.label) {
        showNotification(t("trip.form.create_trip.notification.error.form"), 'error');
        errors.value.trip_details = t("trip.form.create_trip.notification.error.no_trip_label");
    }
}// TODO: Composable

function isTripDestinationsValid(): void {
    trip.value.stops.forEach((stop, index) => {
        const destinationErrors: Record<string, string> = {};

        if (!stop.label || stop.label === '') {
            showNotification(t("trip.form.create_trip.notification.error.form"), 'error');
            destinationErrors.label = t("trip.form.create_trip.notification.error.no_stop_label");
        }

        if (!stop.arrival_date || !stop.departure_date) {
            showNotification(t("trip.form.create_trip.notification.error.form"), 'error');
            destinationErrors.date = t("trip.form.create_trip.notification.error.no_stop_dates");
        }

        if (Object.keys(destinationErrors).length > 0) {
            errors.value.trip_destinations.push({
                index: index,
                ...destinationErrors
            });
        }
    })
}// TODO: Composable

async function updateTrip(e: Event) {
    e.preventDefault();
    errors.value = {
        trip_details: null,
        trip_destinations: []
    };

    isTripDetailsValid()
    isTripDestinationsValid()

    if (errors.value.trip_details !== null || errors.value.trip_destinations.length > 0) return

    try {
        const tripResponse = await axios.patch(`/api/trips/${trip.value.id}`, {
            _token: page.props.csrf_token,
            label: trip.value.label,
            description: trip.value.description,
            is_public: trip.value.isPublic,
            distance: null,/*trip.value.distance TODO: Bug here*/
            duration: null,/*trip.value.duration TODO: Bug here*/
            geojson: JSON.stringify(trip.value.geojson),
        }, {
            headers: {
                'Content-Type': 'application/merge-patch+json'
            }
        });

        const tripId = tripResponse.data.id;

        for (const stop of trip.value.stops) {
            await axios.patch(`/api/stops/${stop.id}`, {
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
                    'Content-Type': 'application/merge-patch+json'
                }
            });
        }

        if (trip.value.participants && trip.value.participants.length > 0) {
            await axios.patch(`/api/trips/${tripId}/participants`, {
                _token: page.props.csrf_token,
                participants: trip.value.participants
            });
        }


        showNotification(t("trip.edit_trip.notification.success"), 'success');
    } catch (error: any) {
        if (error.response && error.response.status === 422) {// Validation errors
            errors.value = error.response.data.errors;
            showNotification(t("trip.form.create_trip.notification.error.form"), 'error');
        } else {// Other errors
            showNotification(t("trip.form.create_trip.notification.error.server"), 'error');
        }
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
        <div class="w-11/12 md:w-10/12 xl:w-8/12 mx-auto my-8">
            <HeroBannerButton
                :title="t('trip.edit_trip.title')"
                :subtitle="t('trip.edit_trip.subtitle')"
                :button-text="t('trip.form.create_trip.save')"
                :icon="Save"
                @click="updateTrip"
                :is-btn-disabled="false"
            />

            <div class="flex flex-col items-center lg:flex-row justify-center lg:gap-2 w-full">
                <TripDetails :isEditable="true" :errors="errors"/>
                <TripParticipants :isEditable="true"/>
            </div>
            <div class="flex flex-col items-start lg:flex-row justify-center lg:gap-2 w-full">
                <InteractiveMap :isEditable="true"/>
                <Destinations :isEditable="true" :errors="errors"/>
            </div>

        </div>
    </div>
    <Footer />
    <Notification :notification="notification"/>

</template>

<style scoped>

</style>
