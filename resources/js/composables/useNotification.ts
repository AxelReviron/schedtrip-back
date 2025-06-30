// src/composables/useNotification.ts
import { ref } from 'vue';
import NotificationInterface from "@/interfaces/notificationInterface";

type NotificationType = 'success' | 'error';

const notification: NotificationInterface = ref({
    show: false,
    message: '',
    type: 'success' as NotificationType,
});

function showNotification(message: string, type: NotificationType = 'success') {
    notification.value = {
        show: true,
        message,
        type
    };

    setTimeout(() => {
        notification.value.show = false;
    }, 2000);
}

export function useNotification() {
    return {
        notification,
        showNotification
    };
}
