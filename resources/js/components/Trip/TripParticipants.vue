<script setup lang="ts">
import {UsersRound} from "lucide-vue-next";
import TitleIcon from "@/components/TitleIcon.vue";
import {useI18n} from "vue-i18n";

const {t} = useI18n();

</script>

<template>
    <div class="bg-white border border-gray-200 mt-8 rounded-sm px-4 py-4 shadow-xs w-full h-[22.25rem]">
        <TitleIcon
            :title="t('trip.form.create_trip.participants.title')"
            :icon="UsersRound"
        />

    </div>
</template>

<style scoped>

</style>
