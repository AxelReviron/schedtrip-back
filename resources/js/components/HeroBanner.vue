<script setup lang="ts">
defineProps({
    title: String,
    subtitle: String,
    buttonText: String,
    icon: Object,
    buttonLink: {
        type: String,
        default: null
    },
    onClick: {
        type: Function,
        default: () => {}
    }
})
</script>

<template>
    <div class="flex flex-row justify-between items-center w-full">
        <div class="flex flex-col items-start">
            <h1 class="text-3xl font-bold text-dark">
                {{ title }}
            </h1>
            <h2 class="text-lg text-dark mt-2">
                {{ subtitle }}
            </h2>
        </div>

        <!-- Si `buttonLink` est fourni, on rend un <RouterLink> -->
        <a v-if="buttonLink" :href="buttonLink">
            <button
                class="flex flex-row gap-2 items-center border py-2 bg-warm text-light font-medium rounded-sm px-4 cursor-pointer hover:bg-warmer"
            >
                <component :is="icon" size="20" />
                {{ buttonText }}
            </button>
        </a>

        <!-- Sinon, on appelle une fonction avec `@click` -->
        <button
            v-else
            @click="onClick"
            class="flex flex-row gap-2 items-center border py-2 bg-warm text-light font-medium rounded-sm px-4 cursor-pointer hover:bg-warmer"
        >
            <component :is="icon" size="20" />
            {{ buttonText }}
        </button>
    </div>
</template>

<style scoped>

</style>
