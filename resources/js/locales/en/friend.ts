export default {
    friend: {
        title: "Friends",
        subtitle: "Connect with your friends and explore their adventures.",
        add_friend: "Add Friend",
        delete_friend_request: "Remove friend request",
        remove: "Remove",
        delete_friend: "Remove friend",
        delete_friend_question: "Are you sure you want to remove this friend ?",
        friend_requests: 'Friend Requests',
        my_friends: 'My Friends',
        no_friend: 'No friends yet.',
        travel_net: 'Start building your travel network by adding friends.',
        form: {
            add_friend: {
                pseudo: "Pseudo",
                send: "Send",
                cancel: "Cancel",
                email: "Email",
                notification: {
                    success: "Friend request successfully sent !",
                    error: {
                        form: "Please correct the errors in the form.",
                        server: "An unexpected error occurred. Please try again.",
                    }
                }
            },
            reject_friend_request: {
                subtitle: "You can block this friend request to prevent this user from sending you any more.",
                reject: "Reject",
                block: "Block",
                notification: {
                    rejected_success: "Friend request successfully rejected !",
                    blocked_success: "Friend request successfully blocked !",
                    error: {
                        server: "An unexpected error occurred. Please try again.",
                    }
                }
            },
            accept_friend_request: {
                notification: {
                    success: "Friend request successfully accepted !",
                    error: {
                        server: "An unexpected error occurred. Please try again.",
                    }
                }
            },
            remove_friend: {
                notification: {
                    success: "Friend successfully removed !",
                    error: {
                        server: "An unexpected error occurred. Please try again.",
                    }
                }
            },
        },
    }
}
