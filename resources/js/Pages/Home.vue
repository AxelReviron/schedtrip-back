<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue';
import { Map, TreePalm, UsersRound, Briefcase, NotebookPen } from "lucide-vue-next";
import Login from "@/Components/Login.vue";

const formData = ref({
    pseudo: '',
    email: '',
    password: '',
    password_confirmed: '',
    consent: false
});
</script>

<template>
    <Head>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
    </Head>
    <div class="flex flex-col lg:flex-row justify-between">


    <div class="relative lg:w-6/12 h-screen overflow-hidden">
        <!--TODO: Add Fallback image-->
        <video
            autoplay
            muted
            loop
            class="absolute top-0 left-0 w-full h-full object-cover"
        >
            <source src="../../assets/video-bg.mp4" type="video/mp4">
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
                    <h1 class="text-3xl md:text-5xl font-bold mb-6 font-[Agbalumo] text-shadow-lg">SchedTrip</h1>
                </div>

                <h2 class="text-lg md:text-2xl font-medium mb-8">
                    Everything you need to plan the perfect journey.
                </h2>
                <ul class="text-xs md:text-xl text-shadow-lg flex flex-col items-start gap-2">
                    <li class="flex flex-row gap-2 items-center">
                        <UsersRound />
                        Organize solo trips or group adventures
                    </li>
                    <li class="flex flex-row gap-2 items-center">
                        <Map />
                        Plan with interactive maps
                    </li>
                    <li class="flex flex-row gap-2 items-center">
                        <NotebookPen />
                        Personalized notes for each stop
                    </li>
                    <li class="flex flex-row gap-2 items-center">
                        <Briefcase />
                        Individual packing lists
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="relative lg:w-6/12 h-screen bg-[#FEFAE0]">
        <Login />
    </div>
    </div>
</template>

<style scoped>

</style>
