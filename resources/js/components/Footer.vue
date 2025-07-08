<script setup lang="ts">
import {TreePalm} from "lucide-vue-next";
import {useI18n} from "vue-i18n";

const {t} = useI18n();

</script>

<template>
    <footer class="bg-light">
        <div class="w-full max-w-screen-xl mx-auto p-4 md:py-8">
            <hr class="my-6 border-gray-200 sm:mx-auto" />
            <div class="sm:flex sm:items-center sm:justify-between">
                <a href="/" class="flex items-center mb-4 sm:mb-0 space-x-3 rtl:space-x-reverse">
                    <TreePalm
                        :size="32"
                        stroke-width="2"
                        class="text-warm drop-shadow-xs"
                    />
                    <span class="self-center text-2xl font-medium font-[Agbalumo] text-warm text-shadow-xs">
                            {{ $t("footer.title") }}
                    </span>
                </a>
                <ul class="flex flex-wrap items-center mb-6 text-sm font-medium text-gray-500 sm:mb-0">
                    <li>
                        <a href="/about" class="hover:underline me-4 md:me-6">
                            {{ $t("footer.links.about") }}
                        </a>
                    </li>
                    <li>
                        <a href="/privacy-policy" class="hover:underline me-4 md:me-6">
                            {{ $t("footer.links.privacy_policy") }}
                        </a>
                    </li>
                    <li>
                        <a href="/terms-of-service" class="hover:underline me-4 md:me-6">
                            {{ $t("footer.links.terms_of_service") }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>
</template>

<style scoped>

</style>
