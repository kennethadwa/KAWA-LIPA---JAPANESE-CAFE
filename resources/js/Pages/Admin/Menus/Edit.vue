<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    menuItem: Object,
    categories: {
        type: Array,
        required: true
    }
});

// Create a local state to hold the reactive temporary preview URL
const imagePreview = ref(props.menuItem.image_path ? `/storage/${props.menuItem.image_path}` : null);

const form = useForm({
    _method: 'put', // CRITICAL: Spoofing PUT via POST multi-part stream transmission
    name: props.menuItem.name,
    description: props.menuItem.description || '',
    price: props.menuItem.price,
    category_id: props.menuItem.category_id, 
    is_available: props.menuItem.is_available == 1,
    image: null 
});

/**
 * Capture file assignment events and generate a quick responsive local preview
 */
const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route('admin.menus.update', props.menuItem.id));
};
</script>

<template>
    <Head :title="`Edit - ${menuItem.name}`" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-sans antialiased">
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-stone-200/60 pb-6 mb-8">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-stone-800 sm:text-3xl">
                        Edit <span class="bg-gradient-to-r from-rose-500 to-pink-600 bg-clip-text text-transparent">Menu Details</span>
                    </h2>
                    <p class="text-xs text-stone-500 font-medium mt-1.5">
                        Modifying records for item: <span class="text-stone-800 font-bold underline">{{ menuItem.name }}</span>
                    </p>
                </div>
                
                <Link 
                    :href="route('admin.menus.index')"
                    class="inline-flex items-center justify-center gap-2 border border-pink-200 bg-white hover:bg-pink-50/50 text-pink-700 font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all active:scale-[0.98] shadow-xs shrink-0"
                >
                    <svg class="w-3.5 h-3.5 text-pink-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Cancel Changes
                </Link>
            </div>

            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    <div class="lg:col-span-5 space-y-2">
                        <label class="block text-[11px] font-black uppercase text-stone-500 tracking-wider">
                            Product Display Image
                        </label>
                        
                        <div class="relative group">
                            <input 
                                id="file-upload"
                                type="file" 
                                accept="image/*"
                                @change="handleImageUpload"
                                class="hidden"
                            />

                            <label 
                                for="file-upload"
                                class="relative flex flex-col items-center justify-center min-h-[340px] lg:min-h-[445px] w-full border-2 border-dashed border-pink-300 bg-pink-50/10 hover:bg-pink-50/30 rounded-2xl cursor-pointer p-6 text-center transition-all duration-200 overflow-hidden"
                            >
                                <div v-if="imagePreview" class="relative w-full h-full flex items-center justify-center">
                                    <img 
                                        :src="imagePreview" 
                                        class="max-h-[320px] lg:max-h-[380px] max-w-full h-auto object-contain rounded-xl shadow-sm border border-stone-200/50" 
                                        alt="Uploaded Preview" 
                                    />
                                    
                                    <div class="absolute inset-0 bg-stone-900/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-xl backdrop-blur-xs">
                                        <span class="text-white text-[11px] font-bold uppercase tracking-wider bg-stone-900/90 px-5 py-2.5 rounded-lg border border-stone-600 shadow-lg">
                                            Change Asset Image
                                        </span>
                                    </div>
                                </div>

                                <div v-else class="flex flex-col items-center justify-center space-y-4 select-none w-full">
                                    <div class="text-pink-500 group-hover:scale-105 transition-transform duration-200 mb-1">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5h10.5a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0016.5 4.5H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25z" />
                                        </svg>
                                    </div>

                                    <span class="bg-gradient-to-r from-rose-400 to-pink-500 text-white font-bold text-xs uppercase tracking-wider px-6 py-2.5 rounded-lg shadow-xs group-hover:from-rose-500 group-hover:to-pink-600 transition-colors inline-block">
                                        Browse Image
                                    </span>

                                    <div class="space-y-1.5 mt-2">
                                        <p class="text-stone-400 font-semibold text-xs tracking-wide">Drop asset file here</p>
                                        <p class="text-[11px] text-stone-500 font-medium tracking-tight px-4">
                                            PNG, JPG, or WEBP formats accepted. Leaving blank preserves current image entry safely.
                                        </p>
                                    </div>
                                </div>
                            </label>
                        </div>
                        
                        <div v-if="form.errors.image" class="text-rose-500 text-[11px] font-bold mt-2 pl-1">{{ form.errors.image }}</div>
                    </div>

                    <div class="lg:col-span-7 bg-white rounded-2xl border border-stone-200/80 shadow-xs p-6 sm:p-8 space-y-6">
                        
                        <div>
                            <h3 class="text-xs font-black text-stone-400 uppercase tracking-widest border-b border-stone-100 pb-3 mb-1">
                                Modify Existing Catalog Record
                            </h3>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black uppercase text-stone-500 tracking-wider">Item Name</label>
                            <input 
                                v-model="form.name" 
                                type="text" 
                                placeholder="e.g., Spanish Latte" 
                                class="mt-1.5 w-full bg-stone-50/50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-xs font-medium placeholder-stone-400 text-stone-700 shadow-2xs focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-400/10 focus:bg-white transition-all" 
                                required 
                            />
                            <div v-if="form.errors.name" class="text-rose-500 text-[11px] font-bold mt-1">{{ form.errors.name }}</div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[11px] font-black uppercase text-stone-500 tracking-wider">Price (PHP)</label>
                                <div class="relative mt-1.5 rounded-xl shadow-2xs">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                        <span class="text-xs font-bold text-stone-400">₱</span>
                                    </div>
                                    <input 
                                        v-model="form.price" 
                                        type="number" 
                                        step="0.01" 
                                        placeholder="140.00" 
                                        class="w-full bg-stone-50/50 border border-stone-200 rounded-xl pl-8 pr-3.5 py-2.5 text-xs font-medium placeholder-stone-400 text-stone-700 focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-400/10 focus:bg-white transition-all" 
                                        required   
                                    />
                                </div>
                                <div v-if="form.errors.price" class="text-rose-500 text-[11px] font-bold mt-1">{{ form.errors.price }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-[11px] font-black uppercase text-stone-500 tracking-wider">Category Classification</label>
                                <select 
                                    v-model="form.category_id" 
                                    class="mt-1.5 w-full bg-stone-50/50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-stone-700 shadow-2xs focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-400/10 focus:bg-white transition-all"
                                    required
                                >
                                    <option 
                                        v-for="cat in categories" 
                                        :key="cat.id" 
                                        :value="cat.id"
                                    >
                                        {{ cat.name }}
                                    </option>

                                    <option v-if="categories.length === 0" value="" disabled>
                                        ⚠️ Create a category first!
                                    </option>
                                </select>
                                <div v-if="form.errors.category_id" class="text-rose-500 text-[11px] font-bold mt-1">{{ form.errors.category_id }}</div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black uppercase text-stone-500 tracking-wider mb-2">Inventory Availability Status</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label 
                                    :class="[
                                        form.is_available 
                                            ? 'border-pink-400 bg-pink-50/20 text-pink-900 ring-2 ring-pink-400/10' 
                                            : 'border-stone-200 bg-stone-50/40 text-stone-600 hover:bg-stone-50'
                                    ]"
                                    class="flex items-center gap-3 text-xs font-bold border rounded-xl px-4 py-3 cursor-pointer transition-all select-none"
                                >
                                    <input 
                                        type="radio" 
                                        :value="true" 
                                        v-model="form.is_available" 
                                        class="w-4 h-4 text-pink-600 border-stone-300 focus:ring-pink-400/40 focus:ring-offset-0"
                                    />
                                    Available for Purchase
                                </label>
                                
                                <label 
                                    :class="[
                                        !form.is_available 
                                            ? 'border-pink-400 bg-pink-50/20 text-pink-900 ring-2 ring-pink-400/10' 
                                            : 'border-stone-200 bg-stone-50/40 text-stone-600 hover:bg-stone-50'
                                    ]"
                                    class="flex items-center gap-3 text-xs font-bold border rounded-xl px-4 py-3 cursor-pointer transition-all select-none"
                                >
                                    <input 
                                        type="radio" 
                                        :value="false" 
                                        v-model="form.is_available" 
                                        class="w-4 h-4 text-pink-600 border-stone-300 focus:ring-pink-400/40 focus:ring-offset-0"
                                    />
                                    Out of Stock / Sold Out
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black uppercase text-stone-500 tracking-wider">Description / Tasting Notes</label>
                            <textarea 
                                v-model="form.description" 
                                rows="4" 
                                placeholder="Sweetened condensed milk mixed with signature dark roast espresso..." 
                                class="mt-1.5 w-full bg-stone-50/50 border border-stone-200 rounded-xl px-3.5 py-2.5 text-xs font-medium placeholder-stone-400 text-stone-700 shadow-2xs focus:outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-400/10 focus:bg-white transition-all"
                            ></textarea>
                            <div v-if="form.errors.description" class="text-rose-500 text-[11px] font-bold mt-1">{{ form.errors.description }}</div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-stone-100">
                            <button 
                                type="submit" 
                                :disabled="form.processing" 
                                class="w-full sm:w-auto bg-stone-800 hover:bg-stone-900 text-white font-bold text-xs uppercase tracking-widest px-8 py-3.5 rounded-xl transition-all shadow-md active:scale-[0.98] disabled:opacity-50"
                            >
                                {{ form.processing ? 'Saving Changes...' : 'Commit Updates to Ledger' }}
                            </button>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </AuthenticatedLayout>
</template>