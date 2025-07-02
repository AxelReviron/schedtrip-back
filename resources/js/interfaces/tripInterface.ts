import StopInterface from "@/interfaces/stopInterface";

export default interface TripInterface {
    id: string;
    label: string;
    description: string|null;
    distance: number;
    duration: number;
    isPublic: boolean;
    geojson: [];// TODO: typer ça
    stops: StopInterface[];
    author_id: string;
    participants: [];// TODO: typer ça
    created_at: Date;
    updated_at: Date;
}
