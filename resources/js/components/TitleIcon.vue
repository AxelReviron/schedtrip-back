<script setup lang="ts">

defineProps({
    title: String,
    icon: Object,
})
</script>

<template>
    <div class="flex flex-row items-center gap-2">
        <component :is="icon" size="24" class="text-dark"/>
        <div class="flex flex-row gap-2 items-center">
            <h3 class="text-dark font-medium text-[1.4rem]">
                {{ title }}
            </h3>
        </div>
    </div>
</template>

<style scoped>

</style>
