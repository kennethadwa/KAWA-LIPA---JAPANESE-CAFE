<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
});

const submit = () => {
    form.post(route('admin.categories.store'), {
        onFinish: () => form.reset('name'),
    });
};
</script>

<template>
    <Head title="Create Category" />

    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto px-4 sm:px-6 py-10">
            <nav class="mb-4">
                <Link 
                    :href="route('admin.menus.index')" 
                    class="inline-flex items-center text-sm font-medium text-stone-500 hover:text-stone-900 transition-colors"
                >
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Catalog Master
                </Link>
            </nav>

            <div class="mb-8">
                <h2 class="text-3xl font-bold tracking-tight text-stone-900">Add New Category</h2>
                <p class="text-sm text-stone-500 mt-1.5">
                    Define a fresh group identifier to keep your catalog items neatly organized.
                </p>
            </div>

            <div class="bg-white rounded-xl border border-stone-200 shadow-sm overflow-hidden">
                <form @submit.prevent="submit" class="p-6 sm:p-8 space-y-6">
                    
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-semibold text-stone-700">
                            Category Name
                        </label>
                        <div class="relative rounded-lg shadow-xs">
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="e.g., Seasonal, Merchandise, Signature Coffee"
                                class="block w-full bg-stone-50 border border-stone-200 rounded-lg px-4 py-3 text-stone-900 placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-stone-500/10 focus:border-stone-500 focus:bg-white transition-all text-sm font-medium"
                                :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500/10': form.errors.name }"
                                required
                                autofocus
                            />
                        </div>
                        
                        <p v-if="form.errors.name" class="text-xs font-medium text-red-600 mt-1">
                            {{ form.errors.name }}
                        </p>
                        <p v-else class="text-xs text-stone-400">
                            Provide a concise name. This label is case-insensitive.
                        </p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-5 border-t border-stone-100">
                        <Link
                            :href="route('admin.menus.index')"
                            class="inline-flex items-center justify-center bg-white border border-stone-200 hover:bg-stone-50 text-stone-700 text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors shadow-xs"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center justify-center bg-stone-900 hover:bg-stone-800 text-white font-semibold text-sm px-5 py-2.5 rounded-lg transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                            </svg>
                            {{ form.processing ? 'Saving Changes...' : 'Save Category' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>