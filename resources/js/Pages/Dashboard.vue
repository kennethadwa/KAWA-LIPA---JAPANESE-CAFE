<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

// Receive metrics ledger from backend
defineProps({
    stats: Object
});

// --- CUSTOM INTERACTIVE CALENDAR SYSTEM (TITLE & BODY ARCHITECTURE) ---
const currentDate = ref(new Date());
const selectedDateKey = ref(null);

// Separate state bindings for title and body inputs
const noteTitle = ref('');
const noteBody = ref('');

// Updated sample data logging format containing Title + Body structures
const calendarNotes = ref({
    '2026-06-21': {
        title: '🚚 Father’s Day Promo',
        body: 'Execution successful—Free Americano Promo launched on all meals!'
    }
});

const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

const currentYear = computed(() => currentDate.value.getFullYear());
const currentMonth = computed(() => currentDate.value.getMonth());

const prevMonth = () => {
    currentDate.value = new Date(currentYear.value, currentMonth.value - 1, 1);
};

const nextMonth = () => {
    currentDate.value = new Date(currentYear.value, currentMonth.value + 1, 1);
};

// Generate calendar grid array structure
const daysInMonthGrid = computed(() => {
    const year = currentYear.value;
    const month = currentMonth.value;
    
    const firstDayIndex = new Date(year, month, 1).getDay();
    const totalDays = new Date(year, month + 1, 0).getDate();
    
    const daysGrid = [];
    
    for (let i = 0; i < firstDayIndex; i++) {
        daysGrid.push({ day: null, dateString: null });
    }
    
    for (let day = 1; day <= totalDays; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        daysGrid.push({ day, dateString: dateStr });
    }
    
    return daysGrid;
});

const selectDate = (dateString) => {
    if (!dateString) return;
    selectedDateKey.value = dateString;
    
    if (calendarNotes.value[dateString]) {
        noteTitle.value = calendarNotes.value[dateString].title || '';
        noteBody.value = calendarNotes.value[dateString].body || '';
    } else {
        noteTitle.value = '';
        noteBody.value = '';
    }
};

