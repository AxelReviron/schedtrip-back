<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { Map, TreePalm, UsersRound, Briefcase, NotebookPen } from "lucide-vue-next";
import Login from "@/components/Welcome/Login.vue";
import {useI18n} from "vue-i18n";
import {ref} from "vue";
import Register from "@/components/Welcome/Register.vue";

const {t} = useI18n();
const isLoginFormVisible = ref(true);

function handleLoginFormVisibility(newValue: boolean) {
    isLoginFormVisible.value = newValue;
}

</script>

<template>
    <Head>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
    </Head>
    <div class="flex flex-col lg:flex-row justify-between">

    <div class="relative lg:w-6/12 h-screen overflow-hidden">
        <video
            autoplay
            muted
            loop
            playsinline
            preload="auto"
            poster="../../assets/fallback-video.webp"
            class="absolute top-0 left-0 w-full h-full object-cover"
        >
            <source src="../../assets/video-bg.webm" type="video/mp4">
        </video>

        <div class="absolute inset-0 bg-black/20"></div>

        <div class="relative z-10 flex items-center justify-center gap-4 h-full text-white">
            <div class="text-center py-8 px-8 rounded-lg border-1 border-white/20 bg-white/10 backdrop-blur-sm text-cream">
                <div class="flex flex-row gap-2 justify-center">
                    <div class="hidden md:block">
                        <TreePalm
                            :size="52"
                            stroke-width="2"
                            class="drop-shadow-sm"
                        />
                    </div>
                    <div class="block md:hidden">
                        <TreePalm
                            :size="32"
                            stroke-width="2"
                            class="drop-shadow-sm"
                        />
                    </div>
                    <h1 class="text-3xl md:text-5xl font-bold mb-6 font-[Agbalumo] text-shadow-lg">
                        {{ $t("home.title") }}
                    </h1>
                </div>

                <h2 class="text-lg md:text-2xl font-medium mb-8">
                    {{ $t("home.subtitle") }}
                </h2>
                <ul class="text-xs md:text-xl text-shadow-lg flex flex-col items-start gap-2">
                    <li class="flex flex-row gap-2 items-center">
                        <UsersRound />
                        {{ $t("home.feature_user") }}
                    </li>
                    <li class="flex flex-row gap-2 items-center">
                        <Map />
                        {{ $t("home.feature_map") }}
                    </li>
                    <li class="flex flex-row gap-2 items-center">
                        <NotebookPen />
                        {{ $t("home.feature_note") }}
                    </li>
                    <li class="flex flex-row gap-2 items-center">
                        <Briefcase />
                        {{ $t("home.feature_luggage") }}
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="relative lg:w-6/12 h-screen bg-[#FEFAE0]">
        <div v-if="isLoginFormVisible">
            <Login @toggle-visibility="handleLoginFormVisibility"/>
        </div>
        <div v-else>
            <Register @toggle-visibility="handleLoginFormVisibility"/>
        </div>
    </div>
    </div>
</template>

<style scoped>

</style>
