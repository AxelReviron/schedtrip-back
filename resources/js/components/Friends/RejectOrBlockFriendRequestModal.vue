<script setup lang="ts">
import {Trash2, X} from "lucide-vue-next";
import {useI18n} from "vue-i18n";
import {ref} from "vue";
import axios from "axios";
import {usePage} from "@inertiajs/vue3";
import Notification from "@/components/Notification.vue";
import {useUserStore} from "@/stores/userStore";
import {storeToRefs} from "pinia";
import {useNotification} from "@/composables/useNotification";

const page = usePage()
const {t} = useI18n();

const props = defineProps<{
    friendId: string
}>();

const { friendId } = props;
const emit = defineEmits(['toggle-visibility']);

const userStore = useUserStore();
const { user } = storeToRefs(userStore);
const { removeUserFriendRequest } = userStore;

const errors = ref({});
const { notification, showNotification } = useNotification();

async function rejectFriendRequest(status: string) {
    errors.value = {};
    try {
        await axios.patch('api/users/friends/action', {
            _token: page.props.csrf_token,
            user_id: friendId,
            status: status
        });

        if (status === 'rejected') {
            showNotification(t("friend.form.reject_friend_request.notification.rejected_success"), 'success');
        } else if (status === 'blocked') {
            showNotification(t("friend.form.reject_friend_request.notification.blocked_success"), 'success');
        }
        setTimeout(async () => {
            removeUserFriendRequest(friendId);
            emit('toggle-visibility');
        }, 2000);
    } catch (error: any) {
        showNotification(t("friend.form.notification.error.server"), 'error');
    }
}
</script>

<template>

    <div class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="z-99 absolute bg-black/20 backdrop-blur-[0.1rem] w-full h-full"></div>

        <div class="z-100 w-100 bg-white border border-gray-200 rounded-sm px-4 py-4 shadow-lg">

            <div class="flex flex-row justify-between mb-2">
                <div
                    class="flex flex-row gap-2 items-center font-medium">
                    <Trash2
                        class="text-dark"
                        size="24"
                    />
                    <h3 class="text-xl text-dark">{{ $t("friend.delete_friend_request") }}</h3>
                </div>
                <div class="relative inline-flex items-center justify-center w-8 h-8 overflow-hidden cursor-pointer rounded-lg">
                    <X
                        @click="emit('toggle-visibility')"
                        class="text-dark"
                        size="20"
                    />
                </div>
            </div>

            <div>
                <form class="text-dark flex flex-col items-center" name="login" method="POST" @submit="handleSubmit">
                    <div class="flex flex-col mt-2 w-full text-dark">
                        <h3>
                            {{ $t("friend.form.reject_friend_request.subtitle") }}
                        </h3>
                    </div>
                    <div class="flex flex-row gap-2 justify-between w-full">
                        <button
                            type="button"
                            @click="rejectFriendRequest('rejected')"
                            class="mt-4 py-2 px-4 w-full border border-red-500 border-2 rounded-lg text-red-500 font-medium cursor-pointer"
                        >
                            {{ $t("friend.form.reject_friend_request.reject") }}
                        </button>
                        <button
                            type="button"
                            @click="rejectFriendRequest('blocked')"
                            class="mt-4 py-2 px-4 w-full border border-red-500 bg-red-500 text-white font-medium rounded-lg px-4 cursor-pointer hover:bg-red-600"
                        >
                            {{ $t("friend.form.reject_friend_request.block") }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <Notification :notification="notification"/>
</template>

<style scoped>

</style>
