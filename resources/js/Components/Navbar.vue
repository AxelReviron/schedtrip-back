<script setup lang="ts">
import {TreePalm, CircleUserRound, Globe, MapPin, UsersRound, Settings, LogOut} from "lucide-vue-next";
import {Head, usePage} from "@inertiajs/vue3";
import {computed, ref} from "vue";
import axios from "axios";

const page = usePage()
const currentRoute = computed(() => page.props.ziggy.location);
const currentPage = currentRoute.value.split('/').pop() || 'home'
const user = computed(() => page.props.auth.user)
const isUserMenuOpen = ref(false)
const isMobileMenuOpen = ref(false)

function toggleUserMenu(): void {
    isUserMenuOpen.value = !isUserMenuOpen.value
}

function toggleMobileMenu(): void {
    isMobileMenuOpen.value = !isMobileMenuOpen.value
}

async function logout(): void {
    await axios.post('auth/logout', {
        _token: page.props.csrf_token,
    });
    window.location.href = '/';
}

</script>

<template>
    <Head>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
    </Head>

    <nav class="bg-white border-gray-200">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <TreePalm
                    :size="32"
                    stroke-width="2"
                />
                <span class="self-center text-2xl font-medium font-[Agbalumo]">SchedTrip</span>
            </a>
            <div class="relative flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <button @click="toggleUserMenu" type="button" class="flex text-sm rounded-full md:me-0 cursor-pointer" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                    <span class="sr-only">Open user menu</span>
                    <CircleUserRound />
                </button>
                <!-- Dropdown menu -->
                <div :class="{'hidden': !isUserMenuOpen}" class="absolute top-5 right-0 z-50 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-sm" id="user-dropdown">
                    <div class="px-4 py-3">
                        <span v-if="user.pseudo" class="block text-sm text-gray-900">
                            {{ user.pseudo }}
                        </span>
                        <span v-if="user.email" class="block text-sm  text-gray-500 truncate">
                            {{ user.email }}
                        </span>
                    </div>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <a href="/setting" class="flex flex-row gap-2 items-center block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <Settings
                                    size="18"
                                />
                                Settings
                            </a>
                        </li>
                        <li>
                            <a @click="logout" href="#" class="flex flex-row gap-2 items-center block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <LogOut
                                    size="18"
                                />
                                Sign out
                            </a>
                        </li>
                    </ul>
                </div>
                <button @click="toggleMobileMenu" data-collapse-toggle="navbar-user" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 cursor-pointer" aria-controls="navbar-user" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                    </svg>
                </button>
            </div>
            <div :class="{'hidden': !isMobileMenuOpen}" class="items-center justify-between w-full md:flex md:w-auto md:order-1" id="navbar-user">
                <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white">
                    <li>
                        <a
                            href="/dashboard"
                            :class="{'bg-warm md:text-warm': currentPage === 'dashboard'}"
                            class="flex flex-row gap-2 items-center block py-2 px-3 text-gray-900 rounded-sm md:bg-transparent md:hover:text-warm md:p-0"
                        >
                            <Globe
                                size="18"
                            />
                            Discover
                        </a>
                    </li>
                    <li>
                        <a
                            href="/trip"
                            :class="{'bg-warm md:text-warm': currentPage === 'trip'}"
                            class="flex flex-row gap-2 items-center block py-2 px-3 text-gray-900 rounded-sm md:bg-transparent md:hover:text-warm md:p-0"
                        >
                            <MapPin
                                size="18"
                            />
                            My Trips
                        </a>
                    </li>
                    <li>
                        <a
                            href="/friend"
                            :class="{'bg-warm md:text-warm': currentPage === 'friend'}"
                            class="flex flex-row gap-2 items-center block py-2 px-3 text-gray-900 rounded-sm md:bg-transparent md:hover:text-warm md:p-0"
                        >
                            <UsersRound
                                size="18"
                            />
                            Friends
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

</template>

<style scoped>

</style>
