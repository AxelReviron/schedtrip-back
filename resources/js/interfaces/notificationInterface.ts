export default interface NotificationInterface {
    show: boolean;
    message: string;
    type: 'success' | 'error';
}
