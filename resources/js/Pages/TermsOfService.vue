<script setup lang="ts">
import {useI18n} from "vue-i18n";

const {t} = useI18n();

</script>

<template>
    <h2 class="text-4xl font-bold text-dark">
        {{ $t("terms_of_service.title") }}
    </h2>
</template>

<style scoped>

</style>
