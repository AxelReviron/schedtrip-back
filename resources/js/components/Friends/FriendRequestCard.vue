<script setup lang="ts">
import { Check, X } from "lucide-vue-next";
import UserInterface from "@/interfaces/userInterface";
import RejectOrBlockFriendRequestModal from "@/components/Friends/RejectOrBlockFriendRequestModal.vue";
import {ref} from "vue";
import axios from "axios";
import Notification from "@/components/Notification.vue";
import {useI18n} from "vue-i18n";
import {usePage} from "@inertiajs/vue3";
import {useUserStore} from "@/stores/userStore";
import {useNotification} from "@/composables/useNotification";

const {t} = useI18n();
const page = usePage()

const props = defineProps<{
    friendRequest: UserInterface
}>();
const { friendRequest } = props;

const isModalVisible = ref(false);

const userStore = useUserStore();
const { refreshUserAllFriendsDetails } = userStore;

function handleRejectOrBlockModalVisibility() {
    isModalVisible.value = !isModalVisible.value;
}

const errors = ref({});

const { notification, showNotification } = useNotification();

async function acceptFriendRequest() {
    errors.value = {};
    try {
        await axios.patch('api/users/friends/action', {
            _token: page.props.csrf_token,
            user_id: friendRequest.id,
            status: 'accepted'
        });

        showNotification(t("friend.form.accept_friend_request.notification.success"), 'success');
        setTimeout(async () => {
            await refreshUserAllFriendsDetails();
        }, 2000);
    } catch (error: any) {
        console.log(error)
        showNotification(t("friend.form.notification.error.server"), 'error');
    }
}
</script>

<template>
    <RejectOrBlockFriendRequestModal
        :friend-id="friendRequest.id"
        v-if="isModalVisible"
        @toggle-visibility="handleRejectOrBlockModalVisibility"
    />

    <div class="bg-gray-100/20 border border-gray-200 rounded-sm px-4 py-4 flex flex-row justify-between shadow-xs">
        <div class="flex justify-start gap-4 items-center">
            <div class="relative inline-flex items-center justify-center w-12 h-12 overflow-hidden bg-cream rounded-full">
                <span class="font-medium text-dark text-lg">{{ friendRequest.pseudo.charAt(0).toUpperCase() }}</span>
            </div>
            <div class="text-dark">
                <p class="font-medium">
                    {{ friendRequest.pseudo }}
                </p>
                <p>
                    {{ friendRequest.email }}
                </p>
            </div>
        </div>
        <div class="flex justify-start gap-2 items-center">
            <div class="relative inline-flex items-center justify-center w-8 h-8 overflow-hidden bg-green-200 hover:bg-green-300 cursor-pointer rounded-lg">
                <Check
                    @click="acceptFriendRequest"
                    class="text-green-600"
                    size="20"
                />
            </div>
            <div class="relative inline-flex items-center justify-center w-8 h-8 overflow-hidden bg-red-200 hover:bg-red-300 cursor-pointer rounded-lg">
                <X
                    @click="handleRejectOrBlockModalVisibility"
                    class="text-red-600"
                    size="20"
                />
            </div>
        </div>
        <Notification :notification="notification"/>
    </div>
</template>

<style scoped>

</style>
