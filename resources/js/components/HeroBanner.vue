<script setup lang="ts">
defineProps({
    title: String,
    subtitle: String,
})
</script>

<template>
    <div class="flex flex-row justify-between items-center w-full">
        <div class="flex flex-col items-start">
            <h1 class="text-2xl md:text-3xl font-bold text-dark">
                {{ title }}
            </h1>
            <h2 class="text-sm md:text-lg text-dark mt-2">
                {{ subtitle }}
            </h2>
        </div>
    </div>
</template>

<style scoped>

</style>
