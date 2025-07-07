export default {
    trip: {
        title: "Voyages",
        subtitle: "Planifiez et organisez vos voyages.",
        new_trip: "Nouveau Voyage",
        no_trip: "Pas encore de voyage",
        my_trips: "Mes Voyages",
        no_trip_text: "Commencez à planifier votre premier voyage !",
        trip_no_description: "Pas de description",
        stops: "Arrêts",
        participant: "Participant",
        participants: "Participants",
        date: "Date",
        view_btn: "Voir",
        edit_trip: "Editer",
        quit_trip: "Quitter",
        delete_trip: "Supprimer",
        public: "publique",
        private: "privé",
        view_trip: {
            title: "Voir le voyage",
            subtitle: "Consultez les détails, destinations et participants",
            edit: "Éditer"
        },
        edit_trip: {
            title: "Modifier un voyage",
            subtitle: "Modifiez les détails, destinations et participants",
            edit_btn: "Éditer",
            notification: {
                success: "Voyage mis a jour correctement !",
            }
        },
        form: {
            create_trip: {
                title: "Créer un voyage",
                subtitle: "Ajoutez les détails, destinations et participants",
                save: "Sauvegarder",
                create: "Créer",
                cancel: "Annuler",
                interactive_map: {
                    title: "Carte interactive",
                    subtitle: "Cliquez n'importe où pour ajouter une destination",
                },
                trip_details: {
                    title: "Détails du voyage",
                    label: "Label",
                    description: "Description",
                    make_public: "Rendre le voyage publique"
                },
                participants: {
                    title: "Participants",
                    add_friends: "Ajouter des amis",
                    search_placeholder: "Rechercher un ami à partir de son pseudo",
                    no_participant_title: "Vous êtes le seul participant",
                    no_participant_subtitle: "Commencez à ajouter des participants avec la barre de recherche",
                    modal: {
                        title: "Ajouter un participant",
                        subtitle: "Les spectateurs peuvent voir votre voyage, tandis que les éditeurs peuvent le modifier entièrement (mais ils ne peuvent pas le supprimer).",
                        permission_label: "Permission",
                        viewer: "Spectateur",
                        editor: "Éditeur",
                        add: "Ajouter",
                    },
                },
                destinations: {
                    title: "Destinations",
                    add_notes: "Ajouter des notes",
                    destination_name: "Nom de la destination",
                    stop: "Arrêt",
                    stops: "arrêts",
                    no_destinations: "Pas encore de destinations",
                    start_destinations: "Commencez à ajouter des arrêts en cliquant sur la carte ou faites une recherche.",
                    search: "Rechercher",
                    search_placeholder: "Rechercher une destination",
                    destination_placeholder: "Destination",
                    note_placeholder: "Ajouter une note pour cette destination",
                    modal: {
                        title: "Supprimer l'arrêt",
                        subtitle: "Êtes-vous sûr de vouloir supprimer cet arrêt ?",
                        remove: "Supprimer"
                    },
                },
                notification: {
                    success: "Voyage créé avec succès !",
                    error: {
                        form: "Veuillez corriger les erreurs dans le formulaire.",
                        server: "Une erreur inattendue s'est produite. Veuillez réessayer.",
                        search_place_error: "Aucun lieu ne correspond à vos critères de recherche.",
                        reverse_search_error: "Impossible d'obtenir le nom du lieu a partir des coordonnées.",
                        route_error: "Impossible d'obtenir la route a partir des coordonnées",
                        search_friend_error: "Impossible de trouver un ami avec ce pseudo.",
                        no_friends: "Vous n'avez pas d'amis, commencer par en ajouter.",
                        no_trip_label: "Un voyage doit avoir un label",
                        no_stop_label: "A arrêt doit avoir un label",
                        no_stop_dates: "Un arrêt doit avoir des dates d'arrivée et de départ",
                    }
                }
            },
        },
    }
}
