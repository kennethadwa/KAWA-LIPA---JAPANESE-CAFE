<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const props = defineProps({
    menuItems: Array,
    categories: Array 
});

/* --- Pagination Configs & States --- */
const categoryCurrentPage = ref(1);
const categoryPerPage = 5;

const menuCurrentPage = ref(1);
const menuPerPage = 10;

/* --- Filtering States --- */
const categorySearchQuery = ref('');
const menuSearchQuery = ref('');
const selectedCategoryFilter = ref('all'); // Holds category.id integer or 'all'

/* --- Helper to get category name by ID --- */
const getCategoryName = (id) => {
    const category = props.categories.find(c => c.id === id);
    return category ? category.name : 'Uncategorized';
};

/* --- Computed Filtered & Paginated Arrays --- */
const filteredCategories = computed(() => {
    if (!props.categories) return [];
    const query = categorySearchQuery.value.toLowerCase().trim();
    return props.categories.filter(cat => 
        cat.name.toLowerCase().includes(query)
    );
});

const paginatedCategories = computed(() => {
    const start = (categoryCurrentPage.value - 1) * categoryPerPage;
    return filteredCategories.value.slice(start, start + categoryPerPage);
});

const totalCategoryPages = computed(() => {
    return Math.ceil(filteredCategories.value.length / categoryPerPage) || 1;
});

