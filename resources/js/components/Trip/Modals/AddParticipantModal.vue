<script setup lang="ts">
import {UserRoundPlus, X} from "lucide-vue-next";
import {useI18n} from "vue-i18n";
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import Notification from "@/components/Notification.vue";
import {storeToRefs} from "pinia";
import {useNotification} from "@/composables/useNotification";
import {useTripFormStore} from "@/stores/tripFormStore";
import UserInterface from "@/interfaces/userInterface";

const page = usePage()
const {t} = useI18n();

const props = defineProps<{
    participant: UserInterface
}>();

const { participant } = props;
const emit = defineEmits(['toggle-visibility']);

const tripFormStore = useTripFormStore();
const {trip} = storeToRefs(tripFormStore);

const errors = ref({});
const { notification, showNotification } = useNotification();

const selectedPermission = ref('view');

async function handleAddParticipant() {
    errors.value = {};
    tripFormStore.addParticipant(participant, selectedPermission.value);
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
                    <UserRoundPlus
                        class="text-dark"
                        size="24"
                    />
                    <h3 class="text-xl text-dark">{{ $t("trip.form.create_trip.participants.modal.title") }}</h3>
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
                <form class="text-dark flex flex-col items-center" name="login" method="POST" @submit="handleAddParticipant">
                    <div class="flex flex-col mt-2 w-full text-dark mb-4">
                        <h3>
                            {{ $t("trip.form.create_trip.participants.modal.subtitle") }}
                        </h3>
                    </div>

                    <div class="w-full">
                        <label for="permission" class="block text-sm font-medium text-dark">
                            {{ $t("trip.form.create_trip.participants.modal.permission_label") }}
                        </label>
                        <select
                            id="permission"
                            v-model="selectedPermission"
                            class="bg-white/70 border border-warm p-2 rounded-sm text-dark focus:outline-warm w-full"
                        >
                            <option value="view">{{ $t("trip.form.create_trip.participants.modal.viewer") }}</option>
                            <option value="update">{{ $t("trip.form.create_trip.participants.modal.editor") }}</option>
                        </select>
                    </div>

                    <div class="flex flex-row gap-2 justify-between w-full">
                        <button
                            type="button"
                            @click="handleAddParticipant"
                            class="mt-4 py-2 px-4 w-full border border-warm bg-warm border-2 rounded-lg text-light font-medium cursor-pointer"
                        >
                            {{ $t("trip.form.create_trip.participants.modal.add") }}
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
