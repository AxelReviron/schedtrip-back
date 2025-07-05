<script setup lang="ts">
import {Search, UsersRound} from "lucide-vue-next";
import TitleIcon from "@/components/TitleIcon.vue";
import {useI18n} from "vue-i18n";
import {ref, watch} from "vue";
import UserInterface from "@/interfaces/userInterface";
import {storeToRefs} from "pinia";
import {useUserStore} from "@/stores/userStore";
import {useNotification} from "@/composables/useNotification";
import {useTripFormStore} from "@/stores/tripFormStore";
import ParticipantCard from "@/components/Friends/ParticipantCard.vue";
import AddParticipantModal from "@/components/Trip/AddParticipantModal.vue";

const {t} = useI18n();
const { notification, showNotification } = useNotification();

const userStore = useUserStore();
const tripFormStore = useTripFormStore();

const {friendsUsers} = storeToRefs(userStore);
const {trip} = storeToRefs(tripFormStore);

const friendSearched = ref<string>(null);
const searchResults = ref<UserInterface[]|[]>([]);

const isModalVisible = ref(false);
const selectedParticipantToAdd = ref(null);

/**
 * Trigger when user start a new search.
 */
watch(
    () => friendSearched.value,
    () => {
        if (searchResults.value && searchResults.value.length > 0) {
            searchResults.value = [];
        }
    }
);

watch(
    () => trip.value.participants,
    () => {
        console.log(trip.value.participants)
    },
    {deep: true}
);

async function handleFriendSearch(e) {
    e.preventDefault();
    let tempFriends: UserInterface[]|[] = [];

    if (!friendsUsers.value) {
        showNotification(t("trip.form.create_trip.notification.error.no_friends"), 'error', 5000);
    }
    friendsUsers.value.forEach((friend) => {
        if (friend.pseudo.toLowerCase().includes(friendSearched.value.toLowerCase())) {
            tempFriends.push(friend);
        }
    })

    searchResults.value = tempFriends;
    if (!searchResults.value.length > 0) {
        showNotification(t("trip.form.create_trip.notification.error.search_friend_error"), 'error', 5000);
    }
}

function handleAddParticipantModalVisibility(participant = null) {
    selectedParticipantToAdd.value = participant;
    isModalVisible.value = !!participant;
}

function handlePermissionChange(friendId: string, newPermission: string) {
    console.log('permission changed')
    tripFormStore.updateParticipantPermission(friendId, newPermission);
}
</script>

<template>
    <AddParticipantModal
        :participant="selectedParticipantToAdd"
        v-if="isModalVisible"
        @toggle-visibility="handleAddParticipantModalVisibility"
    />
    <div class="bg-white border border-gray-200 mt-8 rounded-sm px-4 py-4 shadow-xs w-full h-[22.25rem]">
        <TitleIcon
            :title="t('trip.form.create_trip.participants.title')"
            :icon="UsersRound"
        />

        <form class="mt-4 flex flex-row gap-2" name="searchPlace" method="GET" @submit="handleFriendSearch">
            <input
                type="search"
                v-model="friendSearched"
                :placeholder="t('trip.form.create_trip.participants.search_placeholder')"
                class="bg-white/70 border border-warm p-2 rounded-sm text-warm focus:outline-warm w-full"
                name="search" required
                id="search"
            >
            <button
                class="flex flex-row gap-2 items-center border border-warm py-2 bg-warm font-medium rounded-sm px-4 cursor-pointer hover:bg-warmer"
                @click="handleFriendSearch"
            >
                <Search
                    class="text-white"
                    size="20"
                />
            </button>
        </form>

        <transition name="max-height-expand">
            <div v-if="searchResults && searchResults.length > 0"
                 class="bg-white border border-warm mt-2 rounded-sm shadow-xs flex flex-col h-25 overflow-scroll"
            >
                <div v-for="searchResult in searchResults" :key="searchResult.id"
                     class="cursor-pointer hover:bg-warm hover:text-light px-2 py-2"
                     @click="handleAddParticipantModalVisibility(searchResult)"
                >
                    <p v-if="searchResult.pseudo">
                        {{ searchResult.pseudo }}
                    </p>
                </div>
            </div>
        </transition>

        <div class="w-full border border-1 my-4 rounded-sm text-warm opacity-30"></div>

        <div v-if="trip.participants && trip.participants.length > 0" class="min-h-[40vh]">
            <div
                v-for="(participant, index) in trip.participants" :key="participant.user_id"
            >
                <ParticipantCard
                    :friend-id="participant.user_id"
                    :permission="participant.permission"
                    @permission-changed="handlePermissionChange"
                />
            </div>
        </div>
        <div v-else class="flex flex-col items-center justify-center mt-8">
            <UsersRound
                size="42"
                class="text-warm"
            />
            <h3 class="text-xl text-warm">
                {{ $t("trip.form.create_trip.participants.no_participant_title") }}
            </h3>
            <h4 class="text-[1rem] text-warm text-center">
                {{ $t("trip.form.create_trip.participants.no_participant_subtitle") }}
            </h4>
        </div>

    </div>
</template>

<style scoped>
input[type="search"]::-webkit-search-cancel-button {
    /* Hide default apparence */
    -webkit-appearance: none;
    appearance: none;

    width: 20px;
    height: 20px;

    cursor: pointer;

    /* Replace with SVG and another color */
    background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%23D4A574"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>') no-repeat center center;
    background-size: contain;
}
input[type="search"]::-webkit-search-cancel-button:hover {
    background: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%238B4513"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>') no-repeat center center;
}
/* Search result animations */
.max-height-expand-enter-active, .max-height-expand-leave-active {
    transition: max-height 0.5s ease-out, opacity 0.3s ease;
}
.max-height-expand-enter-from, .max-height-expand-leave-to {
    max-height: 0;
    opacity: 0;
}
.max-height-expand-enter-to, .max-height-expand-leave-from {
    max-height: 220px; /* Ou une valeur plus grande, par exemple 500px, selon votre contenu max */
    opacity: 1;
}
</style>
