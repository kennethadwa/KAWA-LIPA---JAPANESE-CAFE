<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { Head } from '@inertiajs/vue3';

// Receive public catalog content blocks
const props = defineProps({
    menus: Array,
    categories: Array, 
    announcements: Array,
    gallery: Array
});

// Reactivity Layers: Sub-Filtering & Text Searching 
const searchQuery = ref('');
const selectedCategory = ref('All');

// DOM Section Element Targets for Scrolling
const menuSection = ref(null);
const updatesSection = ref(null);
const gallerySection = ref(null);
const aboutSection = ref(null);

// Slider References
const menuSliderRef = ref(null);
const announcementSliderRef = ref(null); 
const gallerySliderRef = ref(null);

// Background Interactive Elements Container Ref
const butterflyContainer = ref(null);
let butterflyInterval = null;

onMounted(() => {
    // Generate butterflies naturally over time
    if (butterflyContainer.value) {
        const createButterfly = () => {
            if (!butterflyContainer.value || butterflyContainer.value.children.length > 15) return;
            
            const butterfly = document.createElement('div');
            butterfly.className = 'absolute pointer-events-none z-0 bfly-element';
            
            // Random properties
            const startX = Math.random() * 100;
            const startY = Math.random() * 40 + 60; // Start mostly from bottom half
            const size = Math.random() * 8 + 6; // 6px to 14px
            const duration = Math.random() * 15 + 15; // 15s to 30s travel time
            const flutterSpeed = Math.random() * 0.3 + 0.2; // Flutter speed
            
            butterfly.style.left = `${startX}%`;
            butterfly.style.top = `${startY}%`;
            butterfly.style.width = `${size}px`;
            butterfly.style.height = `${size}px`;
            butterfly.style.setProperty('--bfly-duration', `${duration}s`);
            butterfly.style.setProperty('--bfly-flutter', `${flutterSpeed}s`);
            butterfly.style.setProperty('--bfly-drift', `${Math.random() * 100 - 50}px`);
            
            // Build visual wings structure
            butterfly.innerHTML = `
                <div class="bfly-wings">
                    <div class="bfly-wing bfly-left"></div>
                    <div class="bfly-wing bfly-right"></div>
                </div>
            `;
            
            butterflyContainer.value.appendChild(butterfly);
            
            // Remove after animation completes
            setTimeout(() => {
                if (butterfly.parentNode) {
                    butterfly.parentNode.removeChild(butterfly);
                }
            }, duration * 1000);
        };

        // Seed initial batch
        for(let i = 0; i < 6; i++) {
            setTimeout(createButterfly, i * 1200);
        }
        // Continuous spawn sequence
        butterflyInterval = setInterval(createButterfly, 4000);
    }
});

onBeforeUnmount(() => {
    if (butterflyInterval) clearInterval(butterflyInterval);
});

const scrollToSection = (elementRef) => {
    if (elementRef) {
        const offset = 100; 
        const bodyRect = document.body.getBoundingClientRect().top;
        const elementRect = elementRef.getBoundingClientRect().top;
        const elementPosition = elementRect - bodyRect;
        const offsetPosition = elementPosition - offset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
    }
};

// Slider Utilities
const slide = (elementRef, direction) => {
    if (elementRef) {
        const scrollAmount = elementRef.clientWidth * 0.85;
        elementRef.scrollBy({
            left: direction === 'left' ? -scrollAmount : scrollAmount,
            behavior: 'smooth'
        });
    }
};

