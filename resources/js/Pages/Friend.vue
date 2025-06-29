<script setup lang="ts">

import {usePage} from "@inertiajs/vue3";
import {computed, ref} from "vue";
import Navbar from "@/Components/Navbar.vue";
import { UserRoundPlus, Mail, UsersRound } from "lucide-vue-next";
import FriendRequestCard from "@/Components/Friends/FriendRequestCard.vue";
import FriendCard from "@/Components/Friends/FriendCard.vue";
import FriendRequestModal from "@/Components/Friends/FriendRequestModal.vue";
import {useI18n} from "vue-i18n";

const {t} = useI18n();
const page = usePage()
const user = computed(() => page.props.auth.user)
const isModalVisible = ref(false);

function handleModalVisibility() {
    isModalVisible.value = !isModalVisible.value;
}
</script>

<template>
    <Navbar/>
    <FriendRequestModal
        v-if="isModalVisible"
        @toggle-visibility="handleModalVisibility"
    />
    <div class="bg-light flex flex-col items-center justify-center">
        <div class="w-8/12 mx-auto my-8">
            <!--Hero, Button-->
            <div class="flex flex-row justify-between items-center w-full">
                <div class="flex flex-col items-start">
                    <h1 class="text-3xl font-bold text-dark">
                        {{ $t("friend.title") }}
                    </h1>
                    <h2 class="text-lg text-dark">
                        {{ $t("friend.subtitle") }}
                    </h2>
                </div>
                <button
                    @click="handleModalVisibility"
                    class="flex flex-row gap-2 items-center border h-12 bg-warm text-light font-medium rounded-sm px-4 cursor-pointer hover:bg-warmer">
                    <UserRoundPlus
                        size="20"
                    />
                    {{ $t("friend.add_friend") }}
                </button>
            </div>

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
                            <span class="font-medium text-white text-sm">4</span>
                        </div>
                    </div>
                </div>
                <div class="h-55 overflow-scroll">
                    <div v-for="n in 4" class="mt-4">
                        <FriendRequestCard />
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
                            <span class="font-medium text-white text-sm">4</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row flex-wrap gap-2">
                    <div v-for="n in 4" class="mt-4">
                        <FriendCard />
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>

</style>
