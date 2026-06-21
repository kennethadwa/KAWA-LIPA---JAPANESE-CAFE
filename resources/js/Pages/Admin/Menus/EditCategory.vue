<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    category: Object
});

const form = useForm({
    name: props.category.name,
});

const submit = () => {
    // CRITICAL: Bind targeting to the primary key ID rather than the text string name
    form.put(route('admin.categories.update', props.category.id));
};
</script>

<template>
    <Head title="Edit Category" />

    <AuthenticatedLayout>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-sans antialiased">
            
            <!-- Breadcrumbs Header Master -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-pink-100/80 pb-6 mb-8">
                <div>
                    <h2 class="text-3xl font-black tracking-tight text-stone-900">
                        Modify <span class="bg-gradient-to-r from-rose-500 to-pink-600 bg-clip-text text-transparent">Category</span>
                    </h2>
                    <p class="text-xs text-stone-500 font-medium mt-1.5">
                        Updating this text will seamlessly update the classification tag across all linked catalog items.
                    </p>
                </div>
                
                <Link 
                    :href="route('admin.menus.index')"
                    class="inline-flex items-center justify-center gap-2 border border-pink-200/60 bg-pink-50/40 hover:bg-pink-100/60 text-pink-900/80 font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-xl transition-all duration-150 active:scale-95 shadow-2xs shrink-0"
                >
                    <svg class="w-4 h-4 text-pink-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Catalog Master
                </Link>
            </div>

            <!-- Premium Group Parameter Card -->
            <div class="bg-gradient-to-br from-white to-pink-50/10 border border-pink-100 rounded-2xl shadow-xl shadow-pink-900/[0.02] backdrop-blur-md relative overflow-hidden">
                <div class="h-1.5 w-full bg-gradient-to-r from-rose-400 to-pink-500 absolute top-0 left-0"></div>
                
                <form @submit.prevent="submit" class="p-6 sm:p-8 space-y-6">
                    
                    <!-- Inner Section Header Label -->
                    <h3 class="text-xs font-black text-stone-400 uppercase tracking-widest border-b border-pink-100/60 pb-3">
                        Taxonomy Node Ledger
                    </h3>

                    <!-- Parameter Row: Category Name Input -->
                    <div class="space-y-2">
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-stone-700">
                            Category Classification Name
                        </label>
                        
                        <div class="relative rounded-xl shadow-2xs">
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="e.g., Seasonal Specialties, Pastry Merchandise"
                                class="w-full bg-stone-50/60 border border-stone-200 rounded-xl px-4 py-3 text-sm font-medium placeholder-stone-400 text-stone-800 transition-all focus:outline-none focus:ring-4 focus:ring-pink-500/10 focus:border-pink-400 focus:bg-white"
                                :class="{ 'border-rose-500 focus:border-rose-500 focus:ring-rose-500/10': form.errors.name }"
                                required
                                autofocus
                            />
                        </div>
                        
                        <!-- Reactive State Context Footers -->
                        <p v-if="form.errors.name" class="text-rose-600 text-xs font-semibold mt-2 pl-1 animate-pulse flex items-center gap-1">
                            ⚠️ {{ form.errors.name }}
                        </p>
                        <p v-else class="text-[11px] text-stone-400 font-medium pl-1">
                            Ensure the name uniquely distinguishes your menu groupings accurately.
                        </p>
                    </div>

                    <!-- Submittal Footer Toolbar Boundary Layout -->
                    <div class="flex items-center justify-end gap-3 pt-5 border-t border-pink-100/60">
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
                                <span>{{ form.processing ? 'Updating Group...' : 'Save Changes' }}</span>
                            </div>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>