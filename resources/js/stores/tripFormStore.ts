import {defineStore} from "pinia";
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import StopInterface from "@/interfaces/stopInterface";
import TripParticipantsFormInterface from "@/interfaces/tripParticipantsForm";

export const useTripFormStore = defineStore('tripFrom', () => {
    const label: string|null = ref(null);
    const description: string|null = ref(null);
    const distance: number|null = ref(null);
    const duration: number|null = ref(null);
    const is_public: boolean = ref(false);
    const geojson: GeojsonInterface|null = ref(null);
    const participants: TripParticipantsFormInterface[]|[] = ref([]);
    const stops: StopInterface[]|[]= ref([]);

    const isLoading: boolean = ref(false);
    const page = usePage();

    return {
        // States
        label,
        description,
        distance,
        duration,
        is_public,
        geojson,
        participants,
        stops,
    }
})
