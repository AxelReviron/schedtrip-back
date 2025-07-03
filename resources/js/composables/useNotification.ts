// src/composables/useNotification.ts
import { ref } from 'vue';
import NotificationInterface from "@/interfaces/notificationInterface";

type NotificationType = 'success' | 'error';

const notification: NotificationInterface = ref({
    show: false,
    message: '',
    type: 'success' as NotificationType,
});

function showNotification(message: string, type: NotificationType = 'success', timeout: number = 2000) {
    notification.value = {
        show: true,
        message,
        type
    };

    setTimeout(() => {
        notification.value.show = false;
    }, timeout);
}

export function useNotification() {
    return {
        notification,
        showNotification
    };
}
