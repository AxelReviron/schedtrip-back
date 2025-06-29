<script setup lang="ts">
import { Check, X } from "lucide-vue-next";
</script>

<template>
    <div class="bg-gray-100/20 border border-gray-200 rounded-sm px-4 py-4 flex flex-row justify-between shadow-xs">
        <div class="flex justify-start gap-4 items-center">
            <div class="relative inline-flex items-center justify-center w-12 h-12 overflow-hidden bg-cream rounded-full">
                <span class="font-medium text-dark text-lg">J</span>
            </div>
            <div class="text-dark">
                <p class="font-medium">John</p>
                <p>john.doe@mail.com</p>
            </div>
        </div>
        <div class="flex justify-start gap-2 items-center">
            <div class="relative inline-flex items-center justify-center w-8 h-8 overflow-hidden bg-green-200 hover:bg-green-300 cursor-pointer rounded-lg">
                <Check
                    class="text-green-600"
                    size="20"
                />
            </div>
            <div class="relative inline-flex items-center justify-center w-8 h-8 overflow-hidden bg-red-200 hover:bg-red-300 cursor-pointer rounded-lg">
                <X
                    class="text-red-600"
                    size="20"
                />
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
