<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

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
const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            showPassword.value = false
            form.reset('password')
        }
    });
};
</script>

<template>
    <Head title="Log in" />

    <div
        class="login-shell relative min-h-screen w-full overflow-hidden bg-slate-950 px-4 py-10 text-white sm:px-6 lg:px-8"
    >
        <div class="absolute inset-0 bg-slate-950/45"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950/55 via-slate-900/25 to-cyan-900/20"></div>
        <div class="absolute inset-0 bg-[url('/img/pisaybg.png')] bg-cover bg-center bg-no-repeat login-zoom"></div>
        <div class="absolute -top-24 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-cyan-400/20 blur-3xl"></div>
        <div class="absolute -bottom-24 right-0 h-80 w-80 rounded-full bg-sky-500/15 blur-3xl"></div>

        <div class="relative z-10 flex min-h-[calc(100vh-5rem)] items-center justify-center">
            <div class="w-full max-w-md">
                <div class="overflow-hidden rounded-[2rem] border border-white/20 bg-white/90 shadow-[0_30px_80px_rgba(2,6,23,0.45)] backdrop-blur-xl">
                    <div class="h-1.5 bg-gradient-to-r from-sky-500 via-cyan-400 to-emerald-400"></div>

                    <div class="space-y-6 px-6 py-8 sm:px-10 sm:py-10">
                        <div class="space-y-2 text-center">
                            <h1 class="text-xl font-black uppercase tracking-[0.18em] text-slate-900 sm:text-2xl">
                                PSHS-CRC ALP MANAGEMENT PORTAL
                            </h1>
                        </div>

                        <div
                            v-if="status"
                            class="rounded-2xl border border-emerald-200 bg-emerald-50/90 px-4 py-3 text-center text-sm font-medium text-emerald-700"
                        >
                            {{ status }}
                        </div>

                        <form @submit.prevent="submit" class="space-y-5">
                            <div class="space-y-2">
                                <InputLabel
                                    for="email"
                                    value="Email"
                                    class="!text-xs !font-semibold !uppercase !tracking-[0.16em] !text-slate-600"
                                />
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full rounded-xl border border-slate-200 bg-white/95 px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/15"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div class="space-y-2">
                                <InputLabel
                                    for="password"
                                    value="Password"
                                    class="!text-xs !font-semibold !uppercase !tracking-[0.16em] !text-slate-600"
                                />
                                <div class="relative">
                                    <TextInput
                                        id="password"
                                        :type="showPassword ? 'text' : 'password'"
                                        class="mt-1 block w-full rounded-xl border border-slate-200 bg-white/95 px-4 py-3 pr-12 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/15"
                                        v-model="form.password"
                                        required
                                        autocomplete="current-password"
                                    />
                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 top-1 flex items-center px-4 text-slate-500 transition hover:text-slate-700"
                                        :aria-label="showPassword ? 'Hide password' : 'Show password'"
                                    >
                                        <svg
                                            v-if="showPassword"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="h-5 w-5"
                                        >
                                            <path d="M13.73 9.87a3 3 0 1 1-4.26 4.26" />
                                            <path d="M21 12s-3.5-7-9-7-9 7-9 7 3.5 7 9 7" />
                                            <path d="M3 3l18 18" />
                                        </svg>
                                        <svg
                                            v-else
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            class="h-5 w-5"
                                        >
                                            <path d="M2.1 12s3.5-7 9-7 9 7 9 7-3.5 7-9 7-9-7-9-7Z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </button>
                                </div>
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <label class="flex items-center">
                                    <Checkbox
                                        name="remember"
                                        v-model:checked="form.remember"
                                        class="h-4 w-4 rounded border-slate-300 text-cyan-600 shadow-sm focus:ring-cyan-500"
                                    />
                                    <span class="ms-2 text-sm font-medium text-slate-600">Remember me</span>
                                </label>

                                <!-- <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="text-sm text-indigo-600 hover:underline"
                                >
                                    Forgot password?
                                </Link> -->
                            </div>

                            <PrimaryButton
                                class="w-full justify-center !rounded-xl !border-0 !bg-gradient-to-r from-sky-600 via-cyan-600 to-emerald-600 !px-4 !py-3 !text-sm !font-semibold !uppercase !tracking-[0.18em] !text-white shadow-lg shadow-cyan-900/20 transition hover:shadow-xl hover:shadow-cyan-900/30"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Log in
                            </PrimaryButton>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.login-zoom {
    animation: loginZoom 24s ease-in-out infinite alternate;
    transform-origin: center;
}

@keyframes loginZoom {
    0% {
        transform: scale(1);
    }
    100% {
        transform: scale(1.12);
    }
}
</style>