// Double-layered filter for Menu Items
const filteredMenuItems = computed(() => {
    if (!props.menuItems) return [];
    
    let items = props.menuItems;
    
    // Filter by category_id mapping
    if (selectedCategoryFilter.value !== 'all') {
        items = items.filter(item => item.category_id === Number(selectedCategoryFilter.value));
    }
    
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
const handleDeleteCategory = (catId, catName) => {
    if (confirm(`Warning: Deleting the category "${catName}" will permanently purge all menu items assigned under it. Proceed?`)) {
        router.delete(route('admin.categories.destroy', catId));
    }
};
</script>

<template>
    <Head title="Manage Menus" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 pb-6 mb-8 border-b border-stone-200">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-stone-900">Menu Catalog</h2>
                    <p class="text-sm text-stone-500 mt-1">
                        Configure your espresso variants, pastries, and main culinary profiles.
                    </p>
                </div>
                
                <div class="flex items-center gap-3 self-stretch md:self-auto">
                    <Link 
                        :href="route('admin.categories.create')"
                        class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-stone-100 hover:bg-stone-200 text-stone-800 font-semibold text-sm px-4 py-2.5 rounded-xl transition shadow-xs focus:outline-none focus:ring-2 focus:ring-stone-500/20"
                    >
                        <svg class="w-4 h-4 text-stone-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Category
                    </Link>

                    <Link 
                        :href="route('admin.menus.create')"
                        class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-sm px-5 py-2.5 rounded-xl transition shadow-sm hover:shadow active:scale-[0.99] focus:outline-none focus:ring-2 focus:ring-rose-500/20"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Menu Item
                    </Link>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-stone-200 shadow-xs overflow-hidden mb-10">
                <div class="p-5 border-b border-stone-100 bg-stone-50/70 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <h3 class="text-sm font-semibold text-stone-700 tracking-tight">
                        Category Groups <span class="ml-1.5 text-xs font-normal text-stone-400 bg-stone-200/60 px-2 py-0.5 rounded-full">{{ categories?.length || 0 }}</span>
                    </h3>
                    
                    <div class="relative max-w-xs w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input 
                            v-model="categorySearchQuery"
                            type="text" 
                            placeholder="Search categories..." 
                            class="w-full bg-white border border-stone-200 rounded-xl pl-10 pr-4 py-2 text-sm placeholder-stone-400 text-stone-800 shadow-2xs focus:outline-none focus:border-stone-400 focus:ring-4 focus:ring-stone-100 transition"
                        />
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-stone-200 text-xs font-semibold uppercase tracking-wider text-stone-500 bg-stone-50/50">
                                <th class="p-4 pl-6">Label Name</th>
                                <th class="p-4">Slug / System Key</th>
                                <th class="p-4 text-center">Linked Products</th>
                                <th class="p-4 pr-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-sm text-stone-600">
                            <tr v-for="cat in paginatedCategories" :key="cat.id" class="hover:bg-stone-50/60 transition-colors">
                                <td class="p-4 pl-6 font-medium text-stone-900">
                                    <span class="capitalize flex items-center gap-2.5">
                                        <span v-if="cat.name.toLowerCase() === 'coffee'">☕</span>
                                        <span v-else-if="cat.name.toLowerCase() === 'non-coffee'">🍓</span>
                                        <span v-else-if="cat.name.toLowerCase() === 'pastry'">🥐</span>
                                        <span v-else-if="cat.name.toLowerCase() === 'meals'">🍽️</span>
                                        <span v-else>🏷️</span>
                                        {{ cat.name }}
                                    </span>
                                </td>
                                <td class="p-4 font-mono text-xs text-stone-400">
                                    {{ cat.slug }}
                                </td>
                                <td class="p-4 text-center text-stone-500 font-medium">
                                    {{ cat.count }} items
                                </td>
                                <td class="p-4 pr-6 text-right space-x-4 whitespace-nowrap">
                                    <Link 
                                        :href="route('admin.categories.edit', cat.id)"
                                        class="text-amber-600 hover:text-amber-700 font-medium transition"
                                    >
                                        Edit
                                    </Link>
                                    <button 
                                        @click="handleDeleteCategory(cat.id, cat.name)"
                                        class="text-rose-600 hover:text-rose-700 font-medium transition"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            
                            <tr v-if="filteredCategories.length === 0">
                                <td colspan="4" class="p-8 text-center text-stone-400">
                                    {{ categories && categories.length > 0 ? 'No category tags matched your filter criteria.' : 'No category records found in database.' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="filteredCategories.length > 0" class="px-6 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50/30 text-xs text-stone-500">
                    <div>
                        Showing <span class="font-medium text-stone-800">{{ (categoryCurrentPage - 1) * categoryPerPage + 1 }}</span> to <span class="font-medium text-stone-800">{{ Math.min(categoryCurrentPage * categoryPerPage, filteredCategories.length) }}</span> of <span class="font-medium text-stone-800">{{ filteredCategories.length }}</span> Categories
                    </div>
                    <div class="flex items-center gap-2">
                        <button 
                            @click="categoryCurrentPage--" 
                            :disabled="categoryCurrentPage === 1"
                            class="px-3 py-1.5 rounded-lg border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 font-medium disabled:opacity-40 disabled:hover:bg-white disabled:cursor-not-allowed transition shadow-2xs"
                        >
                            Previous
                        </button>
                        <button 
                            @click="categoryCurrentPage++" 
                            :disabled="categoryCurrentPage === totalCategoryPages"
                            class="px-3 py-1.5 rounded-lg border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 font-medium disabled:opacity-40 disabled:hover:bg-white disabled:cursor-not-allowed transition shadow-2xs"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-stone-200 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-stone-100 flex flex-col md:flex-row md:items-center justify-between bg-stone-50/70 gap-4">
                    <h3 class="text-sm font-semibold text-stone-700 tracking-tight">
                        Active Master Ledger <span class="ml-1.5 text-xs font-normal text-stone-400 bg-stone-200/60 px-2 py-0.5 rounded-full">{{ filteredMenuItems.length }}</span>
                    </h3>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                        <select 
                            v-model="selectedCategoryFilter"
                            class="bg-white border border-stone-200 rounded-xl px-3 py-2 text-sm text-stone-700 font-medium shadow-2xs focus:outline-none focus:border-stone-400 focus:ring-4 focus:ring-stone-100 transition capitalize w-full sm:w-44"
                        >
                            <option value="all">All Categories</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                {{ cat.name }}
                            </option>
                        </select>

                        <div class="relative w-full sm:w-72">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input 
                                v-model="menuSearchQuery"
                                type="text" 
                                placeholder="Search by name or info..." 
                                class="w-full bg-white border border-stone-200 rounded-xl pl-10 pr-4 py-2 text-sm placeholder-stone-400 text-stone-800 shadow-2xs focus:outline-none focus:border-stone-400 focus:ring-4 focus:ring-stone-100 transition"
                            />
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-stone-200 text-xs font-semibold uppercase tracking-wider text-stone-500 bg-stone-50/50">
                                <th class="p-4 pl-6">Product Details</th>
                                <th class="p-4">Category</th>
                                <th class="p-4">Price</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 pr-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-sm text-stone-600">
                            <tr v-for="item in paginatedMenuItems" :key="item.id" class="hover:bg-stone-50/60 transition-colors">
                                <td class="p-4 pl-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-stone-100 flex-shrink-0 overflow-hidden border border-stone-200 shadow-inner">
                                            <img 
                                                v-if="item.image_url" 
                                                :src="item.image_url" 
                                                :alt="item.name" 
                                                class="w-full h-full object-cover"
                                            />
                                            <div v-else class="w-full h-full flex items-center justify-center text-stone-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-stone-900 text-base">{{ item.name }}</div>
                                            <div class="text-xs text-stone-400 max-w-xs sm:max-w-md mt-0.5 truncate font-normal">
                                                {{ item.description || 'No descriptive notes assigned.' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="p-4 font-medium text-stone-600">
                                    <span v-if="item.category.toLowerCase() === 'coffee'" class="inline-flex items-center gap-1.5">☕ Coffee</span>
                                    <span v-else-if="item.category.toLowerCase() === 'non-coffee'" class="inline-flex items-center gap-1.5">🍓 Non-Coffee</span>
                                    <span v-else-if="item.category.toLowerCase() === 'pastry'" class="inline-flex items-center gap-1.5">🥐 Pastry</span>
                                    <span v-else-if="item.category.toLowerCase() === 'meals'" class="inline-flex items-center gap-1.5">🍽️ Meals</span>
                                    <span v-else class="capitalize inline-flex items-center gap-1.5">🏷️ {{ item.category }}</span>
                                </td>
                                
                                <td class="p-4 font-semibold text-stone-900 text-base">
                                    ₱{{ parseFloat(item.price).toFixed(2) }}
                                </td>
                                
                                <td class="p-4">
                                    <span 
                                        :class="item.is_available ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-stone-100 text-stone-500 border-stone-200'" 
                                        class="px-2.5 py-1 border text-xs font-semibold tracking-wide rounded-lg"
                                    >
                                        {{ item.is_available ? 'Available' : 'Sold Out' }}
                                    </span>
                                </td>
                                
                                <td class="p-4 pr-6 text-right space-x-2 whitespace-nowrap">
                                    <Link 
                                        :href="route('admin.menus.edit', item.id)" 
                                        class="inline-flex items-center gap-1 bg-white border border-stone-200 hover:bg-stone-50 text-stone-700 font-medium text-xs px-3 py-1.5 rounded-lg transition shadow-2xs"
                                    >
                                        Edit
                                    </Link>
                                    <button 
                                        @click="deleteItem(item.id, item.name)" 
                                        class="inline-flex items-center gap-1 bg-white border border-rose-200 hover:bg-rose-50 text-rose-600 font-medium text-xs px-3 py-1.5 rounded-lg transition shadow-2xs"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            
                            <tr v-if="filteredMenuItems.length === 0">
                                <td colspan="5" class="p-16 text-center text-stone-400">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <svg class="w-8 h-8 text-stone-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10.5 4.5h3.5m-3.5 0a2.25 2.25 0 00-2.25 2.25v.75m6 0V6.75a2.25 2.25 0 00-2.25-2.25z" />
                                        </svg>
                                        <p class="font-medium text-sm">
                                            {{ props.menuItems && props.menuItems.length > 0 ? 'No catalog results match your parameters.' : 'Your master menu list is currently empty.' }}
                                        </p>
                                        <p v-if="!props.menuItems || props.menuItems.length === 0" class="text-xs text-stone-400">Click "Add Menu Item" above to add your first dynamic entry.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="filteredMenuItems.length > 0" class="px-6 py-4 border-t border-stone-100 flex items-center justify-between bg-stone-50/30 text-xs text-stone-500">
                    <div>
                        Showing <span class="font-medium text-stone-800">{{ (menuCurrentPage - 1) * menuPerPage + 1 }}</span> to <span class="font-medium text-stone-800">{{ Math.min(menuCurrentPage * menuPerPage, filteredMenuItems.length) }}</span> of <span class="font-medium text-stone-800">{{ filteredMenuItems.length }}</span> Menu Items
                    </div>
                    <div class="flex items-center gap-2">
                        <button 
                            @click="menuCurrentPage--" 
                            :disabled="menuCurrentPage === 1"
                            class="px-3 py-1.5 rounded-lg border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 font-medium disabled:opacity-40 disabled:hover:bg-white disabled:cursor-not-allowed transition shadow-2xs"
                        >
                            Previous
                        </button>
                        <button 
                            @click="menuCurrentPage++" 
                            :disabled="menuCurrentPage === totalMenuPages"
                            class="px-3 py-1.5 rounded-lg border border-stone-200 bg-white hover:bg-stone-50 text-stone-700 font-medium disabled:opacity-40 disabled:hover:bg-white disabled:cursor-not-allowed transition shadow-2xs"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>