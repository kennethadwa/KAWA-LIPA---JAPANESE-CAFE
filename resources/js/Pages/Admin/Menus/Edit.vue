<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    menuItem: Object
});

// Create a local state to hold the reactive temporary preview URL
const imagePreview = ref(props.menuItem.image_path ? `/storage/${props.menuItem.image_path}` : null);

const form = useForm({
    _method: 'put', // CRITICAL: Spoofs a PUT request over a POST transmission so Laravel accepts file uploads
    name: props.menuItem.name,
    description: props.menuItem.description || '',
    price: props.menuItem.price,
    category: props.menuItem.category,
    is_available: props.menuItem.is_available == 1,
    image: null // Holds the raw binary file object when selected
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
    // CRITICAL: We call .post() because standard .put() breaks down when transferring binary multi-part file streams.
    // The '_method: "put"' inside the form object tells Laravel behind the scenes to process this as an update.
    form.post(route('admin.menus.update', props.menuItem.id));
};
</script>

<template>
    <Head :title="`Edit - ${menuItem.name}`" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-stone-200/60 pb-6 mb-8">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-stone-800 sm:text-3xl">Edit Menu Details</h2>
                    <p class="text-xs text-stone-500 font-medium mt-1.5">
                        Modifying records for item: <span class="text-stone-800 font-bold underline">{{ menuItem.name }}</span>
                    </p>
                </div>
                
                <Link 
                    :href="route('admin.menus.index')"
                    class="inline-flex items-center justify-center gap-2 border border-stone-200 bg-white hover:bg-stone-50 text-stone-600 font-bold text-xs px-4 py-2.5 rounded-xl transition-all active:scale-[0.98] shadow-xs shrink-0"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Cancel Changes
                </Link>
            </div>

            <div class="bg-white rounded-2xl border border-stone-200/80 shadow-xs p-6 sm:p-10 transition-all">
                <h3 class="text-xs font-black text-stone-400 uppercase tracking-widest border-b border-stone-100 pb-4 mb-6">
                    Modify Existing Catalog Record
                </h3>
                
                <form @submit.prevent="submit" class="space-y-6">
                    
                    <div>
                        <label class="block text-[11px] font-black uppercase text-stone-500 tracking-wider mb-2">
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
                                class="relative flex flex-col items-center justify-center min-h-[220px] w-full border-2 border-dashed border-sky-400 bg-sky-50/10 hover:bg-sky-50/30 rounded-2xl cursor-pointer p-6 text-center transition-all duration-200 overflow-hidden"
                            >
                                <div v-if="imagePreview" class="relative w-full flex items-center justify-center">
                                    <img 
                                        :src="imagePreview" 
                                        class="max-h-[350px] max-w-full h-auto object-contain rounded-xl shadow-sm border border-stone-200/50" 
                                        alt="Uploaded Preview" 
                                    />
                                    
                                    <div class="absolute inset-0 bg-stone-900/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-xl backdrop-blur-xs">
                                        <span class="text-white text-[11px] font-bold uppercase tracking-wider bg-stone-900/90 px-5 py-2.5 rounded-lg border border-stone-600 shadow-lg">
                                            Change Image
                                        </span>
                                    </div>
                                </div>

                                <div v-else class="flex flex-col items-center justify-center space-y-4 select-none animate-fade-in w-full">
                                    <div class="text-sky-500 group-hover:scale-105 transition-transform duration-200 mb-1">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5h10.5a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0016.5 4.5H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25z" />
                                        </svg>
                                    </div>

                                    <span class="bg-sky-500 text-white font-semibold text-sm px-10 py-3 rounded-lg shadow-sm group-hover:bg-sky-600 transition-colors inline-block">
                                        Browse
                                    </span>

                                    <div class="space-y-1.5 mt-2">
                                        <p class="text-stone-400 font-medium text-[13px] tracking-wide">drop a file here</p>
                                        <p class="text-[12px] text-stone-600 font-medium tracking-tight">
                                            PNG, JPG, or WEBP formats accepted. Leaving blank retains current asset.
                                        </p>
                                    </div>
                                </div>
                            </label>
                        </div>
                        
                        <div v-if="form.errors.image" class="text-rose-500 text-[11px] font-bold mt-2 pl-1">{{ form.errors.image }}</div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black uppercase text-stone-500 tracking-wider">Item Name</label>
                        <input 
                            v-model="form.name" 
                            type="text" 
                            placeholder="e.g., Spanish Latte" 
                            class="mt-1.5 w-full bg-white border border-stone-200 rounded-xl px-3.5 py-2.5 text-xs font-medium placeholder-stone-400 text-stone-700 shadow-2xs focus:outline-none focus:border-stone-400 focus:ring-2 focus:ring-stone-400/10 transition-all" 
                            required 
                        />
                        <div v-if="form.errors.name" class="text-rose-500 text-[11px] font-bold mt-1">{{ form.errors.name }}</div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[11px] font-black uppercase text-stone-500 tracking-wider">Price (PHP)</label>
                            <input 
                                v-model="form.price" 
                                type="number" 
                                step="0.01" 
                                placeholder="140.00" 
                                class="mt-1.5 w-full bg-white border border-stone-200 rounded-xl px-3.5 py-2.5 text-xs font-medium placeholder-stone-400 text-stone-700 shadow-2xs focus:outline-none focus:border-stone-400 focus:ring-2 focus:ring-stone-400/10 transition-all" 
                                required   
                            />
                            <div v-if="form.errors.price" class="text-rose-500 text-[11px] font-bold mt-1">{{ form.errors.price }}</div>
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-black uppercase text-stone-500 tracking-wider">Category</label>
                            <select 
                                v-model="form.category" 
                                class="mt-1.5 w-full bg-white border border-stone-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-stone-700 shadow-2xs focus:outline-none focus:border-stone-400 focus:ring-2 focus:ring-stone-400/10 transition-all"
                            >
                                <option value="coffee">☕ Coffee</option>
                                <option value="non-coffee">🍓 Non-Coffee</option>
                                <option value="pastry">🥐 Pastry</option>
                                <option value="meals">🍽️ Meals</option>
                            </select>
                            <div v-if="form.errors.category" class="text-rose-500 text-[11px] font-bold mt-1">{{ form.errors.category }}</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black uppercase text-stone-500 tracking-wider mb-2.5">Inventory Availability Status</label>
                        <div class="flex flex-wrap items-center gap-4">
                            <label class="flex items-center gap-2 text-xs font-semibold text-stone-700 cursor-pointer bg-stone-50/60 border border-stone-200 rounded-xl px-4 py-2.5 hover:bg-stone-100/60 transition-all select-none">
                                <input 
                                    type="radio" 
                                    :value="true" 
                                    v-model="form.is_available" 
                                    class="w-4 h-4 text-stone-800 border-stone-300 focus:ring-stone-400/40 focus:ring-offset-0"
                                />
                                Available for Purchase
                            </label>
                            <label class="flex items-center gap-2 text-xs font-semibold text-stone-700 cursor-pointer bg-stone-50/60 border border-stone-200 rounded-xl px-4 py-2.5 hover:bg-stone-100/60 transition-all select-none">
                                <input 
                                    type="radio" 
                                    :value="false" 
                                    v-model="form.is_available" 
                                    class="w-4 h-4 text-stone-800 border-stone-300 focus:ring-stone-400/40 focus:ring-offset-0"
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
                            class="mt-1.5 w-full bg-white border border-stone-200 rounded-xl px-3.5 py-2.5 text-xs font-medium placeholder-stone-400 text-stone-700 shadow-2xs focus:outline-none focus:border-stone-400 focus:ring-2 focus:ring-stone-400/10 transition-all"
                        ></textarea>
                        <div v-if="form.errors.description" class="text-rose-500 text-[11px] font-bold mt-1">{{ form.errors.description }}</div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-stone-100">
                        <button 
                            type="submit" 
                            :disabled="form.processing" 
                            class="w-full sm:w-auto bg-stone-800 hover:bg-stone-900 text-white font-bold text-sm uppercase tracking-wider px-8 py-3.5 rounded-lg transition-all shadow-md active:scale-[0.98] disabled:opacity-50"
                        >
                            {{ form.processing ? 'Saving Changes...' : 'Commit Updates to Ledger' }}
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </AuthenticatedLayout>
</template>