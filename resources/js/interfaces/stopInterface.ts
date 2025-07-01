export default interface StopInterface {
    id: string;
    label: string;
    description: string|null;
    trip_id: string;
    duration: number;
    latitude: string;
    longitude: string;
    arrival_date: Date;
    departure_date: Date;
    order_index: number;
    created_at: Date;
    updated_at: Date;
}
