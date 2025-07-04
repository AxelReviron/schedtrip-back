// src/composables/useCreateStopForStore.ts
import * as L from "leaflet";

export default function useCreateStopForStore(
    label: string|null,
    latitude: number,
    longitude: number,
    marker: L.Marker|null,
) {
    return {
        label: label,
        description: null,
        trip_id: null,
        duration: null,
        latitude: latitude,
        longitude: longitude,
        arrival_date: null,
        departure_date: null,
        order_index: null,
        notes: [
            {
                user_id: null,
                stop_id: null,
                content: null,
            }
        ],
        markers: [
            marker
        ]
    };
}
