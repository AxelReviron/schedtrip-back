import {defineStore} from "pinia";
import UserInterface from "@/interfaces/userInterface";
import {ref} from "vue";
import axios from "axios";
import {usePage} from "@inertiajs/vue3";

export const useUserStore = defineStore('user', () => {
    const user: UserInterface|null = ref(null);
    const incomingFriendRequestPendingUsers: UserInterface[]|[] = ref([]);
    const friendsUsers: UserInterface[]|[] = ref([]);
    const isLoading: boolean = ref(false);
    const page = usePage();

    function removeUserFriendRequest(friendId: string): void {
        const friendRequestRouteApi = `/api/users/${friendId}`;

        // Remove on user object friend route api
        user.value.incomingFriendsRequestInPending = user.value.incomingFriendsRequestInPending.filter((friendRouteApi: string) => {
            return friendRouteApi !== friendRequestRouteApi;
        });

        // Remove on incomingFriendRequestPendingUsers object
        incomingFriendRequestPendingUsers.value = incomingFriendRequestPendingUsers.value.filter((friend: UserInterface) => {
            return friend.id !== friendId;
        })
    }

    function removeUserFriend(friendId: string): void {
        const friendRequestRouteApi = `/api/users/${friendId}`;

        // Remove on user object friend route api
        user.value.friends = user.value.friends.filter((friendRouteApi: string) => {
            return friendRouteApi !== friendRequestRouteApi;
        });


        // Remove on friendsUsers object
        friendsUsers.value = friendsUsers.value.filter((friend: UserInterface) => {
            return friend.id !== friendId;
        })
    }

    function acceptFriendRequest(friendId: string): void {
        const friendRequestRouteApi = `/api/users/${friendId}`;

        // Get the friend accepted route api
        const friendAcceptedRouteApi = user.value.incomingFriendsRequestInPending.filter((friendRouteApi: string) => {
            return friendRouteApi === friendRequestRouteApi;
        });

        // Get friend infos
        const newFriend = incomingFriendRequestPendingUsers.value.filter((friend: UserInterface) => {
            return friend.id === friendId;
        })

        removeUserFriendRequest(friendId);

        user.value.friends.push(friendAcceptedRouteApi[0]);
        friendsUsers.value.push(newFriend[0]);
    }

    async function loadFriendRequestsDetails() {
        if (!user.value?.incomingFriendsRequestInPending?.length) {
            incomingFriendRequestPendingUsers.value = [];
            return;
        }

        isLoading.value = true;
        try {
            for (const friendRequestApiRoute of user.value.incomingFriendsRequestInPending) {
                const response = await axios.get(friendRequestApiRoute, {
                    _token: page.props.csrf_token,
                });
                incomingFriendRequestPendingUsers.value.push(response.data);
            }
        } catch (error) {
            incomingFriendRequestPendingUsers.value = [];
        } finally {
            isLoading.value = false;
        }
    }

    async function loadFriendsDetails() {
        if (!user.value?.friends?.length) {
            friendsUsers.value = [];
            return;
        }

        isLoading.value = true;
        try {
            for (const friendApiRoute of user.value.friends) {
                const response = await axios.get(friendApiRoute, {
                    _token: page.props.csrf_token,
                })
                friendsUsers.value.push(response.data);
            }
        } catch (error) {
            console.error('Erreur lors du chargement des amis:', error);
            friendsUsers.value = [];
        } finally {
            isLoading.value = false;
        }
    }

    async function setUser(userData: UserInterface) {
        user.value = userData;
        if (user.value) {
            await Promise.all([
                loadFriendRequestsDetails(),
                loadFriendsDetails()
            ]);
        }
    }

    function addFriends(newFriendsData: UserInterface[]) {
        friendsUsers.value.push(newFriendsData);
    }

    return {
        // States
        user,
        incomingFriendRequestPendingUsers,
        friendsUsers,
        isLoading,

        // Actions
        setUser,
        removeUserFriendRequest,
        removeUserFriend,
        acceptFriendRequest,
        addFriends,
    }
})
