<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    categories: {
        type: Array,
        required: true
    }
});

const imagePreview = ref(null);

const form = useForm({
    name: '',
    description: '',
    price: '',
    category_id: props.categories.length > 0 ? props.categories[0].id : '', 
    is_available: true,
    image: null, 
});

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route('admin.menus.store'), {
        onError: () => {}
    });
};
</script>

<template>
    <Head title="Create Menu Item" />

    <AuthenticatedLayout>
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-sans antialiased">
            
            <!-- Breadcrumbs Header Master -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-pink-100/80 pb-6 mb-8">
                <div>
                    <h2 class="text-3xl font-black tracking-tight text-stone-900">
                        Add <span class="bg-gradient-to-r from-rose-500 to-pink-600 bg-clip-text text-transparent">Menu Item</span>
                    </h2>
                    <p class="text-xs text-stone-500 font-medium mt-1.5">
                        Introduce a new signature blend, dynamic pastry selection, or seasonal espresso asset to the menu master ledger.
                    </p>
                </div>
                
                <Link 
                    :href="route('admin.menus.index')"
                    class="inline-flex items-center justify-center gap-2 border border-pink-200/60 bg-pink-50/40 hover:bg-pink-100/60 text-pink-900/80 font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all duration-150 active:scale-95 shadow-2xs shrink-0"
                >
                    <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Catalog Ledger
                </Link>
            </div>

            <!-- Main Interactive Form Interface Block -->
            <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left Hand Column: Dynamic Interactive Media Dropzone Zone (4 Cols) -->
                <div class="lg:col-span-5 space-y-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                        Product Display Canvas
                    </label>
                    
                    <div class="relative group">
                        <input 
                            id="file-upload"
                            type="file" 
                            accept="image/*"
                            @change="handleFileChange"
                            class="hidden"
                        />

                        <label 
                            for="file-upload"
                            class="relative flex flex-col items-center justify-center min-h-[320px] w-full border-2 border-dashed border-pink-300 bg-gradient-to-br from-pink-50/30 to-rose-50/10 hover:from-pink-50/60 hover:to-rose-50/30 rounded-2xl cursor-pointer p-6 text-center transition-all duration-300 overflow-hidden shadow-xs"
                        >
                            <!-- Dynamic Image Rendering Layer -->
                            <div v-if="imagePreview" class="relative w-full h-full flex items-center justify-center animate-fade-in">
                                <img 
                                    :src="imagePreview" 
                                    class="max-h-[320px] max-w-full h-auto object-cover rounded-xl shadow-md border border-pink-100" 
                                    alt="Uploaded Preview" 
                                />
                                
                                <div class="absolute inset-0 bg-stone-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center rounded-xl backdrop-blur-xs">
                                    <span class="text-white text-xs font-bold uppercase tracking-widest bg-gradient-to-r from-rose-500 to-pink-500 px-5 py-2.5 rounded-xl shadow-lg transform transition-transform duration-200 group-hover:scale-105">
                                        Change Canvas Media
                                    </span>
                                </div>
                            </div>

                            <!-- Empty State Upload Layer -->
                            <div v-else class="flex flex-col items-center justify-center space-y-4 select-none w-full py-6">
                                <div class="text-pink-400 p-4 bg-pink-100/60 rounded-2xl transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3 shadow-2xs">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                                    </svg>
                                </div>

                                <span class="bg-gradient-to-r from-rose-400 to-pink-500 text-white font-bold text-xs uppercase tracking-widest px-6 py-2.5 rounded-xl shadow-md shadow-pink-500/10 group-hover:from-rose-500 group-hover:to-pink-600 transition-all">
                                    Browse Storage
                                </span>

                                <div class="space-y-1">
                                    <p class="text-stone-400 font-semibold text-xs tracking-wide">Or drag and drop asset raw file</p>
                                    <p class="text-[11px] text-stone-500 font-medium">
                                        Supported extensions: <span class="font-bold text-pink-600">PNG, JPG, WEBP</span>
                                    </p>
                                </div>
                            </div>
                        </label>
                    </div>
                    
                    <p v-if="form.errors.image" class="text-rose-600 text-xs font-semibold mt-2 pl-1 animate-pulse flex items-center gap-1">
                        ⚠️ {{ form.errors.image }}
                    </p>
                </div>

                <!-- Right Hand Column: Premium Product Parameters Data Card (7 Cols) -->
                <div class="lg:col-span-7 bg-gradient-to-br from-white to-pink-50/10 border border-pink-100 rounded-2xl p-6 sm:p-8 shadow-xl shadow-pink-900/[0.02] backdrop-blur-md space-y-5 relative">
                    <div class="h-1.5 w-full bg-gradient-to-r from-rose-400 to-pink-500 absolute top-0 left-0 rounded-t-2xl"></div>
                    
                    <!-- Form Inner Content Partition Header -->
                    <h3 class="text-xs font-black text-stone-400 uppercase tracking-widest border-b border-pink-100/60 pb-3">
                        Metadata Input Panel
                    </h3>

                    <!-- Parameter Row: Product Variant Name -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold tracking-wide text-stone-700">Item Presentation Name</label>
                        <input 
                            v-model="form.name" 
                            type="text" 
                            placeholder="e.g., Signature White Spanish Latte" 
                            class="w-full bg-stone-50/60 border border-stone-200 rounded-xl px-4 py-3 text-sm font-medium placeholder-stone-400 text-stone-800 transition-all focus:outline-none focus:ring-4 focus:ring-pink-500/10 focus:border-pink-400 focus:bg-white" 
                            :class="{ 'border-rose-500 focus:border-rose-500': form.errors.name }"
                            required 
                        />
                        <p v-if="form.errors.name" class="text-rose-600 text-xs font-medium mt-1">{{ form.errors.name }}</p>
                    </div>

                    <!-- Parameter Multi-Grid Row: Price & Category Selector -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        
                        <!-- Input Element: Numerical Price -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold tracking-wide text-stone-700">Price Structure (PHP)</label>
                            <div class="relative rounded-xl shadow-2xs">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <span class="text-stone-400 font-bold text-xs tracking-wider">₱</span>
                                </div>
                                <input 
                                    v-model="form.price" 
                                    type="number" 
                                    step="0.01" 
                                    placeholder="145.00" 
                                    class="w-full bg-stone-50/60 border border-stone-200 rounded-xl pl-8 pr-4 py-3 text-sm font-semibold placeholder-stone-400 text-stone-800 transition-all focus:outline-none focus:ring-4 focus:ring-pink-500/10 focus:border-pink-400 focus:bg-white" 
                                    :class="{ 'border-rose-500 focus:border-rose-500': form.errors.price }"
                                    required   
                                />
                            </div>
                            <p v-if="form.errors.price" class="text-rose-600 text-xs font-medium mt-1">{{ form.errors.price }}</p>
                        </div>
                        
                        <!-- Input Element: Category Dropdown Wrapper -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold tracking-wide text-stone-700">Category Node Link</label>
                            <select 
                                v-model="form.category_id" 
                                class="w-full bg-stone-50/60 border border-stone-200 rounded-xl px-3.5 py-3 text-sm font-semibold text-stone-700 transition-all focus:outline-none focus:ring-4 focus:ring-pink-500/10 focus:border-pink-400 focus:bg-white"
                                :class="{ 'border-rose-500 focus:border-rose-500': form.errors.category_id }"
                                required
                            >
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                    {{ cat.name }}
                                </option>
                                <option v-if="categories.length === 0" value="" disabled>
                                    ⚠️ Create a category first!
                                </option>
                            </select>
                            <p v-if="form.errors.category_id" class="text-rose-600 text-xs font-medium mt-1">{{ form.errors.category_id }}</p>
                        </div>
                    </div>

                    <!-- Parameter Row: Custom Styled Operational Radio Pills -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold tracking-wide text-stone-700">Initial Catalog Ledger Status</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            
                            <!-- Radio Component Option: Available -->
                            <label 
                                class="flex items-center gap-3 text-xs font-bold tracking-wide cursor-pointer border rounded-xl px-4 py-3.5 transition-all select-none"
                                :class="form.is_available === true 
                                    ? 'border-pink-400 bg-pink-50/40 text-pink-900 shadow-2xs' 
                                    : 'border-stone-200 bg-stone-50/30 text-stone-500 hover:bg-stone-50'"
                            >
                                <input 
                                    type="radio" 
                                    :value="true" 
                                    v-model="form.is_available" 
                                    class="w-4 h-4 text-pink-500 border-stone-300 focus:ring-pink-400/40 focus:ring-offset-0"
                                />
                                Active & Available for Purchase
                            </label>

                            <!-- Radio Component Option: Sold Out -->
                            <label 
                                class="flex items-center gap-3 text-xs font-bold tracking-wide cursor-pointer border rounded-xl px-4 py-3.5 transition-all select-none"
                                :class="form.is_available === false 
                                    ? 'border-rose-400 bg-rose-50/40 text-rose-900 shadow-2xs' 
                                    : 'border-stone-200 bg-stone-50/30 text-stone-500 hover:bg-stone-50'"
                            >
                                <input 
                                    type="radio" 
                                    :value="false" 
                                    v-model="form.is_available" 
                                    class="w-4 h-4 text-rose-500 border-stone-300 focus:ring-rose-400/40 focus:ring-offset-0"
                                />
                                Mark as Delisted / Sold Out
                            </label>
                        </div>
                    </div>

                    <!-- Parameter Row: Narrative Description Block -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold tracking-wide text-stone-700">Description / Tasting Profile Notes</label>
                        <textarea 
                            v-model="form.description" 
                            rows="4" 
                            placeholder="Crafted with sweetened condensed milk configurations, premium micro-foamed dairy, mixed meticulously with signature dark roast espresso pulls..." 
                            class="w-full bg-stone-50/60 border border-stone-200 rounded-xl px-4 py-3 text-sm font-medium placeholder-stone-400 text-stone-800 transition-all focus:outline-none focus:ring-4 focus:ring-pink-500/10 focus:border-pink-400 focus:bg-white"
                        ></textarea>
                        <p v-if="form.errors.description" class="text-rose-600 text-xs font-medium mt-1">{{ form.errors.description }}</p>
                    </div>

                    <!-- Submittal Footer Toolbar Boundary Layout -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-pink-100/60">
                        <Link
                            :href="route('admin.menus.index')"
                            class="inline-flex items-center justify-center bg-white border border-stone-200 hover:bg-stone-50 text-stone-700 text-xs font-bold uppercase tracking-wider px-5 py-3 rounded-xl transition-all duration-150 active:scale-98"
                        >
                            Cancel
                        </Link>

                        <button 
                            type="submit" 
                            :disabled="form.processing" 
                            class="w-full sm:w-auto bg-gradient-to-r from-rose-400 to-pink-500 hover:from-rose-500 hover:to-pink-600 text-white font-bold text-xs uppercase tracking-widest px-8 py-3.5 rounded-xl transition-all duration-200 shadow-md shadow-pink-500/10 hover:shadow-pink-500/20 active:scale-98 disabled:opacity-50 disabled:pointer-events-none"
                        >
                            <div class="flex items-center justify-center gap-2">
                                <svg v-if="form.processing" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                                </svg>
                                <span>{{ form.processing ? 'Registering Asset...' : 'Save Item to Ledger' }}</span>
                            </div>
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>