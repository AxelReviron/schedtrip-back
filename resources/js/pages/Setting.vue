<script setup lang="ts">

import {usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import Navbar from "@/components/Navbar.vue";
import Footer from "@/components/Footer.vue";

const page = usePage()
const user = computed(() => page.props.auth.user)

</script>

<template>
    <Navbar />
    <div class="bg-[#FEFAE0]">
        <h1 class="text-4xl font-bold text-dark">
            Setting
        </h1>
    </div>
    <Footer />
</template>

<style scoped>

</style>
