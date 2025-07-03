<script setup lang="ts">
import Navbar from "@/components/Navbar.vue";
import { Save } from "lucide-vue-next";
import HeroBanner from "@/components/HeroBanner.vue";
import {useI18n} from "vue-i18n";
import {ref} from "vue";
import {storeToRefs} from "pinia";
import {useUserStore} from "@/stores/userStore";
import InteractiveMap from "@/components/Trip/InteractiveMap.vue";
import Destinations from "@/components/Trip/Destinations.vue";
import TripDetails from "@/components/Trip/TripDetails.vue";
import TripParticipants from "@/components/Trip/TripParticipants.vue";
import {useNotification} from "@/composables/useNotification";
import axios from "axios";
import {useTripFormStore} from "@/stores/tripFormStore";
import {usePage} from "@inertiajs/vue3";
import Notification from "@/components/Notification.vue";

const page = usePage();
const {t} = useI18n();

const userStore = useUserStore();
const { user } = storeToRefs(userStore);

const isModalVisible = ref(false);

const tripFormStore = useTripFormStore();
const {
    label,
    description,
    is_public,
    distance,
    duration,
    geojson,
    participants,
    stops
} = storeToRefs(tripFormStore);

function handleRemoveTripModalVisibility() {
    isModalVisible.value = !isModalVisible.value;
}

const errors = ref({});
const { notification, showNotification } = useNotification();

async function createTrip(e: Event) {
    e.preventDefault();
    errors.value = {};

    try {
        const response = await axios.post('/api/trips', {
            _token: page.props.csrf_token,
            label: label.value,
            description: description.value,
            is_public: is_public.value,
            distance: distance.value,
            duration: duration.value,
            geojson: geojson.value,
            author: '/api/users/' + user.value.id,
        }, {
            headers: {
                'Content-Type': 'application/ld+json'
            }
        });

        const tripId = response.data.id;

        /*await axios.post(`api/trips/${tripId}/participants`, {
            _token: page.props.csrf_token,
            participants: [
                {
                    user_id: '',
                    user_id: 'view'
                }
            ]
        });*/

        showNotification(t("trip.form.create_trip.notification.success"), 'success');
    } catch (error: any) {
        console.log(error);
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
</script>

<template>
    <!--    <RemoveTripModal-->
    <!--        :friend-id="friend.id"-->
    <!--        v-if="isModalVisible"-->
    <!--        @toggle-visibility="handleRemoveTripModalVisibility"-->
    <!--    />-->

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
            <div class="flex flex-col items-center lg:flex-row justify-center lg:gap-2 w-full">
                <InteractiveMap />
                <Destinations />
            </div>

        </div>
    </div>
    <Notification :notification="notification"/>

</template>

<style scoped>

</style>
