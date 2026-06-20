<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-pink-100/60 pb-5">
                <div>
                    <h2 class="text-2xl font-black tracking-tight text-stone-800">Workspace Overview</h2>
                    <p class="text-xs text-rose-700/70 font-semibold mt-1">
                        Welcome back! Here's what's happening at <span class="text-pink-600 font-bold">KAWA LIPA</span> today.
                    </p>
                </div>
                <!-- Premium Filter Switch -->
                <div class="inline-flex items-center self-start sm:self-center bg-pink-100/70 backdrop-blur-md rounded-xl p-1 text-xs font-bold text-rose-800 border border-pink-200/40">
                    <button class="bg-white px-4 py-1.5 rounded-lg shadow-xs text-pink-600">Today</button>
                    <button class="px-4 py-1.5 opacity-70 hover:opacity-100 transition">This Week</button>
                    <button class="px-4 py-1.5 opacity-70 hover:opacity-100 transition">This Month</button>
                </div>
            </div>
        </template>

        <!-- Dynamic Container Base: Gentle Pink Hue Instead of Plain White -->
        <div class="min-mix-h-screen bg-gradient-to-b from-pink-50/40 to-pink-100/20 rounded-3xl p-4 sm:p-6 my-6 space-y-8 border border-pink-100/50">
            
            <!-- ========================================== -->
            <!-- SECTION 1: CORE METRIC GRADIENT CARDS      -->
            <!-- ========================================== -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                
                <!-- Card 1: Total Revenue -->
                <div class="bg-gradient-to-br from-white to-pink-50/60 p-6 rounded-2xl border border-pink-200/60 shadow-xs hover:border-pink-300 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-widest text-stone-400">Total Revenue</span>
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-pink-50 border border-pink-100 text-pink-600 font-black text-sm">₱</span>
                    </div>
                    <div class="mt-4 flex items-baseline justify-between">
                        <span class="text-2xl font-black text-stone-800 tracking-tight">₱24,850.00</span>
                        <span class="text-[10px] font-extrabold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">+12.3%</span>
                    </div>
                </div>

                <!-- Card 2: Orders Placed -->
                <div class="bg-gradient-to-br from-white to-pink-50/60 p-6 rounded-2xl border border-pink-200/60 shadow-xs hover:border-pink-300 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-widest text-stone-400">Orders Placed</span>
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-pink-100/80 text-pink-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                        </span>
                    </div>
                    <div class="mt-4 flex items-baseline justify-between">
                        <span class="text-2xl font-black text-stone-800 tracking-tight">142</span>
                        <span class="text-[10px] font-extrabold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">+8.1%</span>
                    </div>
                </div>

                <!-- Card 3: Event Bookings -->
                <div class="bg-gradient-to-br from-white to-pink-50/60 p-6 rounded-2xl border border-pink-200/60 shadow-xs hover:border-pink-300 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-widest text-stone-400">Event Bookings</span>
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-rose-100/70 text-rose-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"/></svg>
                        </span>
                    </div>
                    <div class="mt-4 flex items-baseline justify-between">
                        <span class="text-2xl font-black text-stone-800 tracking-tight">5 <span class="text-xs font-normal text-stone-400">Reserved</span></span>
                        <span class="text-[10px] font-extrabold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-100">Pending</span>
                    </div>
                </div>

                <!-- Card 4: Average Ticket Spend -->
                <div class="bg-gradient-to-br from-white to-pink-50/60 p-6 rounded-2xl border border-pink-200/60 shadow-xs hover:border-pink-300 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-widest text-stone-400">Avg. Ticket Spend</span>
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-pink-100/80 text-pink-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21a1.125 1.125 0 01-1.125 1.125H5.625A1.125 1.125 0 014.5 21V4.257c0-.526.362-.98.875-1.055l13.829-2.028a1.125 1.125 0 011.296 1.119zM15 10.5a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm-4.5 4.5a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                        </span>
                    </div>
                    <div class="mt-4 flex items-baseline justify-between">
                        <span class="text-2xl font-black text-stone-800 tracking-tight">₱175.00</span>
                        <span class="text-[10px] font-extrabold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-100">-2.4%</span>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- SECTION 2: GRAPHICS & TARGET DATA PANELS   -->
            <!-- ========================================== -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                
                <!-- Main Activity Chart Card -->
                <div class="lg:col-span-2 bg-white/70 backdrop-blur-md p-6 rounded-2xl border border-pink-200/60 shadow-xs flex flex-col justify-between">
                    <div class="border-b border-pink-100/50 pb-4 mb-6">
                        <h3 class="text-xs font-black text-rose-900 uppercase tracking-widest">Hourly Peak Traffic</h3>
                        <p class="text-[11px] text-stone-500 mt-0.5">Visualizing customer flow during business operations.</p>
                    </div>
                    
                    <!-- Monochromatic Rose Gradient Bar Charts -->
                    <div class="h-48 flex items-end justify-between px-2 pt-2">
                        <div class="flex flex-col items-center gap-2 w-full max-w-[45px]">
                            <div class="w-full bg-pink-200/80 rounded-t-xl transition-all duration-300 hover:bg-pink-400" style="height: 35%"></div>
                            <span class="text-[10px] font-bold text-stone-400">8 AM</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 w-full max-w-[45px]">
                            <div class="w-full bg-pink-300/80 rounded-t-xl transition-all duration-300 hover:bg-pink-400" style="height: 60%"></div>
                            <span class="text-[10px] font-bold text-stone-400">10 AM</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 w-full max-w-[45px]">
                            <div class="w-full bg-gradient-to-t from-rose-400/90 to-pink-500 rounded-t-xl shadow-xs shadow-pink-200" style="height: 95%"></div>
                            <span class="text-[10px] font-bold text-pink-600">12 PM</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 w-full max-w-[45px]">
                            <div class="w-full bg-pink-300/80 rounded-t-xl transition-all duration-300 hover:bg-pink-400" style="height: 50%"></div>
                            <span class="text-[10px] font-bold text-stone-400">2 PM</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 w-full max-w-[45px]">
                            <div class="w-full bg-pink-400/80 rounded-t-xl transition-all duration-300 hover:bg-rose-400" style="height: 80%"></div>
                            <span class="text-[10px] font-bold text-stone-400">4 PM</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 w-full max-w-[45px]">
                            <div class="w-full bg-pink-200/80 rounded-t-xl transition-all duration-300 hover:bg-pink-400" style="height: 40%"></div>
                            <span class="text-[10px] font-bold text-stone-400">6 PM</span>
                        </div>
                    </div>
                </div>

                <!-- Product Leaderboard Box -->
                <div class="bg-gradient-to-b from-white via-white to-pink-50/40 p-6 rounded-2xl border border-pink-200/60 shadow-xs">
                    <div class="border-b border-pink-100/50 pb-4 mb-5">
                        <h3 class="text-xs font-black text-rose-900 uppercase tracking-widest">Top Selling Catalog</h3>
                        <p class="text-[11px] text-stone-500 mt-0.5">Most demanded items from the kitchen menu.</p>
                    </div>

                    <div class="space-y-3.5">
                        <!-- Row 1 -->
                        <div class="flex items-center justify-between p-2 rounded-xl hover:bg-pink-50/50 transition duration-150">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-pink-100/60 border border-pink-200 flex items-center justify-center text-sm">☕</div>
                                <div>
                                    <h4 class="text-xs font-bold text-stone-700">Spanish Latte</h4>
                                    <span class="text-[10px] font-semibold text-rose-700/60">54 tickets issued</span>
                                </div>
                            </div>
                            <span class="text-xs font-black text-stone-800">₱140</span>
                        </div>
                        <!-- Row 2 -->
                        <div class="flex items-center justify-between p-2 rounded-xl hover:bg-pink-50/50 transition duration-150">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-pink-100/60 border border-pink-200 flex items-center justify-center text-sm">🥐</div>
                                <div>
                                    <h4 class="text-xs font-bold text-stone-700">Butter Croissant</h4>
                                    <span class="text-[10px] font-semibold text-rose-700/60">32 tickets issued</span>
                                </div>
                            </div>
                            <span class="text-xs font-black text-stone-800">₱95</span>
                        </div>
                        <!-- Row 3 -->
                        <div class="flex items-center justify-between p-2 rounded-xl hover:bg-pink-50/50 transition duration-150">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-pink-100/60 border border-pink-200 flex items-center justify-center text-sm">🍓</div>
                                <div>
                                    <h4 class="text-xs font-bold text-stone-700">Strawberry Matcha</h4>
                                    <span class="text-[10px] font-semibold text-rose-700/60">28 tickets issued</span>
                                </div>
                            </div>
                            <span class="text-xs font-black text-stone-800">₱160</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- SECTION 3: MANAGEMENT DETAILED TIMELINE    -->
            <!-- ========================================== -->
            <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-pink-200/60 shadow-xs overflow-hidden">
                <div class="p-6 border-b border-pink-100/50">
                    <h3 class="text-xs font-black text-rose-900 uppercase tracking-widest">Upcoming Venue Reservations</h3>
                    <p class="text-xs text-stone-500 mt-0.5">Track private catering schedules and upcoming hall leases.</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-pink-50/60 border-b border-pink-100/50 text-[10px] uppercase tracking-widest font-black text-rose-800/80">
                                <th class="p-4 pl-6">Client Identity / Booking Name</th>
                                <th class="p-4">Event Date Schedule</th>
                                <th class="p-4">Classification</th>
                                <th class="p-4">Ledger Status</th>
                                <th class="p-4 pr-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-pink-100/30 text-xs text-stone-700 font-medium">
                            <tr class="hover:bg-pink-50/30 transition">
                                <td class="p-4 pl-6 font-bold text-stone-800">Maria Santos (Birthday Bash)</td>
                                <td class="p-4 text-stone-500 font-medium">June 24, 2026 • 2:00 PM</td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 bg-rose-100/70 text-rose-700 font-bold rounded-lg text-[10px] border border-rose-200/40">Private Venue Lease</span>
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 font-bold rounded-lg text-[10px] border border-emerald-100">Fully Paid</span>
                                </td>
                                <td class="p-4 pr-6 text-right">
                                    <button class="text-[11px] font-bold text-pink-600 hover:text-pink-700 transition underline decoration-dotted">Details</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-pink-50/30 transition">
                                <td class="p-4 pl-6 font-bold text-stone-800">Tech Startup Lipa Meetup</td>
                                <td class="p-4 text-stone-500 font-medium">June 28, 2026 • 6:00 PM</td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 bg-pink-100 text-pink-700 font-bold rounded-lg text-[10px] border border-pink-200/50">Co-Working Event</span>
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-700 font-bold rounded-lg text-[10px] border border-amber-100">50% Downpayment</span>
                                </td>
                                <td class="p-4 pr-6 text-right">
                                    <button class="text-[11px] font-bold text-pink-600 hover:text-pink-700 transition underline decoration-dotted">Details</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>