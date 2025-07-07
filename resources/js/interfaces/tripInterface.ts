import StopInterface from "@/interfaces/stopInterface";
import ParticipantsInterface from "@/interfaces/participantsInterface";

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
    participantsList: ParticipantsInterface[];
    created_at: Date;
    updated_at: Date;
}
