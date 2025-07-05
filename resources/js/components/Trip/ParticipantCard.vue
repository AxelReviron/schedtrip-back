<script setup lang="ts">
import { X } from "lucide-vue-next";
import UserInterface from "@/interfaces/userInterface";
import {onMounted, ref} from "vue";
import {storeToRefs} from "pinia";
import {useUserStore} from "@/stores/userStore";

const props = defineProps<{
    friendId: string,
    permission: string,
}>();

const isModalVisible = ref(false);

const userStore = useUserStore();
const {friendsUsers} = storeToRefs(userStore);

const { friendId, permission } = props;
const friend = ref<UserInterface|null>(null);

const localPermission = ref(permission);
const emit = defineEmits(['permission-changed']);

function handlePermissionChange() {
    emit('permission-changed', friendId, localPermission.value);
}

onMounted(() => {
    friend.value = friendsUsers.value.find((f) => {
        return f.id === friendId;
    });
});

function handleRemoveOrBlockModalVisibility() {//TODO
    isModalVisible.value = !isModalVisible.value;
}
</script>

<template>
<!--    <RemoveFriendModal-->
<!--        :friend-id="friendId"-->
<!--        v-if="isModalVisible"-->
<!--        @toggle-visibility="handleRemoveOrBlockModalVisibility"-->
<!--    />-->
    <div class="bg-gray-100/20 border border-gray-200 rounded-sm px-2 py-2 shadow-xs w-90 w-full">
        <div class="flex flex-row justify-between items-center">
            <div class="flex justify-start w-full gap-4 items-center">
                <div class="relative inline-flex items-center justify-center w-8 h-8 overflow-hidden bg-cream rounded-full">
                    <span v-if="friend && friend.pseudo" class="font-medium text-dark text-sm">
                        {{ friend.pseudo.charAt(0).toUpperCase() }}
                    </span>
                </div>
                <div class="flex flex-row items-center text-dark gap-2 text-sm">
                    <p v-if="friend" class="font-medium">{{ friend.pseudo }}</p>
                    <select
                        class="font-normal border border-warm bg-warm text-light rounded-sm px-1"
                        v-model="localPermission"
                        @change="handlePermissionChange"
                    >
                        <option value="view">{{ $t("trip.form.create_trip.participants.modal.viewer") }}</option>
                        <option value="update">{{ $t("trip.form.create_trip.participants.modal.editor") }}</option>
                    </select>
                </div>
            </div>
            <button class="relative inline-flex items-center justify-center w-6 h-6 overflow-hidden bg-red-200 hover:bg-red-300 cursor-pointer rounded-lg">
                <X
                    @click="handleRemoveOrBlockModalVisibility"
                    class="text-red-600"
                    size="16"
                />
            </button>
        </div>
    </div>
</template>

<style scoped>

</style>
