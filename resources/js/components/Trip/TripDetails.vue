<script setup lang="ts">
import { NotepadText } from "lucide-vue-next";
import {useI18n} from "vue-i18n";
import {storeToRefs} from "pinia";
import {useTripFormStore} from "@/stores/tripFormStore";
import TitleIcon from "@/components/TitleIcon.vue";

const props = defineProps<{
    errors: object,
    isEditable: boolean
}>();

const { errors, isEditable } = props;

const {t} = useI18n();

const tripFormStore = useTripFormStore();
const { trip } = storeToRefs(tripFormStore);

</script>

<template>
    <div class="bg-white border border-gray-200 mt-8 rounded-sm px-4 py-4 shadow-xs w-full">
        <TitleIcon
            :title="t('trip.form.create_trip.trip_details.title')"
            :icon="NotepadText"
        />

        <form class="text-dark flex flex-col items-center" name="login">
            <div class="flex flex-col mt-2 w-full">
                <label for="label" class="p-1 font-medium text-lg">{{ $t("trip.form.create_trip.trip_details.label") }}</label>
                <input
                    :disabled="!isEditable"
                    type="text"
                    v-model="trip.label"
                    :placeholder="t('trip.form.create_trip.trip_details.label')"
                    class="bg-white/70 border border-warm p-2 rounded-lg text-dark focus:outline-warm disabled:cursor-not-allowed"
                    name="label" required
                    id="label"
                >
                <div v-if="props.errors && props.errors.trip_details" class="text-red-500 text-sm mt-1">
                    {{ props.errors.trip_details }}
                </div>
            </div>
            <div class="flex flex-col mt-2 w-full">
                <label for="description" class="p-1 font-medium text-lg">{{ $t("trip.form.create_trip.trip_details.description") }}</label>
                <textarea
                    :disabled="!isEditable"
                    rows="4"
                    v-model="trip.description"
                    :placeholder="t('trip.form.create_trip.trip_details.description')"
                    class="bg-white/70 border border-warm p-2 rounded-lg text-dark focus:outline-warm resize-none disabled:cursor-not-allowed"
                    name="description" required
                    id="description"
                >
                </textarea>
            </div>
            <div class="flex flex-row gap-2 mt-2 w-full">
                <input
                    :disabled="!isEditable"
                    type="checkbox"
                    v-model="trip.isPublic"
                    class="bg-white/70 border border-warm p-2 rounded-lg text-dark focus:outline-warm disabled:cursor-not-allowed"
                    name="is_public" required
                    id="is_public"
                >
                <label for="is_public" class="p-1 font-medium text-lg">{{ $t("trip.form.create_trip.trip_details.make_public") }}</label>
             </div>
        </form>

    </div>
</template>

<style scoped>

</style>
