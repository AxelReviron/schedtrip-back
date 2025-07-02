<script setup lang="ts">
import { NotepadText } from "lucide-vue-next";
import {useI18n} from "vue-i18n";
import {storeToRefs} from "pinia";
import {useTripFormStore} from "@/stores/tripFormStore";
import TitleIcon from "@/components/TitleIcon.vue";

const {t} = useI18n();

const tripFormStore = useTripFormStore();
const { trip } = storeToRefs(tripFormStore);

// TODO: Recevoir les erreurs en props
</script>

<template>
    <div class="bg-white border border-gray-200 mt-8 rounded-sm px-4 py-4 shadow-xs w-8/12">
        <TitleIcon
            :title="t('trip.form.create_trip.trip_details.title')"
            :icon="NotepadText"
        />

        <form class="text-dark flex flex-col items-center" name="login">
            <div class="flex flex-col mt-2 w-full">
                <label for="label" class="p-1 font-medium text-lg">{{ $t("trip.form.create_trip.trip_details.label") }}</label>
                <input
                    type="text"
                    v-model="trip.label"
                    :placeholder="t('trip.form.create_trip.trip_details.label')"
                    class="bg-white/70 border border-warm p-2 rounded-lg text-warm focus:outline-warm"
                    name="label" required
                    id="label"
                >
<!--                <div v-if="errors.label" class="text-red-500 text-sm mt-1">{{ errors.label[0] }}</div>-->
            </div>
            <div class="flex flex-col mt-2 w-full">
                <label for="description" class="p-1 font-medium text-lg">{{ $t("trip.form.create_trip.trip_details.description") }}</label>
                <textarea
                    rows="4"
                    v-model="trip.description"
                    :placeholder="t('trip.form.create_trip.trip_details.description')"
                    class="bg-white/70 border border-warm p-2 rounded-lg text-warm focus:outline-warm resize-none"
                    name="description" required
                    id="description"
                >
                </textarea>
                <!--                <div v-if="errors.label" class="text-red-500 text-sm mt-1">{{ errors.label[0] }}</div>-->
            </div>
            <div class="flex flex-row gap-2 mt-2 w-full">
                <input
                    type="checkbox"
                    v-model="trip.is_public"
                    class="bg-white/70 border border-warm p-2 rounded-lg text-warm focus:outline-warm resize-none"
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
