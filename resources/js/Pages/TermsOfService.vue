<script setup lang="ts">
import {useI18n} from "vue-i18n";

const {t} = useI18n();

</script>

<template>
    <h1 class="text-4xl font-bold text-dark">
        {{ $t("terms_of_service.title") }}
    </h1>
</template>

<style scoped>

</style>
