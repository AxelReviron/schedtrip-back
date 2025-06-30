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

    async function refreshUserAllFriendsDetails() {
        const response = await axios.get('/auth/user', {
            _token: page.props.csrf_token,
        });
        user.value = response.data;

        if (user.value) {
            incomingFriendRequestPendingUsers.value = [];
            friendsUsers.value = [];
            await Promise.all([
                loadFriendRequestsDetails(),
                loadFriendsDetails()
            ]);
        }
    }

    return {
        // States
        user,
        incomingFriendRequestPendingUsers,
        friendsUsers,
        isLoading,

        // Actions
        setUser,
        refreshUserAllFriendsDetails,
    }
})
