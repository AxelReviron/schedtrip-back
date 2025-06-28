<script setup lang="ts">
import NotificationInterface from "@/interfaces/notificationInterface";

const { notification } = defineProps({
    notification: Object as PropType<NotificationInterface>
});
</script>

<template>
    <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 translate-y-4 scale-95"
        enter-to-class="opacity-100 translate-y-0 scale-100"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0 scale-100"
        leave-to-class="opacity-0 translate-y-4 scale-95"
    >
        <div
            v-if="notification.show"
            id="toast-default"
            class="bg-white divide-neutral-200 fixed bottom-4 right-4 z-50 flex items-center w-full max-w-xs p-4 space-x-4 rtl:space-x-reverse rounded-lg shadow-sm"
            :class="{
                'text-green-700': notification.type === 'success',
                'text-red-700': notification.type === 'error'
            }"
            role="alert"
        >
            <div
                class="inline-flex items-center justify-center shrink-0"
                :class="{ 'text-green-600': notification.type === 'success', 'text-red-600': notification.type === 'error' }"
            >
                <div v-if="notification.type === 'error'">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                    </svg>
                    <span class="sr-only">Check icon</span>
                </div>
                <div v-if="notification.type === 'success'">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                    <span class="sr-only">Error icon</span>
                </div>
            </div>
            <div class="ms-3 text-sm font-normal">{{ notification.message }}</div>
        </div>
    </Transition>
</template>

<style scoped>

</style>
