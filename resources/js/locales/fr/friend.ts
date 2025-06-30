export default {
    friend: {
        title: "Amis",
        subtitle: "Connectez-vous avec vos amis et découvrez leurs aventures.",
        add_friend: "Ajouter un ami",
        delete_friend_request: "Supprimer la demande d\'ami",
        friend_requests: 'Demandes d\'amis',
        my_friends: 'Mes amis',
        no_friend: 'Pas encore d\'amis.',
        travel_net: 'Commencez a construire votre réseaux de voyageurs en ajoutant des amis.',
        form: {
            add_friend: {
                pseudo: "Pseudo",
                send: "Envoyer",
                cancel: "Annuler",
                email: "Email",
                notification: {
                    success: "Demande d\'ami envoyé !",
                    error: {
                        form: "Veuillez corriger les erreurs dans le formulaire.",
                        server: "Une erreur inattendue s'est produite. Veuillez réessayer.",
                    }
                }
            },
            reject_friend_request: {
                subtitle: "Vous pouvez bloquer cette demande d'ami pour empêcher cet utilisateur de vous en envoyer d'autres.",
                reject: "Rejeter",
                block: "Bloquer",
                notification: {
                    rejected_success: "La demande d'ami a correctement été rejeté !",
                    blocked_success: "La demande d'ami a correctement été bloqué !",
                    error: {
                        server: "Une erreur inattendue s'est produite. Veuillez réessayer.",
                    }
                }
            },
            accept_friend_request: {
                notification: {
                    success: "La demande d'ami a correctement été accepté !",
                    error: {
                        server: "Une erreur inattendue s'est produite. Veuillez réessayer.",
                    }
                }
            },
        },
    }
}
