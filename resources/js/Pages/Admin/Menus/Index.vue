<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const props = defineProps({
    menuItems: Array,
    categories: Array // Passed down automatically from MenuController@index
});

/* --- Filtering States --- */
const categorySearchQuery = ref('');
const menuSearchQuery = ref('');
const selectedCategoryFilter = ref('all');

/* --- Pagination States --- */
const categoryCurrentPage = ref(1);
const categoryPerPage = 5;

const menuCurrentPage = ref(1);
const menuPerPage = 10;

/* --- Computed Filtered Arrays --- */
// Real-time character evaluation for the Categories Table
const filteredCategories = computed(() => {
    if (!props.categories) return [];
    const query = categorySearchQuery.value.toLowerCase().trim();
    return props.categories.filter(cat => 
        cat.name.toLowerCase().includes(query) || 
        cat.slug.toLowerCase().includes(query)
    );
});

// Sliced Categories for Paginated Output
const paginatedCategories = computed(() => {
    const start = (categoryCurrentPage.value - 1) * categoryPerPage;
    return filteredCategories.value.slice(start, start + categoryPerPage);
});

const totalCategoryPages = computed(() => {
    return Math.ceil(filteredCategories.value.length / categoryPerPage) || 1;
});

// Double-layered filter (Search Input + Dropdown Switch) for the Menu Items Table
const filteredMenuItems = computed(() => {
    if (!props.menuItems) return [];
    
    let items = props.menuItems;
    
    // 1. Apply category dropdown matrix filter
    if (selectedCategoryFilter.value !== 'all') {
        items = items.filter(item => item.category === selectedCategoryFilter.value);
    }
    
    // 2. Apply real-time character matching lookup matrix
    const query = menuSearchQuery.value.toLowerCase().trim();
    if (query) {
        items = items.filter(item => 
            item.name.toLowerCase().includes(query) || 
            (item.description && item.description.toLowerCase().includes(query))
        );
    }
    
    return items;
});

// Sliced Menu Items for Paginated Output
const paginatedMenuItems = computed(() => {
    const start = (menuCurrentPage.value - 1) * menuPerPage;
    return filteredMenuItems.value.slice(start, start + menuPerPage);
});

const totalMenuPages = computed(() => {
    return Math.ceil(filteredMenuItems.value.length / menuPerPage) || 1;
});

/* --- Watchers to Reset Pages on Filter --- */
watch(categorySearchQuery, () => {
    categoryCurrentPage.value = 1;
});

watch([menuSearchQuery, selectedCategoryFilter], () => {
    menuCurrentPage.value = 1;
});

/**
 * Handle direct ledger deletion prompts with a quick verification alert.
 */
const deleteItem = (id, name) => {
    if (confirm(`Are you sure you want to permanently remove "${name}" from the menu catalog?`)) {
        router.delete(route('admin.menus.destroy', id));
    }
};

/* --- Category Management Systems Actions --- */
const handleDeleteCategory = (catName) => {
    if (confirm(`Warning: Deleting the category string "${catName}" will permanently purge all menu items assigned under it. Proceed?`)) {
        router.delete(route('admin.categories.destroy', catName));
    }
};
</script>

