<script setup lang="ts">
import {useI18n} from "vue-i18n";
import Footer from "@/components/Footer.vue";

const {t} = useI18n();

</script>

<template>
    <h1 class="text-4xl font-bold text-dark">
        {{ $t("privacy_policy.title") }}
    </h1>

    <Footer />
</template>

<style scoped>

</style>
