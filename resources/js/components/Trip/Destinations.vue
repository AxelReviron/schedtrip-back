<script setup lang="ts">
import {MapPinned, Search, Globe, Grip} from "lucide-vue-next";
import {useI18n} from "vue-i18n";
import TitleIcon from "@/components/TitleIcon.vue";
import {useTripFormStore} from "@/stores/tripFormStore";
import {storeToRefs} from "pinia";
import {VueDraggable} from "vue-draggable-plus";
import {ref} from "vue";

const {t} = useI18n();

const tripFormStore = useTripFormStore();
const {trip} = storeToRefs(tripFormStore);
const localStops = ref([...trip.value.stops]);

function updateStopOrder() {
    localStops.value.forEach((stop, index) => {
        stop.order_index = index;
    });

    trip.value.stops = [...localStops.value];// TODO: fonction store
    // TODO: Mettre a jour les markers et itineraire
}
// TODO: Recherche et gérer affichage
</script>

<template>
    <div class="bg-white border border-gray-200 mt-8 rounded-sm px-4 py-4 shadow-xs w-4/12">
        <div class="flex flex-row justify-between items-center">
            <TitleIcon
                :title="t('trip.form.create_trip.destinations.title')"
                :icon="MapPinned"
            />
            <h4 class="text-dark text-[1rem]">
                {{ trip.stops.length }} {{ $t("trip.form.create_trip.destinations.stops") }}
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
            <button
                class="flex flex-row gap-2 items-center border border-warm py-2 bg-warm font-medium rounded-sm px-4 cursor-pointer hover:bg-warmer">
                <Search
                    class="text-white"
                    size="20"
                />
            </button>
        </div>

        <div class="w-full border border-1 my-4 rounded-sm text-warm opacity-30"></div>

        <div v-if="trip.stops && trip.stops.length > 0">
            <VueDraggable
                v-model="localStops" @end="updateStopOrder"
                handle=".handle" ghostClass="ghost"
            >
                <div
                    v-for="stop in trip.stops" :key="stop.id"
                    class="bg-white border border-warm mt-2 rounded-sm px-4 py-4 shadow-xs flex flex-row gap-2"
                >
                    <Grip
                        class="text-warm hover:text-warmer hover:cursor-grab handle"
                    />
                    <div class="w-full">
                        <input
                            type="text"
                            v-model="stop.label"
                            :placeholder="t('trip.form.create_trip.destinations.destination_placeholder')"
                            class="bg-white/70 border border-warm p-2 rounded-sm text-dark focus:outline-warm w-full"
                            name="destination"
                            id="destination"
                        />
                        <div v-if="stop.notes && stop.notes.length > 0">
                            <div v-for="(note, index) in stop.notes" :key="note.id">
                            <textarea
                                rows="2"
                                v-model="note.content"
                                :placeholder="t('trip.form.create_trip.destinations.note_placeholder')"
                                class="mt-2 w-full border border-warm p-2 rounded-lg text-dark focus:outline-warm resize-none"
                                name="note" required
                                id="note"
                            >
                            </textarea>
                            </div>
                        </div>
                        <div class="text-warm text-sm flex flex-row items-center gap-2">
                            <Globe
                                size="18"
                            />
                            <p>{{ stop.latitude }}, {{ stop.longitude }}</p>
                        </div>
                    </div>
                </div>
            </VueDraggable>
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
.ghost {
    opacity: 0.5;
    background: #FAEDCD;
}
</style>
