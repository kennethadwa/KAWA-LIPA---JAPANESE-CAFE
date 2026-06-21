<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <!-- Tabbed Header Style Layout Indicators derived from image_362cf2.jpg -->
        <div class="mb-8 flex items-center space-x-6 border-b border-stone-100 pb-px">
            <span class="text-xl font-bold text-stone-900 border-b-2 border-rose-600 pb-3 relative z-10 select-none">
                Login
            </span>
        </div>

        <div v-if="status" class="mb-5 p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-xs font-semibold text-emerald-800">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <!-- Email Block Module Component -->
            <div class="relative group">
                <InputLabel for="email" value="Email / Username" class="text-[11px] font-bold uppercase tracking-widest text-stone-400 pl-1 mb-1.5" />

                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-stone-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </span>
                    <TextInput
                        id="email"
                        type="email"
                        class="block w-full pl-11 pr-4 py-3 rounded-full border-stone-200/80 bg-stone-50/40 placeholder-stone-400 text-sm text-stone-800 shadow-2xs focus:bg-white focus:border-rose-400 focus:ring-4 focus:ring-rose-100 transition duration-200"
                        v-model="form.email"
                        required
                        autofocus
                        placeholder="Enter email or phone number"
                        autocomplete="username"
                    />
                </div>

                <InputError class="mt-1.5 text-xs text-rose-600 pl-1" :message="form.errors.email" />
            </div>

            <!-- Password Block Module Component -->
            <div class="relative group">
                <InputLabel for="password" value="Password Secure Key" class="text-[11px] font-bold uppercase tracking-widest text-stone-400 pl-1 mb-1.5" />

                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-stone-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </span>
                    <TextInput
                        id="password"
                        type="password"
                        class="block w-full pl-11 pr-4 py-3 rounded-full border-stone-200/80 bg-stone-50/40 placeholder-stone-400 text-sm text-stone-800 shadow-2xs focus:bg-white focus:border-rose-400 focus:ring-4 focus:ring-rose-100 transition duration-200"
                        v-model="form.password"
                        required
                        placeholder="Enter password text string"
                        autocomplete="current-password"
                    />
                </div>

                <InputError class="mt-1.5 text-xs text-rose-600 pl-1" :message="form.errors.password" />
            </div>

            <!-- Form Interaction Utilities Section -->
            <div class="flex items-center justify-between pt-1 px-1">
                <label class="flex items-center cursor-pointer select-none group">
                    <Checkbox 
                        name="remember" 
                        v-model:checked="form.remember" 
                        class="rounded border-stone-300 text-rose-600 focus:ring-rose-500/20"
                    />
                    <span class="ms-2 text-xs font-semibold text-stone-500 group-hover:text-stone-700 transition">Remember me</span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-xs font-semibold text-rose-600/90 hover:text-rose-700 transition underline underline-offset-4 decoration-rose-200 hover:decoration-rose-500"
                >
                    Forgot password?
                </Link>
            </div>

            <!-- Primary Actions Integration Group -->
            <div class="pt-4">
                <PrimaryButton
                    class="w-full inline-flex items-center justify-center bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm tracking-wide px-6 py-3 rounded-full transition shadow-md shadow-rose-600/10 hover:shadow-lg hover:shadow-rose-600/20 active:scale-[0.99] focus:outline-none focus:ring-4 focus:ring-rose-500/20 disabled:opacity-50 disabled:cursor-not-allowed"
                    :class="{ 'opacity-50': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing" class="inline-block animate-pulse">Verifying...</span>
                    <span v-else>Login</span>
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>