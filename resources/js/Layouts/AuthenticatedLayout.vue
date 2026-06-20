<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <!-- Background wrapper with smooth light pastel tone -->
    <div class="flex h-screen bg-pink-50/40 overflow-hidden font-sans antialiased">
        
        <!-- ========================================================================= -->
        <!-- DESKTOP SIDEBAR PANEL                                                     -->
        <!-- ========================================================================= -->
        <aside class="hidden md:flex md:flex-shrink-0 flex-col w-64 bg-pink-100/90 text-stone-700 shadow-lg border-r border-pink-200/50 z-20 backdrop-blur-sm">
            
            <!-- Brand Identity Header -->
<div class="flex items-center h-16 px-6 border-b border-pink-200/60 logo-gradient-wave">
    <Link :href="route('dashboard')" class="flex items-center gap-3 group transition duration-200">
        <!-- Logo Image Element -->
        <img 
            src="/storage/images/logo_kawa.png" 
            alt="KAWA LIPA Logo" 
            class="w-14 h-14 rounded-lg object-cover shadow-xs transform transition-transform duration-200 group-hover:scale-105 group-hover:rotate-3"
        />
        
        <!-- Text Wrapper Container -->
        <div class="flex flex-col">
            <span class="font-black text-xl tracking-widest text-white drop-shadow-[0_1.5px_2px_rgba(136,19,55,0.4)] group-hover:text-pink-50 transition-colors duration-200">KAWA LIPA</span>
            <span class="text-[10px] tracking-widest uppercase text-white/90 font-bold -mt-0.5 ml-0.5 drop-shadow-[0_1px_1px_rgba(0,0,0,0.1)]">Admin Dashboard</span>
        </div>
    </Link>
