<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <!-- Full Screen Viewport Wrapper - Soft Pink Tinted Ambient Background -->
    <div class="min-h-screen w-full flex items-center justify-center bg-rose-50/60 p-4 sm:p-6 lg:p-8 font-sans antialiased selection:bg-rose-200 relative overflow-hidden">
        
        <!-- ================= BACKGROUND ANIMATIONS ELEMENT LAYER ================= -->
        <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden">
            <!-- Slow Moving Pastel Pink Blob 1 -->
            <div class="absolute -top-20 -left-20 w-96 h-96 rounded-full bg-rose-200/50 mix-blend-multiply filter blur-3xl opacity-70 animate-blob-one"></div>
            <!-- Slow Moving Soft Rose Blob 2 -->
            <div class="absolute bottom-10 right-10 w-[30rem] h-[30rem] rounded-full bg-pink-100/60 mix-blend-multiply filter blur-3xl opacity-80 animate-blob-two"></div>
            <!-- Center subtle light flare pulse -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[40rem] h-[40rem] rounded-full bg-white/40 filter blur-2xl animate-[pulse_10s_infinite_ease-in-out]"></div>
        </div>
        <!-- ======================================================================= -->

        <!-- Main Floating UI Canvas Layout with an intentional heavy, rich dark shadow backdrop -->
        <div class="w-full max-w-5xl bg-white rounded-[2rem] shadow-[0_25px_60px_-15px_rgba(28,25,23,0.35)] border-2 border-rose-100/80 overflow-hidden grid lg:grid-cols-12 min-h-[600px] relative z-10">
            
            <!-- Left Workspace Form Segment -->
            <div class="col-span-12 lg:col-span-7 flex flex-col justify-between p-8 sm:p-12 lg:p-16 bg-white">
                
                <!-- Branding Header Area -->
                <div class="flex items-center justify-between w-full mb-8 lg:mb-0">
                    <Link href="/" class="flex items-center gap-3 group">
                        <!-- Custom Kawa Logo Image Asset Integration -->
                        <div class="h-14 w-14 overflow-hidden rounded-xl p-1 flex items-center justify-center group-hover:scale-105 transition duration-200">
                            <img 
                                src="/storage/images/logo_kawa.png" 
                                alt="Kawa Logo" 
                                class="w-full h-full object-contain"
                                @error="e => e.target.src = '/images/logo_kawa.png'"
                            />
                        </div>
                        <span class="font-extrabold tracking-tight text-stone-900 text-lg uppercase select-none">Kawa Lipa</span>
                    </Link>
                    
                    <div class="text-xs font-bold uppercase tracking-widest text-rose-500 bg-rose-50 px-3 py-1.5 rounded-full border border-rose-100">
                        Admin Access
                    </div>
                </div>

                <!-- Central Slotted Insertion Target -->
                <div class="w-full max-w-sm mx-auto lg:mx-0 my-auto py-4">
                    <slot />
                </div>

                <!-- Bottom Copyright Stamp -->
                <div class="text-left text-xs text-stone-400 font-medium mt-6 lg:mt-0">
                    &copy; {{ new Date().getFullYear() }} Kawa Lipa. All rights reserved.
                </div>
            </div>

            <!-- Right Segment Panel - Reimagined fully in White, Light Pink, Pastel Pink, and Rose Pink -->
            <div class="hidden lg:flex lg:col-span-5 bg-gradient-to-br from-rose-100/70 via-rose-50/50 to-white relative p-12 flex-col justify-between overflow-hidden border-l border-rose-100">
                
                <!-- Organic structural branding arcs matching image_362cf2.jpg styled with soft brand values -->
                <div class="absolute -right-12 -bottom-12 w-96 h-96 rounded-full bg-white/80 pointer-events-none scale-150 transform transition duration-500 shadow-xl shadow-rose-200/40"></div>
                <div class="absolute -right-6 -bottom-6 w-80 h-80 rounded-full bg-rose-200/40 pointer-events-none scale-120 transform transition duration-500"></div>
                <div class="absolute -right-2 -bottom-2 w-64 h-64 rounded-full bg-rose-300/30 pointer-events-none transform transition duration-500"></div>
                
                <!-- Subtle geometric overlay pattern mesh -->
                <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#e11d48_1px,transparent_1px)] [background-size:24px_24px]"></div>

                <!-- Center Graphic Focal Point Cluster Illustration -->
                <div class="relative z-10 flex flex-col items-center justify-center my-auto space-y-5 text-center">
                    <!-- Custom Branding Logo container wrapping logo_kawa.png with thick layout properties -->
                    <div class="w-24 h-24 rounded-3xl bg-white flex items-center justify-center border-2 border-rose-200/80 shadow-[8px_8px_0px_0px_rgba(225,29,72,0.15)] transform -rotate-3 hover:rotate-0 transition duration-300 overflow-hidden p-3 select-none">
                        <img 
                            src="/storage/images/logo_kawa.png" 
                            alt="Kawa Logo" 
                            class="w-full h-full object-contain filter drop-shadow-xs"
                            @error="e => e.target.src = '/images/logo_kawa.png'"
                        />
                    </div>
                    <div class="max-w-xs">
                        <h3 class="text-xl font-extrabold text-stone-900 tracking-tight">KAWA LIPA</h3>
                        <p class="text-stone-500 text-xs mt-2 leading-relaxed font-medium">
                            Where Japanese inspired flavors meet modern comfort.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</template>

<!-- CSS Custom Keyframes moved outside <template> -->
<style scoped>
@keyframes driftSmoothOne {
    0%, 100% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(40px, -60px) scale(1.15); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
}
@keyframes driftSmoothTwo {
    0%, 100% { transform: translate(0px, 0px) scale(1); }
    50% { transform: translate(-50px, 40px) scale(1.1); }
}
.animate-blob-one {
    animation: driftSmoothOne 22s infinite alternate ease-in-out;
}
.animate-blob-two {
    animation: driftSmoothTwo 28s infinite alternate ease-in-out;
}
</style>