const saveNote = () => {
    if (!selectedDateKey.value) return;
    
    // Clean up or remove object record if values are empty
    if (noteTitle.value.trim() === '' && noteBody.value.trim() === '') {
        delete calendarNotes.value[selectedDateKey.value];
    } else {
        calendarNotes.value[selectedDateKey.value] = {
            title: noteTitle.value.trim(),
            body: noteBody.value.trim()
        };
    }
    selectedDateKey.value = null; // Close tracking panel tray state
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="border-b border-pink-100/60 pb-5">
                <h2 class="text-2xl font-black tracking-tight text-stone-800">Workspace Overview</h2>
                <p class="text-xs text-rose-700/70 font-semibold mt-1">
                    Welcome back! Here's what's happening at <span class="text-pink-600 font-bold">KAWA LIPA</span> today.
                </p>
            </div>
        </template>

        <div class="min-h-screen bg-gradient-to-b from-pink-50/40 to-pink-100/20 rounded-3xl p-4 sm:p-6 my-6 space-y-8 border border-pink-100/50">
            
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <Link :href="route('admin.menus.index')" class="block bg-white p-6 rounded-2xl border-l-8 border-l-rose-500 border-y border-r border-stone-100 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-widest text-stone-500 group-hover:text-rose-600 transition-colors">Menu Catalog Items</span>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-xl shadow-xs border border-rose-100">🍽️</span>
                    </div>
                    <div class="mt-4 flex items-baseline justify-between">
                        <span class="text-4xl font-black text-stone-800 tracking-tight">{{ stats.menuItemsCount }}</span>
                        <span class="text-[10px] font-extrabold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-md border border-rose-100">Live Items</span>
                    </div>
                </Link>

                <Link :href="route('admin.menus.index')" class="block bg-white p-6 rounded-2xl border-l-8 border-l-emerald-500 border-y border-r border-stone-100 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-widest text-stone-500 group-hover:text-emerald-600 transition-colors">Menu Categories</span>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 shadow-xs border border-emerald-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a1.125 1.125 0 001.591 0l4.318-4.318a1.125 1.125 0 000-1.591L9.568 3.659A2.25 2.25 0 008.146 3zM6 7.5a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                        </span>
                    </div>
                    <div class="mt-4 flex items-baseline justify-between">
                        <span class="text-4xl font-black text-stone-800 tracking-tight">{{ stats.categoriesCount }}</span>
                        <span class="text-[10px] font-extrabold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100">Groups</span>
                    </div>
                </Link>

                <Link :href="route('admin.announcements.index')" class="block bg-white p-6 rounded-2xl border-l-8 border-l-amber-500 border-y border-r border-stone-100 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-widest text-stone-500 group-hover:text-amber-600 transition-colors">Announcements</span>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600 shadow-xs border border-amber-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.68-.69-1.808-.69-2.488 0l-2.23 2.23c-.68.68-.68 1.81 0 2.49a1.757 1.757 0 002.488 0l2.23-2.23c.68-.68.68-1.81 0-2.49zM15 5.25a3 3 0 11-6 0 3 3 0 016 0zM4.5 16.5H3a.75.75 0 010-1.5h1.5a.75.75 0 010 1.5zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                    </div>
                    <div class="mt-4 flex items-baseline justify-between">
                        <span class="text-4xl font-black text-stone-800 tracking-tight">{{ stats.announcementsCount }}</span>
                        <span class="text-[10px] font-extrabold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-100">Broadcasts</span>
                    </div>
                </Link>

                <Link :href="route('admin.gallery.index')" class="block bg-white p-6 rounded-2xl border-l-8 border-l-fuchsia-600 border-y border-r border-stone-100 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase tracking-widest text-stone-500 group-hover:text-fuchsia-700 transition-colors">Gallery Assets</span>
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-fuchsia-50 text-fuchsia-700 shadow-xs border border-fuchsia-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        </span>
                    </div>
                    <div class="mt-4 flex items-baseline justify-between">
                        <span class="text-3xl font-black text-stone-800 tracking-tight">{{ stats.gallery.total }}</span>
                        <span class="text-[10px] font-bold text-fuchsia-700 bg-fuchsia-50 px-2 py-0.5 rounded-md border border-fuchsia-100">
                            📸 {{ stats.gallery.images }} | 🎥 {{ stats.gallery.videos }}
                        </span>
                    </div>
                </Link>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-pink-100 shadow-sm shadow-black grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center justify-between border-b border-stone-100 shadow-sm pb-3">
                        <h3 class="text-lg font-black text-stone-800 tracking-tight">Operational Calendar Scheduler</h3>
                        <div class="inline-flex items-center gap-2 bg-stone-50 rounded-lg p-1 border border-stone-200/60">
                            <button @click="prevMonth" class="p-1.5 rounded-md hover:bg-white hover:text-pink-600 transition text-stone-600 font-bold">&larr;</button>
                            <span class="text-xs font-black px-2 text-stone-700 min-w-[120px] text-center uppercase tracking-wider">
                                {{ monthNames[currentMonth] }} {{ currentYear }}
                            </span>
                            <button @click="nextMonth" class="p-1.5 rounded-md hover:bg-white hover:text-pink-600 transition text-stone-600 font-bold">&rarr;</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-7 text-center text-[10px] font-black tracking-widest text-stone-400 uppercase">
                        <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                    </div>

                    <div class="grid grid-cols-7 gap-2">
                        <div v-for="(cell, index) in daysInMonthGrid" :key="index" class="min-h-[75px]">
                            <button 
                                v-if="cell.day"
                                @click="selectDate(cell.dateString)"
                                :class="[
                                    'w-full h-full rounded-xl flex flex-col items-start justify-between p-2 text-xs font-bold border transition relative group overflow-hidden',
                                    calendarNotes[cell.dateString] 
                                        ? 'bg-rose-50/70 border-rose-200 text-rose-700 hover:bg-rose-100' 
                                        : 'bg-stone-50/50 border-stone-200/60 text-stone-700 hover:bg-white hover:border-pink-400'
                                ]"
                            >
                                <div class="flex items-center justify-between w-full">
                                    <span>{{ cell.day }}</span>
                                    <span v-if="calendarNotes[cell.dateString]" class="h-1.5 w-1.5 rounded-full bg-rose-600"></span>
                                </div>

                                <div v-if="calendarNotes[cell.dateString]" class="w-full text-left mt-1">
                                    <p class="text-[9px] leading-tight font-black tracking-tight text-rose-800 bg-white/80 border border-rose-200 px-1 py-0.5 rounded-md truncate max-w-full">
                                        {{ calendarNotes[cell.dateString].title }}
                                    </p>
                                </div>
                            </button>
                            <div v-else class="w-full h-full bg-stone-50/20 rounded-xl border border-stone-100/20"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-stone-50/70 border border-stone-200/60 rounded-xl p-5 flex flex-col justify-between space-y-4">
                    <div v-if="selectedDateKey" class="space-y-4 h-full flex flex-col justify-between">
                        <div class="space-y-3">
                            <div>
                                <h4 class="text-sm font-black text-stone-800">Operational Logging Manager</h4>
                                <p class="text-[11px] font-bold text-pink-600 mt-0.5">Date Selected: {{ selectedDateKey }}</p>
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-black uppercase text-stone-400 tracking-wider">Event Title</label>
                                <input 
                                    type="text" 
                                    v-model="noteTitle"
                                    placeholder="e.g., Mother's Day, Store Closed"
                                    class="w-full mt-1 px-3 py-2 text-xs font-bold text-stone-700 bg-white border border-stone-200 rounded-xl focus:ring-1 focus:ring-pink-400 focus:border-pink-400"
                                />
                            </div>

                            <div>
                                <label class="text-[10px] font-black uppercase text-stone-400 tracking-wider">Details / Description</label>
                                <textarea 
                                    v-model="noteBody"
                                    placeholder="Type complete description logs here..."
                                    class="w-full mt-1 p-3 text-xs font-semibold text-stone-700 bg-white border border-stone-200 rounded-xl focus:ring-1 focus:ring-pink-400 focus:border-pink-400 min-h-[100px]"
                                ></textarea>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <button @click="saveNote" class="flex-1 bg-pink-600 hover:bg-pink-700 text-white text-xs font-black py-2.5 rounded-xl transition shadow-xs">Save Update</button>
                            <button @click="selectedDateKey = null" class="bg-stone-200 hover:bg-stone-300 text-stone-700 text-xs font-bold px-4 py-2.5 rounded-xl transition">Cancel</button>
                        </div>
                    </div>

                    <div v-else class="h-full flex flex-col items-center justify-center text-center p-6 space-y-2">
                        <span class="text-2xl">📅</span>
                        <h4 class="text-xs font-black text-stone-600 uppercase tracking-wider">No Date Selected</h4>
                        <p class="text-[11px] font-medium text-stone-400 max-w-[200px]">Click any active date on the left layout map to create, view, or modify operational logs.</p>
                    </div>
                </div>

            </div>

        </div>
    </AuthenticatedLayout>
</template>