</div>

            <!-- Workspace Navigation Links -->
            <nav class="flex-1 px-3 py-6 space-y-2 overflow-y-auto">
                
                <!-- [DESKTOP] LINK: DASHBOARD -->
                <!-- ----------------------------------------------------------------- -->
                <Link
                    :href="route('dashboard')"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold rounded-xl transform transition-all duration-200 ease-out hover:scale-[1.02] active:scale-[0.98] hover:translate-x-1"
                    :class="route().current('dashboard') 
                        ? 'bg-gradient-to-r from-rose-400 to-pink-500 text-white shadow-md shadow-pink-500/20' 
                        : 'text-pink-900/80 hover:bg-white/60 hover:text-pink-700 shadow-xs'"
                >
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 8.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                    <span>Dashboard</span>
                </Link>

                <!-- [DESKTOP] LINK: MENUS CATALOG (Updated Route) -->
                <!-- ----------------------------------------------------------------- -->
                <Link
                    :href="route('admin.menus.index')"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold rounded-xl transform transition-all duration-200 ease-out hover:scale-[1.02] active:scale-[0.98] hover:translate-x-1"
                    :class="route().current('admin.menus.*') 
                        ? 'bg-gradient-to-r from-rose-400 to-pink-500 text-white shadow-md shadow-pink-500/20' 
                        : 'text-pink-900/80 hover:bg-white/60 hover:text-pink-700 shadow-xs'"
                >
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    <span>Menus</span>
                </Link>

                <!-- [DESKTOP] LINK: ANNOUNCEMENTS (Ready for Route) -->
                <!-- ----------------------------------------------------------------- -->
                <Link
                    :href="route('dashboard')"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold rounded-xl transform transition-all duration-200 ease-out hover:scale-[1.02] active:scale-[0.98] hover:translate-x-1"
                    :class="route().current('announcements.*') 
                        ? 'bg-gradient-to-r from-rose-400 to-pink-500 text-white shadow-md shadow-pink-500/20' 
                        : 'text-pink-900/80 hover:bg-white/60 hover:text-pink-700 shadow-xs'"
                >
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.213M10.34 15.84a19.861 19.861 0 004.46-2.223m-4.46 2.223V5.672m4.46 7.945A19.86 19.86 0 0110.34 5.672m4.46 7.945a19.673 19.673 0 002.348-3.158m-2.348 3.158a19.861 19.861 0 001.24-5.336m1.108 2.178A1.919 1.919 0 0118 9.696v.608c0 .248-.045.49-.132.717m-.46-3.076a1.932 1.932 0 010 2.752m0-2.752a1.932 1.932 0 000 2.752" />
                    </svg>
                    <span>Announcements</span>
                </Link>

                <!-- [DESKTOP] LINK: VIBE GALLERY (Ready for Route) -->
                <!-- ----------------------------------------------------------------- -->
                <Link
                    :href="route('dashboard')"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold rounded-xl transform transition-all duration-200 ease-out hover:scale-[1.02] active:scale-[0.98] hover:translate-x-1"
                    :class="route().current('gallery.*') 
                        ? 'bg-gradient-to-r from-rose-400 to-pink-500 text-white shadow-md shadow-pink-500/20' 
                        : 'text-pink-900/80 hover:bg-white/60 hover:text-pink-700 shadow-xs'"
                >
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <span>Vibe Gallery</span>
                </Link>
            </nav>

            <!-- Sidebar Footnote Details -->
            <div class="p-4 border-t border-pink-200/40 bg-pink-200/10 text-[11px] font-bold text-pink-700/50 text-center tracking-widest">
                KAWA DASHBOARD v1.0.0
            </div>
        </aside>

        <!-- ========================================================================= -->
        <!-- CORE PLATFORM MAIN WORKSPACE LAYOUT                                       -->
        <!-- ========================================================================= -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Global Top Utility Navigation Bar -->
            <nav class="border-b border-pink-100 bg-white/80 backdrop-blur-md shadow-xs z-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between items-center">
                        
                        <div class="flex items-center">
                            <span class="font-bold text-xs uppercase tracking-widest text-stone-500 hidden md:inline-block bg-stone-100 px-2.5 py-1 rounded-md">
                                Management Panel
                            </span>
                            <!-- Mobile-Only Branding Header Logo -->
                            <div class="flex flex-col shrink-0 items-start md:hidden">
                                <Link :href="route('dashboard')">
                                    <span class="font-black text-lg tracking-widest text-pink-600">KAWA LIPA</span>
                                </Link>
                            </div>
                        </div>

                        <!-- Right Actions Dropdown Menu (Desktop View) -->
                        <div class="hidden sm:flex sm:items-center">
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-xl border border-pink-100 bg-white px-3.5 py-2 text-sm font-semibold text-stone-600 transition-all duration-150 hover:bg-pink-50/50 hover:text-pink-600 focus:outline-none shadow-xs"
                                            >
                                                {{ $page.props.auth.user.name }}
                                                <svg class="-me-0.5 ms-2 h-4 w-4 text-stone-400 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')">Account Configuration</DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button" class="text-rose-500 font-bold hover:bg-rose-50">Log Out</DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger Toggle Controls Button (Mobile View Only) -->
                        <div class="-me-2 flex items-center md:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center rounded-xl p-2 text-stone-500 transition duration-150 ease-in-out hover:bg-pink-100 hover:text-pink-600 focus:outline-none"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ========================================================================= -->
                <!-- MOBILE DROPDOWN INTERFACE OVERLAY                                         -->
                <!-- ========================================================================= -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="md:hidden bg-white border-b border-pink-100 shadow-inner">
                    <div class="space-y-1 pb-3 pt-2 px-3">
                        
                        <!-- [MOBILE] LINK: DASHBOARD -->
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                        
                        <!-- [MOBILE] LINK: MENUS CATALOG (Updated Route) -->
                        <ResponsiveNavLink :href="route('admin.menus.index')" :active="route().current('admin.menus.*')">
                            Menus
                        </ResponsiveNavLink>
                        
                        <!-- [MOBILE] LINK: ANNOUNCEMENTS -->
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('announcements.*')">
                            Announcements
                        </ResponsiveNavLink>
                        
                        <!-- [MOBILE] LINK: VIBE GALLERY -->
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('gallery.*')">
                            Vibe Gallery
                        </ResponsiveNavLink>
                    </div>
                    
                    <!-- Mobile Account Configuration Footer -->
                    <div class="border-t border-pink-100 pb-2 pt-4 px-4 bg-pink-50/30">
                        <div class="px-2">
                            <div class="text-base font-bold text-stone-800">{{ $page.props.auth.user.name }}</div>
                            <div class="text-sm font-medium text-stone-500">{{ $page.props.auth.user.email }}</div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">Account Configuration</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button" class="text-rose-600 font-bold">Log Out</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Section Layout Secondary Breadcrumb Header Slot Wrapper -->
            <header class="bg-white border-b border-pink-100/50 shadow-xs" v-if="$slots.header">
                <div class="mx-auto w-full px-6 py-4 sm:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Principal Workspace Page View Render Target -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 sm:p-8 bg-white">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
/* High-End Shifting Gradient Animation */
.logo-gradient-wave {
    background: linear-gradient(270deg, #f472b6, #fb7185, #ec4899, #f43f5e);
    background-size: 800% 800%;
    animation: smoothWave 14s ease infinite;
}

@keyframes smoothWave {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
</style>