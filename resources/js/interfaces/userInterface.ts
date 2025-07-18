import TripInterface from "@/interfaces/tripInterface";

export default interface UserInterface {
    id: string;
    pseudo: string;
    email: string;
    email_verified_at: Date;
    friends: string[];
    usersBlocked: string[];
    incomingFriendsRequestInPending: string[];
    outgoingFriendsRequestInPending: string[];
    trips: TripInterface[]|[];
    created_at: Date;
    updated_at: Date;
}
