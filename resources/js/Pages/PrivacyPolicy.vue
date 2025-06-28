<script setup lang="ts">
import {useI18n} from "vue-i18n";

const {t} = useI18n();

</script>

<template>
    <h2 class="text-4xl font-bold text-dark">
        {{ $t("privacy_policy.title") }}
    </h2>
</template>

<style scoped>

</style>
