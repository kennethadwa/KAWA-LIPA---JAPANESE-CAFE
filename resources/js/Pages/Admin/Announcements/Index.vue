<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    announcements: Array
});

const isModalOpen = ref(false);
const isEditing = ref(false);
const currentId = ref(null);
const searchQuery = ref('');

const form = useForm({
    title: '',
    content: '',
    status: 'draft',
    expires_at: ''
});

const filteredAnnouncements = computed(() => {
    if (!searchQuery.value) return props.announcements;
    return props.announcements.filter(item => 
        item.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        item.content.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const openCreateModal = () => {
    isEditing.value = false;
    currentId.value = null;
    form.reset();
    isModalOpen.value = true;
};

const openEditModal = (announcement) => {
    isEditing.value = true;
    currentId.value = announcement.id;
    form.title = announcement.title;
    form.content = announcement.content;
    form.status = announcement.status;
    form.expires_at = announcement.expires_at ? announcement.expires_at.split('T')[0] : '';
    isModalOpen.value = true;
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(route('admin.announcements.update', currentId.value), {
            onSuccess: () => closeModal()
        });
    } else {
        form.post(route('admin.announcements.store'), {
            onSuccess: () => closeModal()
        });
    }
};

const deleteAnnouncement = (id) => {
    if (confirm('Are you sure you want to completely remove this publication record?')) {
        form.delete(route('admin.announcements.destroy', id));
    }
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};
</script>

<template>
    <Head title="Announcements Management" />

    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 py-10 font-sans antialiased space-y-8">
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 border-b border-stone-200 pb-8">
                <div class="space-y-1">
                    <h2 class="text-3xl font-black tracking-tight text-stone-800 sm:text-4xl">
                        KAWA <span class="bg-gradient-to-r from-rose-500 to-pink-600 bg-clip-text text-transparent">Announcements</span>
                    </h2>
                    <p class="text-sm text-stone-500 font-medium">
                        Broadcast system notifications, café events, and operating schedule alterations.
                    </p>
                </div>
                
                <button 
                    @click="openCreateModal"
                    class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 text-sm font-bold uppercase tracking-wider text-white rounded-xl bg-gradient-to-r from-rose-500 to-pink-600 shadow-md shadow-pink-500/20 hover:from-rose-600 hover:to-pink-700 transform transition-all active:scale-[0.98] shrink-0"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Create New Broadcast
                </button>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="relative w-full sm:max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input 
                        v-model="searchQuery"
                        type="text" 
                        placeholder="Search system broadcasts..."
                        class="w-full pl-11 pr-4 py-3 bg-stone-50 border border-stone-200 rounded-xl text-sm font-medium text-stone-700 placeholder-stone-400 focus:outline-none focus:border-pink-400 focus:ring-4 focus:ring-pink-400/10 focus:bg-white transition-all"
                    />
                </div>
                <div class="hidden sm:block text-sm text-stone-400 font-semibold">
                    Showing {{ filteredAnnouncements.length }} of {{ announcements.length }} ledger entries
                </div>
            </div>

            <div v-if="filteredAnnouncements.length === 0" class="flex flex-col items-center justify-center py-20 px-6 bg-stone-50/50 rounded-2xl border-2 border-dashed border-stone-200 text-center">
                <div class="w-14 h-14 rounded-2xl bg-pink-50 flex items-center justify-center text-pink-500 mb-5">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-stone-700">No matching records isolated</h3>
                <p class="text-sm text-stone-400 max-w-sm mt-1.5">Refine your lookahead parameters or generate a fresh system announcement item to populate this ledger view.</p>
            </div>

            <div v-else class="bg-white border border-stone-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[900px]">
                        <thead>
                            <tr class="bg-stone-50/70 border-b border-stone-200 text-xs font-black uppercase tracking-widest text-stone-500">
                                <th class="py-5 px-8 w-1/4">Broadcast Title</th>
                                <th class="py-5 px-8 w-2/5">Message Context Summary</th>
                                <th class="py-5 px-8">Status Indicator</th>
                                <th class="py-5 px-8">Expirations</th>
                                <th class="py-5 px-8 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-sm font-medium text-stone-600">
                            <tr v-for="item in filteredAnnouncements" :key="item.id" class="hover:bg-stone-50/30 transition-colors group">
                                
                                <td class="py-6 px-8 font-bold text-stone-800">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-stone-900 font-black text-base tracking-tight">{{ item.title }}</span>
                                        <span class="text-xs text-stone-400 font-bold tracking-wide">ID: #{{ item.id }}</span>
                                    </div>
                                </td>
                                
                                <td class="py-6 px-8">
                                    <div class="max-w-md text-stone-500 line-clamp-2 leading-relaxed text-sm">
                                        {{ item.content }}
                                    </div>
                                </td>
                                
                                <td class="py-6 px-8">
                                    <span 
                                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-extrabold uppercase tracking-wider shadow-2xs border"
                                        :class="item.status === 'published' 
                                            ? 'bg-emerald-50 border-emerald-200 text-emerald-800' 
                                            : 'bg-amber-50 border-amber-200 text-amber-800'"
                                    >
                                        <span class="w-2 h-2 rounded-full" :class="item.status === 'published' ? 'bg-emerald-500' : 'bg-amber-500'"></span>
                                        {{ item.status }}
                                    </span>
                                </td>
                                
                                <td class="py-6 px-8 text-stone-500 font-bold">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ item.expires_at ? new Date(item.expires_at).toLocaleDateString(undefined, {month: 'short', day: 'numeric', year: 'numeric'}) : 'Indefinite Run' }}
                                    </div>
                                </td>
                                
                                <td class="py-6 px-8 text-right">
                                    <div class="flex items-center justify-end gap-3.5 opacity-90 group-hover:opacity-100 transition-opacity">
                                        <button 
                                            @click="openEditModal(item)" 
                                            class="inline-flex items-center justify-center p-2 text-stone-500 hover:text-pink-600 hover:bg-pink-50 rounded-xl transition-all"
                                            title="Edit Records"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                            </svg>
                                        </button>
                                        <button 
                                            @click="deleteAnnouncement(item.id)" 
                                            class="inline-flex items-center justify-center p-2 text-stone-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all"
                                            title="Delete File"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-stone-900/40 backdrop-blur-md transition-all duration-300">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl border border-stone-200 overflow-hidden transform transition-all animate-in fade-in zoom-in-95 duration-150">
                
                <div class="px-8 py-6 border-b border-stone-100 flex justify-between items-center bg-stone-50/50">
                    <div class="space-y-0.5">
                        <h3 class="font-black text-stone-800 tracking-tight text-lg">
                            {{ isEditing ? 'Modify System Announcement' : 'Draft New Broadcast Node' }}
                        </h3>
                        <p class="text-xs text-stone-400 font-medium">Fill out parameters to control visibility lifecycle indicators.</p>
                    </div>
                    <button 
                        @click="closeModal" 
                        class="p-1.5 text-stone-400 hover:text-stone-600 rounded-xl hover:bg-stone-100 transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="p-8 space-y-6">
                    
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-stone-500 mb-2">Broadcast Message Title</label>
                        <input 
                            v-model="form.title" 
                            type="text" 
                            placeholder="e.g., Extended Holiday Store Operations"
                            class="w-full px-4 py-3 bg-stone-50/40 border border-stone-200 rounded-xl text-sm font-medium text-stone-700 placeholder-stone-400 focus:outline-none focus:border-pink-400 focus:ring-4 focus:ring-pink-400/10 focus:bg-white transition-all shadow-2xs" 
                            required 
                        />
                    </div>

                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-stone-500 mb-2">Main Content / Context Details</label>
                        <textarea 
                            v-model="form.content" 
                            rows="5" 
                            placeholder="Provide full operating schedules or system information alerts for patrons and floor staff..."
                            class="w-full px-4 py-3 bg-stone-50/40 border border-stone-200 rounded-xl text-sm font-medium text-stone-700 placeholder-stone-400 focus:outline-none focus:border-pink-400 focus:ring-4 focus:ring-pink-400/10 focus:bg-white transition-all shadow-2xs" 
                            required
                        ></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-stone-500 mb-2.5">Publishing Status Mode</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label 
                                :class="[
                                    form.status === 'draft' 
                                        ? 'border-pink-400 bg-pink-50/10 text-pink-900 ring-4 ring-pink-400/10' 
                                        : 'border-stone-200 bg-stone-50/30 text-stone-600 hover:bg-stone-50'
                                ]"
                                class="flex items-center gap-3 text-sm font-bold border rounded-xl px-5 py-4 cursor-pointer transition-all select-none"
                            >
                                <input 
                                    type="radio" 
                                    value="draft" 
                                    v-model="form.status" 
                                    class="w-4 h-4 text-pink-600 border-stone-300 focus:ring-pink-400/40 focus:ring-offset-0"
                                />
                                Save as Draft
                            </label>
                            
                            <label 
                                :class="[
                                    form.status === 'published' 
                                        ? 'border-pink-400 bg-pink-50/10 text-pink-900 ring-4 ring-pink-400/10' 
                                        : 'border-stone-200 bg-stone-50/30 text-stone-600 hover:bg-stone-50'
                                ]"
                                class="flex items-center gap-3 text-sm font-bold border rounded-xl px-5 py-4 cursor-pointer transition-all select-none"
                            >
                                <input 
                                    type="radio" 
                                    value="published" 
                                    v-model="form.status" 
                                    class="w-4 h-4 text-pink-600 border-stone-300 focus:ring-pink-400/40 focus:ring-offset-0"
                                />
                                Publish Live
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-stone-500 mb-2">System Expiration Window (Optional)</label>
                        <input 
                            v-model="form.expires_at" 
                            type="date" 
                            class="w-full px-4 py-3 bg-stone-50/40 border border-stone-200 rounded-xl text-sm font-medium text-stone-700 focus:outline-none focus:border-pink-400 focus:ring-4 focus:ring-pink-400/10 focus:bg-white transition-all shadow-2xs" 
                        />
                        <p class="text-xs text-stone-400 font-medium mt-1.5">Leave empty to run broadcast indefinitely across active displays.</p>
                    </div>

                    <div class="pt-6 flex items-center justify-end gap-4 border-t border-stone-100">
                        <button 
                            type="button" 
                            @click="closeModal" 
                            class="px-5 py-3 text-sm font-bold uppercase tracking-wider text-stone-400 hover:text-stone-600 hover:bg-stone-50 rounded-xl transition-colors"
                        >
                            Dismiss
                        </button>
                        <button 
                            type="submit" 
                            :disabled="form.processing" 
                            class="px-7 py-3.5 text-sm font-bold uppercase tracking-widest text-white bg-stone-800 hover:bg-stone-900 rounded-xl shadow-md transition-all active:scale-[0.98] disabled:opacity-50"
                        >
                            {{ form.processing ? 'Syncing...' : (isEditing ? 'Commit Changes' : 'Execute Broadcast') }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>