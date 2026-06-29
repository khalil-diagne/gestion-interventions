<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Inscription" />

    <div class="flex min-h-screen items-center justify-center bg-[#060b14] px-4 py-10 dark">
        <div class="w-full max-w-[480px]">
            <div class="cyber-card rounded-[26px] p-6 sm:p-9">
                <p class="text-[11px] mono tracking-[0.22em] text-[#57c8ff] uppercase mb-1">
                    ATECH &bull; ONBOARDING
                </p>

                <h1 class="section-title text-[26px] text-main leading-tight mb-1">
                    Créer un compte client
                </h1>

                <p class="text-sm text-sub mb-7">
                    Rejoignez la plateforme pour suivre vos interventions techniques.
                </p>

                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label for="name" class="label">Nom complet</label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="field w-full"
                            required
                            autofocus
                            autocomplete="name"
                        />
                        <p v-if="form.errors.name" class="mt-1.5 text-xs text-[#ff8b95]">{{ form.errors.name }}</p>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="label">Adresse email</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="field w-full"
                            required
                            autocomplete="username"
                        />
                        <p v-if="form.errors.email" class="mt-1.5 text-xs text-[#ff8b95]">{{ form.errors.email }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div>
                            <label for="password" class="label">Mot de passe</label>
                            <input
                                id="password"
                                v-model="form.password"
                                type="password"
                                class="field w-full"
                                required
                                autocomplete="new-password"
                            />
                            <p v-if="form.errors.password" class="mt-1.5 text-xs text-[#ff8b95]">{{ form.errors.password }}</p>
                        </div>
                        <div>
                            <label for="password_confirmation" class="label">Confirmer</label>
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                class="field w-full"
                                required
                                autocomplete="new-password"
                            />
                            <p v-if="form.errors.password_confirmation" class="mt-1.5 text-xs text-[#ff8b95]">{{ form.errors.password_confirmation }}</p>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="btn-cyber w-full py-3 text-[15px]"
                        :class="{ 'opacity-50 pointer-events-none': form.processing }"
                        :disabled="form.processing"
                    >
                        Créer mon compte
                    </button>
                </form>

                <p class="mt-5 text-center">
                    <Link
                        :href="route('login')"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-subtle bg-transparent px-5 py-2.5 text-sm font-semibold text-sub transition-all duration-200 hover:border-subtle hover:text-main"
                    >
                        &larr; Retour connexion
                    </Link>
                </p>
            </div>
        </div>
    </div>
</template>
