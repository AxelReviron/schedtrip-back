<script setup lang="ts">
import {useI18n} from "vue-i18n";
import Footer from "@/components/Footer.vue";
import Navbar from "@/components/Navbar.vue";
import HeroBanner from "@/components/HeroBanner.vue";

const {t} = useI18n();

</script>

<template>
    <Navbar/>
    <div class="bg-light flex flex-col items-center justify-center">
        <div class="w-8/12 mx-auto my-8 min-h-[70vh]">
            <HeroBanner
                :title="t('privacy_policy.title')"
                :subtitle="t('privacy_policy.subtitle')"
            />
        </div>
    </div>

    <Footer />
</template>

<style scoped>

</style>
