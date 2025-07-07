<script setup lang="ts">
import {Trash2, X} from "lucide-vue-next";
import {useI18n} from "vue-i18n";
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import Notification from "@/components/Notification.vue";
import {storeToRefs} from "pinia";
import {useNotification} from "@/composables/useNotification";
import {useTripFormStore} from "@/stores/tripFormStore";
import StopInterface from "@/interfaces/stopInterface";

const page = usePage()
const {t} = useI18n();

const props = defineProps<{
    stop: StopInterface
}>();

const { stop } = props;
const emit = defineEmits(['toggle-visibility']);

const tripFormStore = useTripFormStore();
const {trip} = storeToRefs(tripFormStore);

const errors = ref({});
const { notification, showNotification } = useNotification();

async function handleRemoveStop() {
    errors.value = {};
    tripFormStore.removeStop(stop);
    tripFormStore.removeGeoJson();
    emit('toggle-visibility');
}
</script>

<template>

    <div class="fixed inset-0 z-999 flex items-center justify-center">
        <div class="z-99 absolute bg-black/20 backdrop-blur-[0.1rem] w-full h-full"></div>

        <div class="z-100 w-100 bg-white border border-gray-200 rounded-sm px-4 py-4 shadow-lg">

            <div class="flex flex-row justify-between mb-2">
                <div
                    class="flex flex-row gap-2 items-center font-medium">
                    <Trash2
                        class="text-dark"
                        size="24"
                    />
                    <h3 class="text-xl text-dark">{{ $t("trip.form.create_trip.destinations.modal.title") }}</h3>
                </div>
                <div class="relative inline-flex items-center justify-center w-8 h-8 overflow-hidden cursor-pointer rounded-lg">
                    <X
                        @click="emit('toggle-visibility')"
                        class="text-dark"
                        size="20"
                    />
                </div>
            </div>

            <div>
                <form class="text-dark flex flex-col items-center" name="login" method="POST" @submit="handleSubmit">
                    <div class="flex flex-col mt-2 w-full text-dark">
                        <h3>
                            {{ $t("trip.form.create_trip.destinations.modal.subtitle") }}
                        </h3>
                    </div>
                    <div class="flex flex-row gap-2 justify-between w-full">
                        <button
                            type="button"
                            @click="handleRemoveStop"
                            class="mt-4 py-2 px-4 w-full border border-red-500 border-2 rounded-lg text-red-500 font-medium cursor-pointer"
                        >
                            {{ $t("trip.form.create_trip.destinations.modal.remove") }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <Notification :notification="notification"/>
</template>

<style scoped>

</style>
