<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    items: Array
});

const isModalOpen = ref(false);
const activeFilter = ref('all');
const fileInputRef = ref(null);

// Store multiple object URLs for clean visual previews
const filePreviews = ref([]);

const form = useForm({
    title: '',
    files: [], // Changed from single null object to an Array container
    type: 'event',
    featured: false
});

const filteredItems = computed(() => {
    if (activeFilter.value === 'all') return props.items;
    return props.items.filter(item => item.type === activeFilter.value);
});

const triggerFileSelect = () => {
    fileInputRef.value.click();
};

// Extracted file processor helper to manage multi-selection and drag-drops
const processFiles = (filesList) => {
    const filesArray = Array.from(filesList);
    
    // Merge new files into our existing form selection array
    form.files = [...form.files, ...filesArray];
    
    // Generate individual render previews
    filesArray.forEach(file => {
        if (file.type.startsWith('image/')) {
            filePreviews.value.push({
                name: file.name,
                type: 'image',
                url: URL.createObjectURL(file)
            });
        } else if (file.type.startsWith('video/')) {
            filePreviews.value.push({
                name: file.name,
                type: 'video',
                url: URL.createObjectURL(file)
            });
        }
    });
};

const handleFileChange = (e) => {
    if (e.target.files.length > 0) {
        processFiles(e.target.files);
    }
};

const handleFileDrop = (e) => {
    if (e.dataTransfer.files.length > 0) {
        processFiles(e.dataTransfer.files);
    }
};

// Allow user to remove a single item from the batch pool before submitting
const removeSelectedFile = (index) => {
    form.files.splice(index, 1);
    filePreviews.value.splice(index, 1);
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    filePreviews.value = [];
};

const submitForm = () => {
    form.post(route('admin.gallery.store'), {
        onSuccess: () => {
            closeModal();
        }
    });
};

const deleteItem = (id) => {
    if (confirm('Delete this media item permanently?')) {
        form.delete(route('admin.gallery.destroy', id));
    }
};
</script>

