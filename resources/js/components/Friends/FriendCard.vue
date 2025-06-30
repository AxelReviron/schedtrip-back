<script setup lang="ts">
import { SquareArrowOutUpRight, Trash2 } from "lucide-vue-next";
import UserInterface from "@/interfaces/userInterface";
import RemoveFriendModal from "@/components/Friends/RemoveFriendModal.vue";
import {ref} from "vue";

const isModalVisible = ref(false);

const props = defineProps<{
    friend: UserInterface
}>();

const { friend } = props;

function handleRemoveOrBlockModalVisibility() {
    isModalVisible.value = !isModalVisible.value;
}
</script>

<template>
    <RemoveFriendModal
        :friend-id="friend.id"
        v-if="isModalVisible"
        @toggle-visibility="handleRemoveOrBlockModalVisibility"
    />
    <div class="bg-gray-100/20 border border-gray-200 rounded-sm px-4 py-4  shadow-xs w-90">
        <div class="flex flex-row justify-between items-center">
            <div class="flex justify-start gap-4 items-center">
                <div class="relative inline-flex items-center justify-center w-12 h-12 overflow-hidden bg-cream rounded-full">
                    <span class="font-medium text-dark text-lg">{{ friend.pseudo.charAt(0).toUpperCase() }}</span>
                </div>
                <div class="text-dark">
                    <p class="font-medium">{{ friend.pseudo }}</p>
                    <p>{{ friend.email }}</p>
                </div>
            </div>
            <div class="flex flex-row gap-2">
                <button class="relative inline-flex items-center justify-center w-8 h-8 overflow-hidden bg-warm text-light hover:bg-warmer cursor-pointer rounded-lg">
                    <SquareArrowOutUpRight
                        class="text-light"
                        size="18"
                    />
                </button>
                <button class="relative inline-flex items-center justify-center w-8 h-8 overflow-hidden bg-red-200 hover:bg-red-300 cursor-pointer rounded-lg">
                    <Trash2
                        @click="handleRemoveOrBlockModalVisibility"
                        class="text-red-600"
                        size="20"
                    />
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
