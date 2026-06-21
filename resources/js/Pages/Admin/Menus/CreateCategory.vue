<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
});

const submit = () => {
    form.post(route('admin.categories.store'), {
        // Only reset the input field if the creation actually succeeds
        onSuccess: () => form.reset('name'),
    });
};
</script>

<template>
    <Head title="Create Category" />

    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto px-4 sm:px-6 py-10 font-sans antialiased">
            
            <!-- Breadcrumbs / Back Navigation -->
            <nav class="mb-5">
                <Link 
                    :href="route('admin.menus.index')" 
                    class="inline-flex items-center text-xs font-bold uppercase tracking-widest text-pink-700/70 hover:text-pink-600 bg-pink-100/40 hover:bg-pink-100/70 px-3 py-1.5 rounded-xl transition-all duration-200"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Catalog Master
                </Link>
            </nav>

            <!-- Header Section -->
            <div class="mb-8">
                <h2 class="text-3xl font-black tracking-tight text-stone-900">
                    Add New <span class="bg-gradient-to-r from-rose-500 to-pink-600 bg-clip-text text-transparent">Category</span>
                </h2>
                <p class="text-sm text-stone-500 mt-1.5">
                    Define a fresh group identifier to keep your cafe's catalog items neatly organized and highly searchable.
                </p>
            </div>

            <!-- Styled Premium Form Card Container -->
            <div class="bg-gradient-to-br from-white to-pink-50/20 rounded-2xl border border-pink-100 shadow-xl shadow-pink-900/[0.03] overflow-hidden backdrop-blur-md">
                
                <!-- Card Header Accent Ribbon -->
                <div class="h-1.5 w-full bg-gradient-to-r from-rose-400 via-pink-500 to-pink-600"></div>

                <form @submit.prevent="submit" class="p-6 sm:p-8 space-y-6">
                    
                    <!-- Input Layout Group -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-bold tracking-wide text-stone-700">
                            Category Label Designation
                        </label>
                        
                        <div class="relative rounded-xl shadow-xs">
                            <!-- Visual Context Icon Indicator inside Input Box -->
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-pink-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a1.43 1.43 0 002.022 0l4.319-4.319a1.43 1.43 0 000-2.022L9.581 3.659a2.25 2.25 0 00-1.591-.659z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                </svg>
                            </div>
                            
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="e.g., Seasonal Specialties, Signature Coffee, Bites"
                                class="block w-full bg-stone-50/60 border border-stone-200 rounded-xl pl-11 pr-4 py-3 text-stone-900 placeholder-stone-400 font-medium transition-all duration-200 text-sm focus:outline-none focus:ring-4 focus:ring-pink-500/10 focus:border-pink-400 focus:bg-white"
                                :class="{ 'border-rose-500 focus:border-rose-500 focus:ring-rose-500/10': form.errors.name }"
                                required
                                autofocus
                            />
                        </div>
                        
                        <!-- Reactive Hints / Errors Dynamic Display -->
                        <div class="flex items-start min-h-[20px]">
                            <p v-if="form.errors.name" class="text-xs font-semibold text-rose-600 flex items-center gap-1 animate-pulse">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ form.errors.name }}
                            </p>
                            <p v-else class="text-xs text-stone-400 font-medium">
                                Provide a direct clear category label. Note: labels are case-insensitive.
                            </p>
                        </div>
                    </div>

                    <!-- Layout Form Actions Container -->
                    <div class="flex items-center justify-end gap-3 pt-5 border-t border-pink-100/60">
                        <Link
                            :href="route('admin.menus.index')"
                            class="inline-flex items-center justify-center bg-white border border-stone-200 hover:bg-stone-50 text-stone-700 text-sm font-bold px-5 py-2.5 rounded-xl transition-all duration-150 active:scale-98 shadow-xs"
                        >
                            Cancel
                        </Link>
                        
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center justify-center bg-gradient-to-r from-rose-400 to-pink-500 hover:from-rose-500 hover:to-pink-600 text-white font-bold text-sm px-6 py-2.5 rounded-xl transition-all duration-200 shadow-md shadow-pink-500/10 hover:shadow-pink-500/20 active:scale-98 disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none"
                        >
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                            </svg>
                            <span>{{ form.processing ? 'Creating...' : 'Save Category Master' }}</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>