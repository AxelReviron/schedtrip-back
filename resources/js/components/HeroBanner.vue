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
    },
    isBtnDisabled: Boolean,
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

        <a v-if="buttonLink" :href="buttonLink">
            <button
                :disabled="isBtnDisabled"
                class="flex flex-row gap-2 items-center border py-2 px-2 md:px-4 bg-warm text-light font-medium rounded-sm cursor-pointer hover:bg-warmer disabled:cursor-not-allowed disabled:bg-gray-200 disabled:border-gray-200"
            >
                <component :is="icon" size="20" />
                {{ buttonText }}
            </button>
        </a>

        <button
            v-else
            @click="onClick"
            :disabled="isBtnDisabled"
            class="flex flex-row gap-2 items-center border py-2 px-2 md:px-4 bg-warm text-light font-medium rounded-sm cursor-pointer hover:bg-warmer disabled:cursor-not-allowed disabled:bg-gray-200 disabled:border-gray-200"
        >
            <component :is="icon" size="20" />
            {{ buttonText }}
        </button>
    </div>
</template>

<style scoped>

</style>