// Infinite Horizontal Grid Gallery Navigation Logic
const navigateGallery = (direction) => {
    const el = gallerySliderRef.value;
    if (!el) return;

    const scrollAmount = el.clientWidth; 
    const maxScroll = el.scrollWidth - el.clientWidth;

    if (direction === 'right') {
        // If we hit or pass the end threshold, bounce cleanly back to zero index position
        if (Math.ceil(el.scrollLeft) >= maxScroll - 15) {
            el.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
            el.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    } else {
        // If back at index start boundary, snap straight to maximum outer content track width
        if (el.scrollLeft <= 15) {
            el.scrollTo({ left: maxScroll, behavior: 'smooth' });
        } else {
            el.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        }
    }
};

// Modal State Management
const selectedMenuItem = ref(null);
const isMenuModalOpen = ref(false);

const selectedGalleryItem = ref(null);
const isGalleryModalOpen = ref(false);

const openMenuModal = (item) => {
    selectedMenuItem.value = item;
    isMenuModalOpen.value = true;
};

const closeMenuModal = () => {
    isMenuModalOpen.value = false;
    setTimeout(() => { selectedMenuItem.value = null; }, 300);
};

const openGalleryModal = (item) => {
    selectedGalleryItem.value = item;
    isGalleryModalOpen.value = true;
};

const closeGalleryModal = () => {
    isGalleryModalOpen.value = false;
    setTimeout(() => { selectedGalleryItem.value = null; }, 300);
};

const getCategoryName = (item) => {
    if (item.category && typeof item.category === 'object') {
        return item.category.name;
    }
    return item.category || 'Standard';
};

// Menu Items Counter for Pill Badges
const getAllItemsCount = () => {
    return props.menus ? props.menus.length : 0;
};

const getCategoryCount = (categoryName) => {
    if (!props.menus) return 0;
    return props.menus.filter(item => {
        const itemCat = getCategoryName(item);
        return itemCat.toLowerCase() === categoryName.toLowerCase();
    }).length;
};

// Dynamic Search Computation Engine
const filteredMenus = computed(() => {
    return (props.menus || []).filter(item => {
        const matchesSearch = item.name.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
                              (item.description && item.description.toLowerCase().includes(searchQuery.value.toLowerCase()));
        
        const categoryName = getCategoryName(item);
        const matchesCategory = selectedCategory.value === 'All' || 
                                categoryName.toLowerCase() === selectedCategory.value.toLowerCase();
                                
        return matchesSearch && matchesCategory;
    });
});

const filteredAnnouncements = computed(() => {
    if (!searchQuery.value) return props.announcements || [];
    return (props.announcements || []).filter(ann => 
        ann.title.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
        ann.content.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});
</script>

<template>
    <Head title="Welcome to Kawa Lipa" />

    <div class="min-h-screen relative overflow-hidden bg-gradient-to-b from-[#fff5f6] via-[#ffeef1] to-[#ffdce2] text-rose-900/90 antialiased font-sans scroll-smooth">
        
        <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden">
            <div class="absolute inset-0 opacity-40 bg-[radial-gradient(#ffccd5_1px,transparent_1px)] [background-size:24px_24px]"></div>
            
            <div ref="butterflyContainer" class="absolute inset-0 w-full h-full overflow-hidden"></div>

            <div class="absolute inset-x-0 top-[800px] w-full h-64 opacity-20">
                <svg class="w-full h-full min-h-[150px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 24 150 28" preserveAspectRatio="none">
                    <path d="M-160 44c30 0 58-18 88-18s58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" fill="url(#bg-wave-grad-1)" class="wave-back" />
                    <defs>
                        <linearGradient id="bg-wave-grad-1" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#f43f5e" />
                            <stop offset="100%" stop-color="#fda4af" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>
            <div class="absolute inset-x-0 bottom-40 w-full h-80 opacity-25 transform rotate-180">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 24 150 28" preserveAspectRatio="none">
                    <path d="M-160 44c30 0 58-18 88-18s58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" fill="url(#bg-wave-grad-2)" class="wave-front" />
                    <defs>
                        <linearGradient id="bg-wave-grad-2" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#f43f5e" />
                            <stop offset="100%" stop-color="#ec4899" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>

            <div class="absolute top-[680px] left-[5%] opacity-15 animate-float-slow">
                <svg width="70" height="70" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="45" fill="white" stroke="#f43f5e" stroke-width="8"/>
                    <path d="M50 25C35 25 28 38 32 48C36 58 50 62 56 54C62 46 54 38 46 40C40 41 38 48 42 51C45 53 49 51 49 48" stroke="#f43f5e" stroke-width="6" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="absolute top-[1600px] right-[4%] opacity-20 animate-float-medium">
                <svg width="90" height="90" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="45" fill="white" stroke="#f43f5e" stroke-width="8"/>
                    <path d="M50 25C35 25 28 38 32 48C36 58 50 62 56 54C62 46 54 38 46 40C40 41 38 48 42 51C45 53 49 51 49 48" stroke="#f43f5e" stroke-width="6" stroke-linecap="round"/>
                </svg>
            </div>

            <div class="absolute top-[2400px] left-[3%] opacity-15 hidden lg:block animate-pulse-gentle">
                <div class="relative w-32 h-32">
                    <div class="smoke-line position-1"></div>
                    <div class="smoke-line position-2"></div>
                    <div class="smoke-line position-3"></div>
                    <svg class="absolute bottom-2 left-0" width="120" height="70" viewBox="0 0 120 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 5C10 5 15 45 60 45C105 45 110 5 110 5H10Z" fill="#f43f5e"/>
                        <path d="M40 45C40 45 42 58 60 58C78 58 80 45 80 45H40Z" fill="#e11d48"/>
                        <line x1="15" y1="12" x2="105" y2="12" stroke="white" stroke-width="4"/>
                    </svg>
                </div>
            </div>
        </div>

        <header class="w-full bg-rose-500 shadow-md sticky top-0 z-40 overflow-hidden select-none pb-5">
            <div class="px-4 sm:px-6 lg:px-12 py-4 flex flex-col md:flex-row items-center justify-between gap-4 relative z-10">
                <div class="flex items-center gap-3 shrink-0">
                    <img src="/storage/images/logo_kawa.png" alt="Kawa Lipa Logo" class="h-14 w-14 object-contain rounded-2xl p-0.5" />
                    <div class="flex flex-col">
                        <span class="text-xl font-black tracking-tight text-white drop-shadow-xs">KAWA <span class="text-pink-200">LIPA</span></span>
                        <span class="text-[9px] text-pink-100 font-bold uppercase tracking-widest drop-shadow-xs">Premium Experience Platform</span>
                    </div>
                </div>

                <nav class="flex items-center gap-1 bg-white/10 backdrop-blur-md p-1 rounded-2xl border border-white/10 text-xs font-extrabold uppercase tracking-wider text-white">
                    <button @click="scrollToSection(menuSection)" class="px-4 py-2 rounded-xl hover:bg-white/10 transition-all">Menus</button>
                    <button @click="scrollToSection(updatesSection)" class="px-4 py-2 rounded-xl hover:bg-white/10 transition-all">Updates ({{ filteredAnnouncements.length }})</button>
                    <button @click="scrollToSection(gallerySection)" class="px-4 py-2 rounded-xl hover:bg-white/10 transition-all">Gallery</button>
                    <button @click="scrollToSection(aboutSection)" class="px-4 py-2 rounded-xl hover:bg-white/10 transition-all">About Us</button>
                </nav>

                <div class="relative w-full max-w-xs transform hover:scale-[1.01] transition-all">
                    <input v-model="searchQuery" type="text" placeholder="Search menu, broadcasts..." 
                           class="w-full pl-5 pr-10 py-2 bg-white/95 border-0 rounded-xl text-rose-950 text-xs font-semibold focus:ring-4 focus:ring-pink-300/50 focus:bg-white transition-all placeholder:text-rose-300" />
                    <span class="absolute right-3 top-2.5 text-xs filter grayscale opacity-70">🔍</span>
                </div>
            </div>

            <div class="absolute bottom-0 left-0 w-full h-8 pointer-events-none overflow-hidden">
                <svg class="waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
                    <defs>
                        <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
                    </defs>
                    <g class="parallax">
                        <use href="#gentle-wave" x="48" y="0" fill="rgba(255, 228, 230, 0.25)" class="wave-back" />
                        <use href="#gentle-wave" x="48" y="5" fill="rgba(255, 228, 230, 0.45)" class="wave-front" />
                    </g>
                </svg>
            </div>
        </header>

        <div class="w-full h-[580px] relative flex items-center justify-center overflow-hidden border-b border-pink-100 bg-transparent">
            <img src="/storage/wallpaper/kawa_wallpaper.png" alt="Kawa Lipa Wallpaper" class="absolute inset-0 w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-t from-[#fff5f6] via-transparent to-black/10 z-1"></div>
            <div class="relative z-10 text-center max-w-2xl mx-auto px-4 drop-shadow-md">
                <h1 class="text-4xl font-black text-white tracking-tight sm:text-6xl uppercase bg-rose-950/70 backdrop-blur-xs px-6 py-3 rounded-3xl inline-block border border-white/20">Discover Our Showcase</h1>
                <p class="mt-4 text-sm sm:text-base text-white font-extrabold tracking-wide bg-rose-950/70 backdrop-blur-xs px-4 py-2 rounded-xl inline-block">Where Japanese inspired flavors meet modern comfort.</p>
            </div>
        </div>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-24 relative z-10">

            
<section ref="menuSection" class="scroll-mt-6 group/slider relative">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-black uppercase text-rose-950 tracking-tight">KAWA LIPA MENU</h2>
        <p class="text-xs text-rose-400 font-bold mt-1">Fresh, authentic Japanese dishes crafted daily.</p>
    </div>

    <div class="space-y-8">
        <div class="flex flex-wrap items-center justify-center gap-2">
            <button @click="selectedCategory = 'All'" :class="[selectedCategory === 'All' ? 'bg-pink-400 text-white' : 'bg-white text-rose-500 hover:bg-rose-50/50 border border-pink-100/80', 'px-4 py-1.5 rounded-xl text-xs font-extrabold shadow-xs transition-all flex items-center gap-1.5']">
                <span>All Categories</span>
                <span :class="[selectedCategory === 'All' ? 'bg-white text-pink-500' : 'bg-rose-50 text-rose-500', 'px-1.5 py-0.5 rounded-md text-[10px] font-black']">
                    {{ getAllItemsCount() }}
                </span>
            </button>
            
            <button v-for="cat in categories" :key="cat" @click="selectedCategory = cat" :class="[selectedCategory === cat ? 'bg-pink-400 text-white' : 'bg-white text-rose-500 hover:bg-rose-50/50 border border-pink-100/80', 'px-4 py-1.5 rounded-xl text-xs font-extrabold shadow-xs transition-all capitalize flex items-center gap-1.5']">
                <span>{{ cat }}</span>
                <span :class="[selectedCategory === cat ? 'bg-white text-pink-500' : 'bg-rose-50 text-rose-500', 'px-1.5 py-0.5 rounded-md text-[10px] font-black']">
                    {{ getCategoryCount(cat) }}
                </span>
            </button>
        </div>

        <div class="relative px-4">
            <template v-if="filteredMenus.length > 0">
                <button @click="slide(menuSliderRef, 'left')" 
                        :class="[filteredMenus.length > 6 ? 'flex' : 'flex lg:hidden']"
                        class="absolute left-0 top-1/2 -translate-y-1/2 z-20 bg-rose-500 hover:bg-rose-600 text-white w-10 h-10 rounded-full items-center justify-center shadow-md font-bold transition-all active:scale-90 select-none">‹</button>
                <button @click="slide(menuSliderRef, 'right')" 
                        :class="[filteredMenus.length > 6 ? 'flex' : 'flex lg:hidden']"
                        class="absolute right-0 top-1/2 -translate-y-1/2 z-20 bg-rose-500 hover:bg-rose-600 text-white w-10 h-10 rounded-full items-center justify-center shadow-md font-bold transition-all active:scale-90 select-none">›</button>
            </template>

            <div ref="menuSliderRef" class="overflow-x-auto custom-pink-scrollbar snap-x snap-mandatory py-2 pb-5 scroll-smooth">
                <template v-if="filteredMenus.length > 0">
                    <div :class="[
                        filteredMenus.length <= 6 
                            ? 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6' 
                            : 'grid grid-flow-col grid-rows-2 gap-6 auto-cols-[calc(100%-12px)] sm:auto-cols-[calc(50%-12px)] lg:auto-cols-[calc(33.333%-16px)]'
                    ]">
                        <div v-for="item in filteredMenus" :key="item.id" @click="openMenuModal(item)"
                             class="w-full shrink-0 snap-start bg-white/70 backdrop-blur-md rounded-3xl border border-pink-100 shadow-xs overflow-hidden flex flex-col hover:shadow-lg hover:border-pink-300 hover:-translate-y-0.5 cursor-pointer transition-all duration-300 active:scale-95 select-none">
                            
                            <div class="w-full h-48 bg-rose-100/40 overflow-hidden relative border-b border-pink-100/60">
                                <img v-if="item.image_path" :src="item.image_path.startsWith('http') ? item.image_path : `/storage/${item.image_path}`" :alt="item.name" class="w-full h-full object-cover transition duration-500 hover:scale-105" />
                                <div v-else class="w-full h-full flex flex-col items-center justify-center text-rose-300 gap-1.5">
                                    <span>🍱</span>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-rose-400/60">No Image Uploaded</span>
                                </div>
                            </div>

                            <div class="p-6 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-3">
                                        <span class="text-[10px] font-extrabold uppercase bg-white border border-pink-200 text-pink-500 px-2.5 py-0.5 rounded-lg tracking-wider">
                                            {{ getCategoryName(item) }}
                                        </span>
                                        <span class="text-base font-black text-rose-500">₱{{ item.price || '0.00' }}</span>
                                    </div>
                                    <h3 class="text-base font-bold text-rose-950 tracking-tight mb-1.5">{{ item.name }}</h3>
                                    <p class="text-xs text-rose-700/60 line-clamp-2 font-medium leading-relaxed">{{ item.description || 'No description assigned yet.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <div v-if="filteredMenus.length === 0" class="text-center py-12 text-rose-300 font-medium text-xs">No matching items found.</div>
            </div>
        </div>
    </div>
</section>

  <section ref="updatesSection" class="scroll-mt-6 border-t border-pink-200/40 pt-16">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-black uppercase text-rose-950 tracking-tight">Platform Broadcasts</h2>
        <p class="text-xs text-rose-400 font-bold mt-1">Live announcements, schedules, and event updates.</p>
    </div>

    <div class="relative px-4 max-w-6xl mx-auto">
        <template v-if="filteredAnnouncements.length > 0">
            <button @click="slide(announcementSliderRef, 'left')" 
                    class="absolute -left-2 top-1/2 -translate-y-1/2 z-20 bg-rose-500 hover:bg-rose-600 border border-rose-600 text-white w-10 h-10 rounded-full flex items-center justify-center shadow-md font-bold transition-all active:scale-90 select-none">‹</button>
            <button @click="slide(announcementSliderRef, 'right')" 
                    class="absolute -right-2 top-1/2 -translate-y-1/2 z-20 bg-rose-500 hover:bg-rose-600 border border-rose-600 text-white w-10 h-10 rounded-full flex items-center justify-center shadow-md font-bold transition-all active:scale-90 select-none">›</button>
        </template>

        <div ref="announcementSliderRef" class="flex gap-6 overflow-x-auto custom-pink-scrollbar snap-x snap-mandatory py-2 pb-6 scroll-smooth">
            <div v-for="ann in filteredAnnouncements" :key="ann.id" 
                 class="w-full sm:w-[calc(50%-12px)] shrink-0 snap-start bg-white p-7 rounded-3xl shadow-xs border border-neutral-200/80 text-black flex flex-col justify-between transition-all duration-300 hover:shadow-md hover:border-pink-300 hover:-translate-y-0.5">
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-neutral-100 pb-3">
                        <span class="text-[10px] text-pink-600 font-black uppercase tracking-widest bg-white border border-pink-100 px-3 py-1 rounded-xl shadow-3xs">
                            Broadcast Post
                        </span>
                        <div class="flex items-center gap-1.5">
                            <img src="/storage/images/logo_kawa.png" alt="KAWA LIPA Logo" class="w-7 h-7 rounded-lg object-cover shadow-3xs" />
                            <span class="text-[10px] text-neutral-500 font-bold uppercase tracking-wider">KAWA LIPA</span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-black text-black tracking-tight line-clamp-1">{{ ann.title }}</h3>
                        <p class="text-xs text-neutral-700 line-clamp-4 font-medium leading-relaxed whitespace-pre-line">{{ ann.content }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="filteredAnnouncements.length === 0" class="text-center py-12 text-rose-300 font-medium text-xs">No recent announcements found.</div>
    </div>
</section>

<section ref="gallerySection" class="scroll-mt-6 border-t border-pink-200/40 pt-16">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-black uppercase text-rose-950 tracking-tight">Our Gallery Showcase</h2>
        <p class="text-xs text-rose-400 font-bold mt-1">Glimpses of premium vibes and traditional moments.</p>
    </div>

    <div class="relative px-12">
        <template v-if="gallery && gallery.length > 4">
            <button @click="navigateGallery('left')" class="absolute left-0 top-1/2 -translate-y-1/2 z-20 bg-rose-500 hover:bg-rose-600 text-white w-10 h-10 rounded-full flex items-center justify-center shadow-md font-bold transition-all active:scale-90 select-none">‹</button>
            <button @click="navigateGallery('right')" class="absolute right-0 top-1/2 -translate-y-1/2 z-20 bg-rose-500 hover:bg-rose-600 text-white w-10 h-10 rounded-full flex items-center justify-center shadow-md font-bold transition-all active:scale-90 select-none">›</button>
        </template>

        <div ref="gallerySliderRef" class="overflow-x-auto py-3 snap-x snap-mandatory custom-pink-scrollbar scroll-smooth">
            <template v-if="gallery && gallery.length > 0">
                <div class="grid grid-flow-col grid-rows-2 gap-4 auto-cols-[calc(50%-8px)] sm:auto-cols-[calc(25%-12px)]">
                    <div v-for="media in gallery" :key="media.id" @click="openGalleryModal(media)"
                         class="w-full shrink-0 aspect-square bg-white rounded-2xl overflow-hidden relative border border-pink-100 shadow-xs group cursor-pointer transition-all active:scale-95 select-none snap-start">
                        
                        <img v-if="media.media_type === 'image'" :src="`/storage/${media.file_path}`" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" :alt="media.title || 'Showcase asset'" />
                        
                        <div v-else class="w-full h-full relative bg-rose-950/20 flex items-center justify-center overflow-hidden">
                            <img v-if="media.thumbnail_path" :src="`/storage/${media.thumbnail_path}`" class="absolute inset-0 w-full h-full object-cover opacity-90 group-hover:scale-105 transition duration-500" />
                            <video v-else-if="media.file_path" :src="`/storage/${media.file_path}#t=0.1`" preload="metadata" class="absolute inset-0 w-full h-full object-cover opacity-80 pointer-events-none group-hover:scale-105 transition duration-500" muted playsinline></video>
                            <div v-else class="absolute inset-0 bg-rose-200/50 flex flex-col items-center justify-center text-rose-400">
                                <span class="text-xl">🎬</span>
                            </div>
                            <div class="z-10 w-12 h-12 bg-black/60 backdrop-blur-xs rounded-full flex items-center justify-center border border-white/20 group-hover:scale-110 transition-transform text-white text-sm shadow-md">▶</div>
                        </div>
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-pink-950/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300 p-4 flex items-end">
                            <p class="text-[10px] font-bold text-white uppercase tracking-wider">{{ media.title || 'View Asset' }}</p>
                        </div>
                    </div>
                </div>
            </template>
            <div v-else class="text-center w-full py-12 text-rose-300 font-medium text-xs">The gallery portfolio is currently empty.</div>
        </div>
    </div>
</section>

            <section ref="aboutSection" class="scroll-mt-6 border-t border-pink-200/40 pt-16 mb-10 space-y-14">
                <div class="text-center max-w-3xl mx-auto">
                    <span class="text-[11px] font-black tracking-widest text-pink-500 bg-pink-50 border border-pink-100 px-3 py-1 rounded-full uppercase">Japanese Restaurant</span>
                    <h2 class="text-3xl sm:text-5xl font-black text-rose-950 tracking-tight mt-4 leading-tight">
                        "A Taste of Japan, Right in the Heart of Lipa City."
                    </h2>
                    <p class="mt-4 text-base text-rose-700/70 font-medium max-w-xl mx-auto leading-relaxed">
                        Bringing people together over authentic flavors, cozy spaces, and local hospitality.
                    </p>
                </div>

                <div class="bg-white/80 rounded-3xl border border-pink-100 p-8 md:p-12 shadow-xs backdrop-blur-xs relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 text-9xl font-black text-pink-100/30 select-none font-serif">川</div>
                    <div class="max-w-3xl">
                        <h3 class="text-xs font-extrabold uppercase tracking-widest text-pink-500 mb-3 flex items-center gap-2">
                            <span>🌸</span> Our Story
                        </h3>
                        <h4 class="text-xl font-bold text-rose-950 tracking-tight mb-4">The Brand Identity</h4>
                        <div class="space-y-4 text-sm text-rose-900/80 leading-relaxed font-medium">
                            <p>
                                <strong class="text-rose-950 font-bold">KAWA Lipa</strong> was born out of a deep passion for Japanese culinary culture and a desire to bring a unique, aesthetic dining experience to the community of Lipa. The word <span class="bg-pink-50 text-pink-600 px-1.5 py-0.5 rounded font-bold">"Kawa" (川)</span> means river in Japanese—symbolizing a continuous flow of good food, warm conversations, and unforgettable memories shared between our customers, our crews, and our community.
                            </p>
                            <p>
                                Whether you are looking for a quiet corner to enjoy a premium brew, a cozy spot to study, or a place to share a hearty bowl of ramen with family and friends, KAWA Lipa offers a sanctuary where modern cafe aesthetics meet traditional Japanese comfort.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="text-center">
                        <span class="text-xs font-extrabold uppercase tracking-widest text-pink-500">Core Values</span>
                        <p class="text-lg font-bold text-rose-950 tracking-tight">What Makes KAWA Special</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white/60 border border-pink-100/60 rounded-2xl p-6 shadow-2xs hover:border-pink-200 transition-all text-center space-y-3 backdrop-blur-xs">
                            <div class="text-3xl">🍜</div>
                            <h4 class="text-sm font-black text-rose-950 uppercase tracking-wider">Authentic Comfort</h4>
                            <p class="text-xs text-rose-700/70 leading-relaxed font-medium">We meticulously prepare our dishes—from savory ramen broths to crispy katsu—to ensure every bite feels like a warm embrace.</p>
                        </div>
                        <div class="bg-white/60 border border-pink-100/60 rounded-2xl p-6 shadow-2xs hover:border-pink-200 transition-all text-center space-y-3 backdrop-blur-xs">
                            <div class="text-3xl">☕</div>
                            <h4 class="text-sm font-black text-rose-950 uppercase tracking-wider">Premium Quality</h4>
                            <p class="text-xs text-rose-700/70 leading-relaxed font-medium">From our hand-picked coffee beans to our high-grade matcha, we never compromise on the quality of our ingredients.</p>
                        </div>
                        <div class="bg-white/60 border border-pink-100/60 rounded-2xl p-6 shadow-2xs hover:border-pink-200 transition-all text-center space-y-3 backdrop-blur-xs">
                            <div class="text-3xl">👥</div>
                            <h4 class="text-sm font-black text-rose-950 uppercase tracking-wider">Local Community</h4>
                            <p class="text-xs text-rose-700/70 leading-relaxed font-medium">More than just a restaurant, we are a space for local foodies, students, and families to connect and feel at home.</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="mt-24 border-t border-rose-200/60 bg-rose-500/70 pt-16 pb-8 text-white">
    <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-10 pb-12 border-b border-rose-200/60">
        
        <!-- Column 1: Brand & Reviews -->
        <div class="space-y-4">
            <div class="flex items-center gap-2">
                <img src="/storage/images/logo_kawa.png" alt="KAWA LIPA Logo" class="w-12 h-12 rounded-lg object-cover shadow-xs" />
                <span class="text-base font-black text-white tracking-wider">KAWA LIPA</span>
            </div>
            <p class="text-xs text-white font-medium leading-relaxed">
                Experience premium vibes and unforgettable traditional moments right in the heart of Lipa City.
            </p>
            <!-- Review Badge -->
            <div class="inline-flex items-center gap-2 bg-white border border-rose-200/80 px-3 py-2 rounded-xl shadow-3xs">
                <span class="text-sm">🌟</span>
                <div class="text-left">
                    <p class="text-[11px] font-black text-black uppercase tracking-wider leading-none">100% Recommended</p>
                    <p class="text-[10px] text-black font-bold mt-0.5">(16 Public Reviews)</p>
                </div>
            </div>
        </div>

        <!-- Column 2: Exact Location Coordinates -->
        <div class="space-y-3">
            <h4 class="text-xs font-black uppercase text-whitetracking-widest">Find Us</h4>
            <div class="flex items-start gap-2 text-xs text-white leading-relaxed font-medium">
                <span class="text-sm mt-0.5">📍</span>
                <p>
                    <span class="font-bold text-white block mb-0.5">W5X6+99C</span>
                    Beside Dali's Store, TM Kalaw St,<br>
                    Balintawak, Lipa City, 4217 Batangas,<br>
                    Philippines
                </p>
            </div>
        </div>

        <!-- Column 3: Direct Connect & Contact Info -->
        <div class="space-y-3">
            <h4 class="text-xs font-black uppercase text-white  tracking-widest">Get In Touch</h4>
            <ul class="space-y-2.5 text-xs text-white font-medium">
                <li class="flex items-center gap-2">
                    <span class="text-sm ">📞</span>
                    <a href="tel:09778209336" class="hover:text-pink-600 transition-colors">0977 820 9336</a>
                </li>
                <li class="flex items-center gap-2">
                    <span class="text-sm">🌐</span>
                    <a href="https://www.facebook.com/search/top?q=KAWA%20Lipa" target="_blank" rel="noopener noreferrer" class="hover:text-pink-600 transition-colors font-bold text-white flex items-center gap-1">
                        KAWA Lipa <span class="text-[10px] text-rose-400 font-normal">→</span>
                    </a>
                </li>
            </ul>
        </div>

    </div>

    <!-- Bottom Copyright Note -->
    <div class="max-w-6xl mx-auto px-6 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-[10px] text-white font-bold uppercase tracking-wider">
        <p class="text-white">© 2026 KAWA LIPA. All Rights Reserved.</p>
        <p class="text-white">Premium Experiences & Gatherings</p>
    </div>
</footer>


    </div>

    <div v-if="isMenuModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-rose-950/40 backdrop-blur-sm transition-opacity duration-300" @click.self="closeMenuModal">
        <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-pink-100 transform transition-all duration-300 scale-100 animate-scale-up flex flex-col">
            <div class="w-full h-64 bg-rose-50 relative">
                <img v-if="selectedMenuItem?.image_path" :src="selectedMenuItem.image_path.startsWith('http') ? selectedMenuItem.image_path : `/storage/${selectedMenuItem.image_path}`" :alt="selectedMenuItem?.name" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full flex flex-col items-center justify-center text-rose-300 gap-1">
                    <span class="text-4xl">🍱</span>
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-400/50">No Image Preview Available</span>
                </div>
                <button @click="closeMenuModal" class="absolute top-4 right-4 bg-rose-950/60 hover:bg-rose-950/80 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition shadow-md select-none">✕</button>
            </div>

            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-xs font-black uppercase bg-pink-50 border border-pink-100 text-pink-500 px-3 py-1 rounded-xl tracking-wider">
                        {{ getCategoryName(selectedMenuItem) }}
                    </span>
                    <span class="text-xl font-black text-rose-500">₱{{ selectedMenuItem?.price || '0.00' }}</span>
                </div>
                <div>
                    <h2 class="text-xl font-black text-rose-950 tracking-tight mb-2">{{ selectedMenuItem?.name }}</h2>
                    <p class="text-sm text-rose-800/80 leading-relaxed font-medium whitespace-pre-line">{{ selectedMenuItem?.description || 'This premium item has no full description details assigned yet.' }}</p>
                </div>
                <div class="pt-2">
                    <button @click="closeMenuModal" class="w-full py-3 bg-gradient-to-r from-pink-400 to-rose-400 hover:from-pink-500 hover:to-rose-500 text-white font-extrabold text-xs uppercase tracking-wider rounded-2xl shadow-sm transition-all transform active:scale-98">Back to Catalog</button>
                </div>
            </div>
        </div>
    </div>

    <div v-if="isGalleryModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-md transition-opacity duration-300" @click.self="closeGalleryModal">
        <div class="relative max-w-4xl w-full h-full max-h-[80vh] flex flex-col justify-center items-center group/theater animate-scale-up">
            <div class="absolute top-0 left-0 w-full p-4 flex items-center justify-between bg-gradient-to-b from-black/60 to-transparent text-white z-30 opacity-100 sm:opacity-0 group/theater:opacity-100 transition-opacity">
                <span class="text-xs font-extrabold uppercase tracking-wide bg-white/10 px-3 py-1.5 rounded-lg border border-white/10">{{ selectedGalleryItem?.title || 'Asset View' }}</span>
                <button @click="closeGalleryModal" class="bg-white/10 hover:bg-white/20 w-10 h-10 rounded-full flex items-center justify-center font-bold text-base transition border border-white/20 shadow-md">✕</button>
            </div>

            <div class="w-full h-full flex items-center justify-center p-4">
                <img v-if="selectedGalleryItem?.media_type === 'image'" :src="`/storage/${selectedGalleryItem.file_path}`" class="max-w-full max-h-full object-contain rounded-xl shadow-2xl" />
                <video v-else-if="selectedGalleryItem?.media_type === 'video'" :src="`/storage/${selectedGalleryItem.file_path}`" class="max-w-full max-h-full rounded-xl shadow-2xl" controls autoplay></video>
            </div>
        </div>
    </div>
</template>

<style>
/* Custom Layout Scrollbars Elements */
.custom-scrollbar::-webkit-scrollbar {
    height: 5px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(244, 63, 94, 0.05);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(244, 63, 94, 0.3);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(244, 63, 94, 0.5);
}
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: rgba(244, 63, 94, 0.3) rgba(244, 63, 94, 0.05);
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* Header Wave System Keyframes */
.waves {
    position: relative;
    width: 100%;
    height: 100%;
    min-height: 20px;
    max-height: 30px;
}
.wave-back {
    animation: moveWaveLeft 22s cubic-bezier(0.55, 0.5, 0.45, 0.5) infinite;
}
.wave-front {
    animation: moveWaveRight 14s cubic-bezier(0.55, 0.5, 0.45, 0.5) infinite;
}

@keyframes moveWaveLeft {
    0% { transform: translate3d(-90px, 0, 0); }
    100% { transform: translate3d(85px, 0, 0); }
}
@keyframes moveWaveRight {
    0% { transform: translate3d(85px, 0, 0); }
    100% { transform: translate3d(-90px, 0, 0); }
}

/* Component Modals Pop Effects */
@keyframes scaleUp {
    from { transform: scale(0.95); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
.animate-scale-up {
    animation: scaleUp 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

/* Floating Elements Keyframes Engine */
@keyframes floatSlow {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-25px) rotate(8deg); }
}
@keyframes floatMedium {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-18px) rotate(-12deg); }
}
@keyframes pulseGentle {
    0%, 100% { transform: scale(1); opacity: 0.15; }
    50% { transform: scale(1.03); opacity: 0.22; }
}
.animate-float-slow { animation: floatSlow 8s ease-in-out infinite; }
.animate-float-medium { animation: floatMedium 6s ease-in-out infinite; }
.animate-pulse-gentle { animation: pulseGentle 4s ease-in-out infinite; }

/* Vector Streaming Ramen Smoke Trails */
.smoke-line {
    position: absolute;
    width: 4px;
    height: 25px;
    background: linear-gradient(to top, rgba(244,63,94,0.4), transparent);
    border-radius: 50%;
    opacity: 0;
    animation: riseSmoke 3s ease-in-out infinite;
}
.smoke-line.position-1 { left: 35px; bottom: 65px; animation-delay: 0s; }
.smoke-line.position-2 { left: 55px; bottom: 65px; animation-delay: 0.8s; }
.smoke-line.position-3 { left: 75px; bottom: 65px; animation-delay: 0.4s; }

@keyframes riseSmoke {
    0% { transform: translateY(0) scaleX(1); opacity: 0; }
    15% { opacity: 0.7; }
    50% { transform: translateY(-20px) scaleX(1.5); opacity: 0.4; }
    100% { transform: translateY(-45px) scaleX(0.5); opacity: 0; }
}

/* Performance Optimized CSS/JS Butterfly Elements */
.bfly-element {
    animation: bflyFly var(--bfly-duration) linear infinite;
}
.bfly-wings {
    display: flex;
    animation: bflyFlutter var(--bfly-flutter) ease-in-out infinite alternate;
    transform-style: preserve-3d;
}
.bfly-wing {
    width: 50%;
    height: 100%;
    background: radial-gradient(circle, #f43f5e 20%, #fda4af 80%);
    border-radius: 50% 50% 10% 40%;
}
.bfly-wing.bfly-left {
    transform-origin: right center;
}
.bfly-wing.bfly-right {
    transform-origin: left center;
    transform: scaleX(-1);
}

@keyframes bflyFly {
    0% {
        transform: translateY(0) translateX(0);
        opacity: 0;
    }
    10% { opacity: 0.6; }
    90% { opacity: 0.6; }
    100% {
        transform: translateY(-800px) translateX(var(--bfly-drift));
        opacity: 0;
    }
}
@keyframes bflyFlutter {
    0% { transform: rotateY(-65deg); }
    100% { transform: rotateY(65deg); }
}

/* Webkit Browsers (Chrome, Safari, Edge) */
.custom-pink-scrollbar::-webkit-scrollbar {
    height: 8px !important; /* Forces scrollbar thickness visible */
    display: block !important;
}

.custom-pink-scrollbar::-webkit-scrollbar-track {
    background: #fce7f3 !important; /* light pink background */
    border-radius: 9999px;
}

.custom-pink-scrollbar::-webkit-scrollbar-thumb {
    background: #ec4899 !important; /* pink-500 handle bar */
    border-radius: 9999px;
}

.custom-pink-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #db2777 !important; /* darker pink on drag hover */
}

/* Firefox Engine Fallback */
.custom-pink-scrollbar {
    scrollbar-width: thin !important;
    scrollbar-color: #ec4899 #fce7f3 !important;
}
</style>