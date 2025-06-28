<script setup lang="ts">

import {usePage} from "@inertiajs/vue3";
import {computed} from "vue";

const page = usePage()
const user = computed(() => page.props.auth.user)

console.log(user.value);
</script>

<template>
    <h1 class="text-4xl font-bold text-dark">
        Dashboard
    </h1>
</template>

<style scoped>

</style>