<template>
    <Head title="Manage Menus" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 border-b border-stone-200/60 pb-6 mb-8">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-stone-800 sm:text-3xl">Menu Catalog</h2>
                    <p class="text-xs text-stone-500 font-medium mt-1.5">
                        Configure espresso items, pastries, and seasonal culinary offerings.
                    </p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <Link 
                        :href="route('admin.categories.create')"
                        class="inline-flex items-center justify-center gap-2 bg-rose-500 hover:bg-rose-600 text-white font-bold text-xs uppercase tracking-wider px-5 py-3.5 rounded-xl transition-all shadow-sm active:scale-[0.98] whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-rose-500/20"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Category
                    </Link>

                    <Link 
                        :href="route('admin.menus.create')"
                        class="inline-flex items-center justify-center gap-2 bg-rose-500 hover:bg-rose-600 text-white font-bold text-xs uppercase tracking-wider px-5 py-3.5 rounded-xl transition-all shadow-sm active:scale-[0.98] shrink-0 focus:outline-none focus:ring-2 focus:ring-rose-500/20"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Menu Item
                    </Link>
                </div>
            </div>

            <div class="bg-[#FFF0F2] rounded-2xl border border-stone-200/80 shadow-xs overflow-hidden mb-8 transition-all">
                <div class="p-6 border-b border-stone-100 bg-[#FFE1E6]/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <h3 class="text-xs font-black text-stone-500 uppercase tracking-widest">
                        Category Master Groups ({{ categories?.length || 0 }})
                    </h3>
                    
                    <div class="relative max-w-xs w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-stone-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input 
                            v-model="categorySearchQuery"
                            type="text" 
                            placeholder="Quick-search category..." 
                            class="w-full bg-white border border-stone-200 rounded-xl pl-9 pr-3 py-1.5 text-[11px] font-medium placeholder-stone-400 text-stone-700 shadow-2xs focus:outline-none focus:border-stone-400 focus:ring-2 focus:ring-stone-400/10 transition-all"
                        />
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-stone-200/60 text-[11px] uppercase tracking-wider font-black text-stone-500 bg-[#FFE1E6]/40">
                                <th class="p-4 pl-6">Active Database Tag Label</th>
                                <th class="p-4">Reference Key Structure</th>
                                <th class="p-4 text-center">Linked Products</th>
                                <th class="p-4 pr-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-xs text-stone-700 font-medium">
                            <tr v-for="cat in paginatedCategories" :key="cat.name" class="hover:bg-[#FFE1E6]/30 transition-colors">
                                <td class="p-4 pl-6 font-bold text-stone-800">
                                    <span class="capitalize flex items-center gap-2 text-sm tracking-tight">
                                        <span v-if="cat.name === 'coffee'">☕</span>
                                        <span v-else-if="cat.name === 'non-coffee'">🍓</span>
                                        <span v-else-if="cat.name === 'pastry'">🥐</span>
                                        <span v-else-if="cat.name === 'meals'">🍽️</span>
                                        <span v-else >🏷️</span>
                                        {{ cat.name }}
                                    </span>
                                </td>
                                <td class="p-4 font-mono text-[11px] text-stone-500">
                                    {{ cat.slug }}
                                </td>
                                <td class="p-4 text-center font-black text-stone-500">
                                    {{ cat.count }} items
                                </td>
                                <td class="p-4 pr-6 text-right space-x-3 whitespace-nowrap">
                                    <Link 
                                        :href="route('admin.categories.edit', cat.id || cat.slug || cat.name)"
                                        class="text-blue-500 hover:text-blue-700 font-bold transition-colors inline-block"
                                    >
                                        Edit
                                    </Link>
                                    <button 
                                        @click="handleDeleteCategory(cat.name)"
                                        class="text-red-500 hover:text-red-700 font-bold transition-colors"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            
                            <tr v-if="filteredCategories.length === 0">
                                <td colspan="4" class="p-6 text-center italic text-stone-400 font-medium">
                                    {{ categories && categories.length > 0 ? 'No matching category tags found matching your active filter query.' : 'No tracking strings found inside DB records yet.' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="filteredCategories.length > 0" class="px-6 py-4 border-t border-stone-100 flex items-center justify-between bg-[#FFE1E6]/20 text-[11px] font-bold text-stone-500">
                    <div>
                        Showing {{ (categoryCurrentPage - 1) * categoryPerPage + 1 }} to {{ Math.min(categoryCurrentPage * categoryPerPage, filteredCategories.length) }} of {{ filteredCategories.length }} Categories
                    </div>
                    <div class="flex items-center gap-2">
                        <button 
                            @click="categoryCurrentPage--" 
                            :disabled="categoryCurrentPage === 1"
                            class="px-3 py-1.5 rounded-lg border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 disabled:opacity-40 disabled:hover:bg-white disabled:cursor-not-allowed transition-all"
                        >
                            Previous
                        </button>
                        <button 
                            @click="categoryCurrentPage++" 
                            :disabled="categoryCurrentPage === totalCategoryPages"
                            class="px-3 py-1.5 rounded-lg border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 disabled:opacity-40 disabled:hover:bg-white disabled:cursor-not-allowed transition-all"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-[#FFF0F2] rounded-2xl border border-stone-200/80 shadow-xs overflow-hidden transition-all">
                <div class="p-6 border-b border-stone-100 flex flex-col md:flex-row md:items-center justify-between bg-[#FFE1E6]/50 gap-4">
                    <h3 class="text-xs font-black text-stone-500 uppercase tracking-widest">
                        Active Master Ledger ({{ filteredMenuItems.length }})
                    </h3>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                        <select 
                            v-model="selectedCategoryFilter"
                            class="bg-white border border-stone-200 rounded-xl px-3 py-1.5 text-[11px] font-bold text-stone-600 shadow-2xs focus:outline-none focus:border-stone-400 focus:ring-2 focus:ring-stone-400/10 transition-all capitalize w-full sm:w-40"
                        >
                            <option value="all">All Categories</option>
                            <option v-for="cat in categories" :key="'filter-' + cat.slug" :value="cat.name">
                                {{ cat.name }}
                            </option>
                        </select>

                        <div class="relative w-full sm:w-64">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-stone-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input 
                                v-model="menuSearchQuery"
                                type="text" 
                                placeholder="Search by name or description..." 
                                class="w-full bg-white border border-stone-200 rounded-xl pl-9 pr-3 py-1.5 text-[11px] font-medium placeholder-stone-400 text-stone-700 shadow-2xs focus:outline-none focus:border-stone-400 focus:ring-2 focus:ring-stone-400/10 transition-all"
                            />
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-stone-200/60 text-[11px] uppercase tracking-wider font-black text-stone-500 bg-[#FFE1E6]/40">
                                <th class="p-4 pl-6">Product Details</th>
                                <th class="p-4">Category</th>
                                <th class="p-4">Price</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 pr-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-xs text-stone-700 font-medium">
                            <tr v-for="item in paginatedMenuItems" :key="item.id" class="hover:bg-[#FFE1E6]/30 transition-colors">
                                <td class="p-4 pl-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-stone-100 flex-shrink-0 overflow-hidden border border-stone-200/60 shadow-inner">
                                            <img 
                                                v-if="item.image_url" 
                                                :src="item.image_url" 
                                                :alt="item.name" 
                                                class="w-full h-full object-cover"
                                            />
                                            <div v-else class="w-full h-full flex items-center justify-center text-stone-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-bold text-stone-800 text-sm tracking-tight">{{ item.name }}</div>
                                            <div class="text-[11px] text-stone-500 max-w-xs sm:max-w-md mt-0.5 truncate font-normal">
                                                {{ item.description || 'No descriptive structural notes assigned.' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="p-4 text-[11px] tracking-wide font-bold text-stone-600">
                                    <span v-if="item.category === 'coffee'" class="inline-flex items-center gap-1.5">☕ Coffee</span>
                                    <span v-else-if="item.category === 'non-coffee'" class="inline-flex items-center gap-1.5">🍓 Non-Coffee</span>
                                    <span v-else-if="item.category === 'pastry'" class="inline-flex items-center gap-1.5">🥐 Pastry</span>
                                    <span v-else-if="item.category === 'meals'" class="inline-flex items-center gap-1.5">🍽️ Meals</span>
                                    <span v-else class="capitalize inline-flex items-center gap-1.5">🏷️ {{ item.category }}</span>
                                </td>
                                
                                <td class="p-4 font-bold text-stone-800 tracking-tight text-sm">
                                    ₱{{ parseFloat(item.price).toFixed(2) }}
                                </td>
                                
                                <td class="p-4">
                                    <span 
                                        :class="item.is_available ? 'bg-emerald-50 text-emerald-700 border-emerald-200/60' : 'bg-stone-100 text-stone-500 border-stone-200'" 
                                        class="px-2.5 py-1 border text-[10px] font-black uppercase tracking-wider rounded-lg"
                                    >
                                        {{ item.is_available ? 'Available' : 'Sold Out' }}
                                    </span>
                                </td>
                                
                                <td class="p-4 pr-6 text-right space-x-2 whitespace-nowrap">
                                    <Link 
                                        :href="route('admin.menus.edit', item.id)" 
                                        class="inline-flex items-center gap-1.5 border border-blue-300 bg-white text-blue-500 font-bold text-[11px] px-3 py-1.5 rounded-lg transition-all shadow-2xs hover:bg-blue-50/50 hover:text-blue-700 hover:border-blue-400 active:scale-[0.97]"
                                    >
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                        Edit
                                    </Link>
                                    <button 
                                        @click="deleteItem(item.id, item.name)" 
                                        class="inline-flex items-center gap-1.5 border border-red-300 bg-white text-red-500 font-bold text-[11px] px-3 py-1.5 rounded-lg transition-all shadow-2xs hover:bg-rose-50/50 hover:text-rose-700 hover:border-red-400 active:scale-[0.97]"
                                    >
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            
                            <tr v-if="filteredMenuItems.length === 0">
                                <td colspan="5" class="p-12 text-center text-stone-400 font-medium">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <svg class="w-8 h-8 text-stone-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10.5 4.5h3.5m-3.5 0a2.25 2.25 0 00-2.25 2.25v.75m6 0V6.75a2.25 2.25 0 00-2.25-2.25z" />
                                        </svg>
                                        <p class="italic text-[13px]">
                                            {{ props.menuItems && props.menuItems.length > 0 ? 'No catalog results match your search parameters.' : 'The master catalog ledger is currently empty.' }}
                                        </p>
                                        <p v-if="!props.menuItems || props.menuItems.length === 0" class="text-[11px] text-stone-400 font-normal">Click "Add Menu Item" to record your first menu entry.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="filteredMenuItems.length > 0" class="px-6 py-4 border-t border-stone-100 flex items-center justify-between bg-[#FFE1E6]/20 text-[11px] font-bold text-stone-500">
                    <div>
                        Showing {{ (menuCurrentPage - 1) * menuPerPage + 1 }} to {{ Math.min(menuCurrentPage * menuPerPage, filteredMenuItems.length) }} of {{ filteredMenuItems.length }} Menu Items
                    </div>
                    <div class="flex items-center gap-2">
                        <button 
                            @click="menuCurrentPage--" 
                            :disabled="menuCurrentPage === 1"
                            class="px-3 py-1.5 rounded-lg border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 disabled:opacity-40 disabled:hover:bg-white disabled:cursor-not-allowed transition-all"
                        >
                            Previous
                        </button>
                        <button 
                            @click="menuCurrentPage++" 
                            :disabled="menuCurrentPage === totalMenuPages"
                            class="px-3 py-1.5 rounded-lg border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 disabled:opacity-40 disabled:hover:bg-white disabled:cursor-not-allowed transition-all"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>