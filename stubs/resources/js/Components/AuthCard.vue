<script setup>
defineProps({
    title: { type: String, required: true },
    subtitle: { type: String, default: '' },
});
</script>

<template>
    <div class="min-h-screen bg-slate-950 text-slate-100 app-dark">
        <div class="mx-auto grid min-h-screen max-w-6xl grid-cols-1 lg:grid-cols-[0.9fr_1.1fr]">
            <aside class="hidden border-r border-white/10 p-10 lg:flex lg:flex-col lg:justify-between">
                <div>
                    <div class="mb-10 flex items-center gap-3">
                        <span class="grid h-11 w-11 place-items-center rounded-xl bg-cyan-300 font-black text-slate-950">S</span>
                        <div>
                            <p class="font-black tracking-[0]">Servicios Linea Once</p>
                            <p class="text-xs uppercase tracking-[0.2em] text-cyan-200">Starter Kit</p>
                        </div>
                    </div>
                    <h1 class="max-w-md text-5xl font-black leading-tight tracking-[0]">
                        Auth sólido para aplicaciones Laravel modernas.
                    </h1>
                </div>
                <p class="max-w-sm text-sm leading-6 text-slate-400">
                    Sesiones, verificación de email, recuperación de contraseña y 2FA TOTP sin Fortify.
                </p>
            </aside>

            <main class="flex items-center justify-center px-5 py-10">
                <div class="w-full max-w-md">
                    <div class="mb-8 lg:hidden">
                        <div class="mb-6 flex items-center gap-3">
                            <span class="grid h-10 w-10 place-items-center rounded-xl bg-cyan-300 font-black text-slate-950">S</span>
                            <span class="font-black">Servicios Linea Once</span>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/[0.04] p-6 shadow-2xl backdrop-blur md:p-8">
                        <h2 class="text-2xl font-black tracking-[0]">{{ title }}</h2>
                        <p v-if="subtitle" class="mt-2 text-sm leading-6 text-slate-400">{{ subtitle }}</p>
                        <div class="mt-7">
                            <slot />
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
