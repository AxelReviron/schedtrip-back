<script setup lang="ts">
import { X } from "lucide-vue-next";
import UserInterface from "@/interfaces/userInterface";
import {ref} from "vue";

const props = defineProps<{
    friendId: string,
    friendPseudo: string,
    permission: string,
    isEditable: boolean,
}>();

const isModalVisible = ref(false);

const { friendId, friendPseudo, permission, isEditable } = props;
const friend = ref<UserInterface|null>(null);

const localPermission = ref(permission);
const emit = defineEmits(['permission-changed']);

function handlePermissionChange() {
    emit('permission-changed', friendId, localPermission.value);
}

function handleRemoveParticipantModalVisibility() {//TODO
    if (isEditable) {
        isModalVisible.value = !isModalVisible.value;
    }
}
</script>

<template>
<!--    <RemoveParticipantModal-->
<!--        :friend-id="friendId"-->
<!--        v-if="isModalVisible"-->
<!--        @toggle-visibility="RemoveParticipantModal"-->
<!--    />-->
    <div class="bg-gray-100/20 border border-gray-200 rounded-sm px-2 py-2 shadow-xs w-90 w-full">
        <div class="flex flex-row justify-between items-center">
            <div class="flex justify-start w-full gap-4 items-center">
                <div class="relative inline-flex items-center justify-center w-8 h-8 overflow-hidden bg-cream rounded-full">
                    <span v-if="friendPseudo" class="font-medium text-dark text-sm">
                        {{ friendPseudo.charAt(0).toUpperCase() }}
                    </span>
                </div>
                <div class="flex flex-row items-center text-dark gap-2 text-sm">
                    <p v-if="friendPseudo" class="font-medium">{{ friendPseudo }}</p>
                    <select
                        :disabled="!isEditable"
                        class="font-normal border border-warm bg-warm text-light rounded-sm px-1 disabled:cursor-not-allowed disabled:bg-gray-200 disabled:border-gray-200"
                        v-model="localPermission"
                        @change="handlePermissionChange"
                    >
                        <option value="view">{{ $t("trip.form.create_trip.participants.modal.viewer") }}</option>
                        <option value="update">{{ $t("trip.form.create_trip.participants.modal.editor") }}</option>
                    </select>
                </div>
            </div>
            <button
                :disabled="!isEditable"
                class="relative inline-flex items-center justify-center w-6 h-6 overflow-hidden bg-red-200 hover:bg-red-300 cursor-pointer rounded-lg disabled:cursor-not-allowed disabled:bg-gray-200 disabled:border-gray-200"
            >
                <X
                    @click="handleRemoveOrBlockModalVisibility"
                    :class="isEditable ? 'text-red-600' : 'text-light'"
                    size="16"
                />
            </button>
        </div>
    </div>
</template>

<style scoped>

</style>
