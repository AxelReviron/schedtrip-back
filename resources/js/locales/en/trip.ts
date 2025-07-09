export default {
    trip: {
        title: "Trips",
        subtitle: "Plan and organize your trips",
        new_trip: "New Trip",
        no_trip: "No trips yet",
        my_trips: "My Trips",
        no_trip_text: "Start planning your first trip !",
        trip_no_description: "No description",
        stops: "Stops",
        participant: "Participant",
        participants: "Participants",
        date: "Date",
        view_btn: "View",
        quit_trip: "Quit",
        delete_trip: "Delete",
        public: "public",
        private: "private",
        view_trip: {
            title: "View trip",
            subtitle: "See trip details, destinations and participants",
            edit: "Edit"
        },
        edit_trip: {
            title: "Edit trip",
            subtitle: "Update trip details, destinations and participants",
            edit_btn: "Edit",
            notification: {
                success: "Trip successfully updated !",
            }
        },
        form: {
            create_trip: {
                title: "Create new trip",
                subtitle: "Add trip details, destinations and participants",
                save: "Save",
                create: "Create",
                cancel: "Cancel",
                interactive_map: {
                    title: "Interactive map",
                    subtitle: "Click anywhere to add a destination",
                },
                trip_details: {
                    title: "Trip details",
                    label: "Label",
                    description: "Description",
                    make_public: "Make trip public"
                },
                participants: {
                    title: "Participants",
                    add_friends: "Add friends",
                    search_placeholder: "Search a friend by it's pseudo",
                    no_participant_title: "You are the only participant",
                    no_participant_subtitle: "Start adding participants with the search bar.",
                    modal: {
                        title: "Add Participant",
                        subtitle: "Viewers can see your trip, while editors can fully modify it (but they can't delete it).",
                        permission_label: "Permission",
                        viewer: "Viewer",
                        editor: "Editor",
                        add: "Add"
                    },
                },
                destinations: {
                    title: "Destinations",
                    add_notes: "Add notes",
                    destination_name: "Destination name",
                    stop: "Stop",
                    stops: "stops",
                    no_destinations: "No destinations yet.",
                    search: "Search",
                    start_destinations: "Start adding destinations clicking on the map or search it.",
                    search_placeholder: "Search for a destination",
                    destination_placeholder: "Destination",
                    note_placeholder: "Add a note for this destination",
                    modal: {
                        title: "Remove Stop",
                        subtitle: "Are you sure you want to remove this stop ?",
                        remove: "Remove"
                    },
                },
                notification: {
                    success: "Trip successfully created !",
                    error: {
                        form: "Please correct the errors in the form.",
                        server: "An unexpected error occurred. Please try again.",
                        search_place_error: "Can't find any places that match your search criteria.",
                        reverse_search_error: "Can't get the name from coordinates",
                        route_error: "Can't get route from coordinates",
                        search_friend_error: "Can't find any friend with this pseudo",
                        no_friends: "You don't have any friends, add one first.",
                        no_trip_label: "A trip must have a label",
                        no_stop_label: "A stop must have a label",
                        no_stop_dates: "A stop must have arrival and departure dates",
                        rate_limit: "You have exceeded the requests limit, you can increase it (for free) in your account settings."
                    }
                }
            },
        },
    }
}
