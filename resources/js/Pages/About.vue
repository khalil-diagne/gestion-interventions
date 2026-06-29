<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Footer from '@/Components/Footer.vue';
</script>

<template>
    <Head title="À propos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-main leading-tight">À propos d'ATECH</h2>
        </template>

        <div class="py-12 bg-body text-main">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-16">
                
                <!-- 1. HERO -->
                <section class="text-center">
                    <h1 class="section-title text-4xl mb-6">ATECH Cybersécurité — Qui sommes-nous ?</h1>
                    <p class="text-lg text-sub max-w-3xl mx-auto leading-relaxed">
                        Entreprise sénégalaise innovante, ATECH Cybersécurité s'engage à offrir une sécurité intégrée pour protéger les infrastructures numériques de ses clients. Notre mission est d'allier expertise technique et réactivité pour anticiper les menaces de demain.
                    </p>
                </section>

                <!-- 2. EXPERTISE -->
                <section>
                    <h2 class="section-title text-3xl mb-8 text-center">Nos domaines d'expertise</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="cyber-card p-6 text-center">
                            <div class="text-4xl mb-4">🔐</div>
                            <h3 class="font-bold text-lg mb-2">Cybersécurité</h3>
                            <p class="text-sm text-sub">Protection avancée de vos données et systèmes contre les intrusions.</p>
                        </div>
                        <div class="cyber-card p-6 text-center">
                            <div class="text-4xl mb-4">📷</div>
                            <h3 class="font-bold text-lg mb-2">Sécurité physique</h3>
                            <p class="text-sm text-sub">Surveillance et contrôle d'accès pour sécuriser vos locaux.</p>
                        </div>
                        <div class="cyber-card p-6 text-center">
                            <div class="text-4xl mb-4">🎓</div>
                            <h3 class="font-bold text-lg mb-2">Formation</h3>
                            <p class="text-sm text-sub">Renforcement des compétences de vos équipes face aux risques numériques.</p>
                        </div>
                        <div class="cyber-card p-6 text-center">
                            <div class="text-4xl mb-4">🖥️</div>
                            <h3 class="font-bold text-lg mb-2">Matériel de sécurité</h3>
                            <p class="text-sm text-sub">Fourniture et installation d'équipements technologiques robustes.</p>
                        </div>
                    </div>
                </section>

                <!-- 3. CONFIANCE -->
                <section>
                    <h2 class="section-title text-3xl mb-8 text-center">Pourquoi nous faire confiance ?</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-4xl mx-auto">
                        <div class="flex items-center gap-3 cyber-card p-4"><span>✅</span> Expertise reconnue</div>
                        <div class="flex items-center gap-3 cyber-card p-4"><span>✅</span> Partenaires technologiques de référence</div>
                        <div class="flex items-center gap-3 cyber-card p-4"><span>✅</span> Ancrage local, vision globale</div>
                        <div class="flex items-center gap-3 cyber-card p-4"><span>✅</span> Réactivité et disponibilité</div>
                    </div>
                </section>

                <!-- 4. PLATEFORME -->
                <section class="border-2 border-accent/50 bg-card/50 rounded-3xl p-8">
                    <h2 class="section-title text-3xl mb-2 text-center text-accent">Notre plateforme de gestion</h2>
                    <p class="text-center text-sub mb-8 font-mono">Une solution développée par ATECH pour ATECH</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="text-3xl mb-3 text-accent">⚡</div>
                            <h4 class="font-bold text-sm">Affectation automatique</h4>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl mb-3 text-accent">🔔</div>
                            <h4 class="font-bold text-sm">Alertes en temps réel</h4>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl mb-3 text-accent">📊</div>
                            <h4 class="font-bold text-sm">Tableaux de bord</h4>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl mb-3 text-accent">📄</div>
                            <h4 class="font-bold text-sm">Rapports PDF auto</h4>
                        </div>
                    </div>
                </section>

            </div>
        </div>

        <Footer />
    </AuthenticatedLayout>
</template>
