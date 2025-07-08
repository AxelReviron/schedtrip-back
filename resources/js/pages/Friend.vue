<script setup lang="ts">
import {usePage} from "@inertiajs/vue3";
import { ref } from "vue";
import Navbar from "@/components/Navbar.vue";
import { UserRoundPlus, Mail, UsersRound, UserRoundSearch } from "lucide-vue-next";
import FriendRequestCard from "@/components/Friends/FriendRequestCard.vue";
import FriendCard from "@/components/Friends/FriendCard.vue";
import AddFriendModal from "@/components/Friends/AddFriendModal.vue";
import {useI18n} from "vue-i18n";
import {useUserStore} from "@/stores/userStore";
import {storeToRefs} from "pinia";
import HeroBanner from "@/components/HeroBanner.vue";
import Footer from "@/components/Footer.vue";

const {t} = useI18n();
const page = usePage();
const isModalVisible = ref(false);

const userStore = useUserStore();

function handleModalVisibility() {
    isModalVisible.value = !isModalVisible.value;
}

const {
    user,
    incomingFriendRequestPendingUsers,
    friendsUsers,
    isLoading
} = storeToRefs(userStore);

//TODO: Message if no pending request
//TODO: Link to friend profile.
//TODO: Skeleton on loading
</script>

<template>
    <Navbar/>
    <AddFriendModal
        v-if="isModalVisible"
        @toggle-visibility="handleModalVisibility"
    />
    <div class="bg-light flex flex-col items-center justify-center">
        <div class="w-8/12 mx-auto my-8">
            <HeroBanner
                :title="t('friend.title')"
                :subtitle="t('friend.subtitle')"
                :button-text="t('friend.add_friend')"
                :icon="UserRoundPlus"
                :on-click="handleModalVisibility"
                :is-btn-disabled="false"
            />

            <!--Friend Request-->
            <div class="bg-white border border-gray-200 mt-8 rounded-sm px-4 py-4 shadow-xs">
                <div class="flex flex-row items-center gap-2">
                    <Mail
                        size="24"
                        class="text-dark"
                    />
                    <div class="flex flex-row gap-2 items-center">
                        <h3 class="text-dark font-medium text-[1.4rem]">
                            {{ $t("friend.friend_requests") }}
                        </h3>
                        <div class="relative inline-flex items-center justify-center w-6 h-6 overflow-hidden bg-warm rounded-full">
                            <span class="font-medium text-white text-sm">
                                {{ incomingFriendRequestPendingUsers ? incomingFriendRequestPendingUsers.length : '0' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div v-if="incomingFriendRequestPendingUsers && incomingFriendRequestPendingUsers.length > 0" class="mb-2 overflow-scroll">
                    <div v-for="friendRequest in incomingFriendRequestPendingUsers" :key="friendRequest.id" class="mt-4">
                        <FriendRequestCard
                            :friend-request="friendRequest"
                        />
                    </div>
                </div>
            </div>

            <!--My Friends-->
            <div class="bg-white border border-gray-200 mt-8 rounded-sm px-4 py-4 shadow-xs">
                <div class="flex flex-row items-center gap-2">
                    <UsersRound
                        size="24"
                        class="text-dark"
                    />
                    <div class="flex flex-row gap-2 items-center">
                        <h3 class="text-dark font-medium text-[1.4rem]">
                            {{ $t("friend.my_friends") }}
                        </h3>
                        <div class="relative inline-flex items-center justify-center w-6 h-6 overflow-hidden bg-warm rounded-full">
                            <span class="font-medium text-white text-sm">
                                {{ friendsUsers ? friendsUsers.length : '0' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div v-if="friendsUsers && friendsUsers.length > 0" class="flex flex-row justify-between flex-wrap gap-2">
                    <div v-for="friend in friendsUsers" :key="friend.id" class="mt-4">
                        <FriendCard :friend="friend"/>
                    </div>
                </div>
                <div v-else class="flex flex-col items-center justify-center mt-4">
                    <UserRoundSearch
                        size="42"
                        class="text-warm"
                    />
                    <h3 class="text-xl text-warm">
                        {{ $t("friend.no_friend") }}
                    </h3>
                    <h4 class="text-lg text-warm">
                        {{ $t("friend.travel_net") }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
    <Footer />
</template>

<style scoped>

</style>
