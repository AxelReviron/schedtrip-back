<script setup lang="ts">
import {MapPinned, Search} from "lucide-vue-next";
import {useI18n} from "vue-i18n";
import TitleIcon from "@/components/TitleIcon.vue";
import {useTripFormStore} from "@/stores/tripFormStore";
import {storeToRefs} from "pinia";

const {t} = useI18n();

const tripFormStore = useTripFormStore();
const { stops } = storeToRefs(tripFormStore);

</script>

<template>
    <div class="bg-white border border-gray-200 mt-8 rounded-sm px-4 py-4 shadow-xs w-4/12">
        <div class="flex flex-row justify-between items-center">
            <TitleIcon
                :title="t('trip.form.create_trip.destinations.title')"
                :icon="MapPinned"
            />
            <h4 class="text-dark text-[1rem]">
                {{ stops.length }} {{ $t("trip.form.create_trip.destinations.stops") }}
            </h4>
        </div>

        <div class="mt-4 flex flex-row gap-2">
            <input
                type="search"
                :placeholder="t('trip.form.create_trip.destinations.search_placeholder')"
                class="bg-white/70 border border-warm p-2 rounded-sm text-warm focus:outline-warm w-full"
                name="search" required
                id="search"
            >
            <button class="flex flex-row gap-2 items-center border border-warm py-2 bg-warm font-medium rounded-sm px-4 cursor-pointer hover:bg-warmer">
                <Search
                    class="text-white"
                    size="20"
                />
            </button>
        </div>

        <div v-if="stops.length > 0">


        </div>

        <div v-else class="flex flex-col items-center justify-center mt-8">
            <MapPinned
                size="42"
                class="text-warm"
            />
            <h3 class="text-xl text-warm">
                {{ $t("trip.form.create_trip.destinations.no_destinations") }}
            </h3>
            <h4 class="text-[1rem] text-warm text-center">
                {{ $t("trip.form.create_trip.destinations.start_destinations") }}
            </h4>
        </div>
    </div>
</template>

<style scoped>

</style>