<template>
    <Head title="Vibe Gallery Management" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="font-black text-xl text-stone-800 tracking-wide uppercase">KAWA Vibe Gallery</h2>
                    <p class="text-xs font-semibold text-pink-700/60 uppercase tracking-wider mt-0.5">Manage branding photography and video assets</p>
                </div>
                <button 
                    @click="isModalOpen = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-white rounded-xl bg-gradient-to-r from-rose-400 to-pink-500 shadow-md shadow-pink-500/20 transform transition duration-150 hover:scale-[1.02] active:scale-[0.98]"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Upload Assets
                </button>
            </div>
        </template>

        <div class="py-2 space-y-6">
            <div class="flex flex-wrap gap-2 border-b border-pink-100 pb-4">
                <button 
                    v-for="filter in ['all', 'event', 'customer', 'crew']" 
                    :key="filter"
                    @click="activeFilter = filter"
                    class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all"
                    :class="activeFilter === filter ? 'bg-gradient-to-r from-rose-400 to-pink-500 text-white shadow-xs' : 'bg-pink-50/60 text-pink-900/70 hover:bg-pink-100/70'"
                >
                    {{ filter }}
                </button>
            </div>

            <div v-if="filteredItems.length === 0" class="flex flex-col items-center justify-center p-16 bg-pink-50/10 rounded-2xl border border-dashed border-pink-200">
                <p class="text-stone-400 font-medium text-sm">No media objects found matching this category selection.</p>
            </div>

            <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <div 
                    v-for="item in filteredItems" 
                    :key="item.id" 
                    class="group relative bg-white border border-pink-50 rounded-2xl shadow-xs overflow-hidden transform transition duration-200 hover:shadow-md"
                >
                    <span v-if="item.featured" class="absolute top-3 left-3 z-10 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-rose-500 text-white shadow-xs">
                        ⭐ Featured
                    </span>

                    <div class="aspect-square bg-stone-100 relative overflow-hidden flex items-center justify-center">
                        <video 
                            v-if="item.media_type === 'video'" 
                            :src="item.file_url" 
                            controls 
                            class="w-full h-full object-cover"
                        ></video>
                        <img 
                            v-else 
                            :src="item.file_url" 
                            :alt="item.title" 
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                        />
                    </div>

                    <div class="p-4 space-y-2">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h4 class="font-bold text-stone-800 text-sm truncate max-w-[150px]">{{ item.title || 'Untitled Asset' }}</h4>
                                <span class="inline-block mt-0.5 text-[10px] font-bold uppercase tracking-wider text-pink-700/60">{{ item.type }}</span>
                            </div>
                            <button 
                                @click="deleteItem(item.id)" 
                                class="text-stone-400 hover:text-rose-600 p-1 rounded-lg hover:bg-stone-50 transition-colors"
                                title="Remove File From Directory Storage Logs"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-1.5 14.25a2.25 2.25 0 01-2.25 2.25H8.25A2.25 2.25 0 016 22.5L4.5 8.25m15 0a.75.75 0 00-.75-.75h-15a.75.75 0 00-.75.75m15 0H3.75m1.5 0V4.5A2.25 2.25 0 017.5 2.25h9a2.25 2.25 0 012.25 2.25V8.25M9 13.5h.008v.008H9v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 4.5h.008v.008H9V18zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/40 backdrop-blur-xs">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg border border-pink-100 overflow-hidden transform transition-all">
                <div class="px-6 py-4 border-b border-pink-100 flex justify-between items-center bg-pink-50/30">
                    <h3 class="font-black text-stone-800 tracking-wide uppercase text-sm">Upload Gallery Batch</h3>
                    <button @click="closeModal" class="text-stone-400 hover:text-stone-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1">Batch Global Caption (Optional)</label>
                        <input v-model="form.title" type="text" class="w-full px-4 py-2.5 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-pink-400" placeholder="Defaults to individual file name if left blank" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-2">Select Media Files</label>
                        
                        <input 
                            ref="fileInputRef"
                            type="file" 
                            @change="handleFileChange" 
                            accept="image/*,video/*" 
                            multiple
                            class="hidden" 
                        />

                        <div 
                            @click="triggerFileSelect"
                            @dragover.prevent
                            @drop.prevent="handleFileDrop"
                            class="group/uploader border-2 border-dashed border-sky-400/80 hover:border-pink-400 rounded-2xl p-6 flex flex-col items-center justify-center text-center cursor-pointer bg-sky-50/10 hover:bg-pink-50/10 transition-all duration-200"
                        >
                            <div class="text-sky-500 group-hover/uploader:text-pink-500 transition-colors duration-150 mb-2">
                                <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                                </svg>
                            </div>

                            <button 
                                type="button"
                                class="px-6 py-1.5 bg-sky-500 group-hover/uploader:bg-pink-500 text-white font-bold text-xs rounded-full shadow-xs transition-colors tracking-wide"
                            >
                                Browse Files
                            </button>
                            <p class="text-xs text-stone-400 font-medium mt-2">Click or drop multiple files here</p>
                        </div>
                    </div>

                    <div v-if="filePreviews.length > 0" class="space-y-2">
                        <label class="block text-[10px] font-black uppercase text-stone-400 tracking-wider">Queue Selection ({{ form.files.length }} files)</label>
                        <div class="grid grid-cols-3 gap-2 border border-stone-100 p-2 rounded-xl bg-stone-50/50">
                            <div v-for="(preview, index) in filePreviews" :key="index" class="relative aspect-square rounded-lg overflow-hidden border border-stone-200 shadow-xs group">
                                <img v-if="preview.type === 'image'" :src="preview.url" class="w-full h-full object-cover" />
                                <div v-else class="w-full h-full bg-slate-800 flex items-center justify-center text-white text-[10px] font-bold p-1 text-center truncate">
                                    🎥 Video
                                </div>
                                <button 
                                    type="button"
                                    @click.stop="removeSelectedFile(index)"
                                    class="absolute top-1 right-1 bg-rose-600 text-white p-1 rounded-full opacity-90 hover:opacity-100 transition shadow-xs"
                                >
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1">Gallery Filter Group Placement Type</label>
                        <select v-model="form.type" class="w-full px-4 py-2.5 border border-stone-200 rounded-xl text-sm focus:outline-none focus:border-pink-400">
                            <option value="event">Event Highlight</option>
                            <option value="customer">Customer Vibe</option>
                            <option value="crew">Crew / Behind The Scenes</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 py-2">
                        <input type="checkbox" id="featured" v-model="form.featured" class="w-4 h-4 text-pink-500 border-stone-300 rounded focus:ring-pink-400 focus:ring-opacity-25" />
                        <label for="featured" class="text-xs font-bold uppercase tracking-wider text-stone-700 cursor-pointer select-none">Pin / Highlight all as Featured</label>
                    </div>

                    <div class="pt-4 flex justify-end gap-3 border-t border-pink-50">
                        <button type="button" @click="closeModal" class="px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-stone-500 hover:text-stone-700">Cancel</button>
                        <button type="submit" :disabled="form.processing || form.files.length === 0" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white bg-gradient-to-r from-rose-400 to-pink-500 rounded-xl shadow-xs transition transform hover:scale-[1.01] disabled:opacity-40">
                            {{ form.processing ? 'Uploading Batch...' : 'Save Assets' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>