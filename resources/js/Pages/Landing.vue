<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({ canLogin: Boolean, canRegister: Boolean });
</script>

<template>
    <Head title="ATECH Cybersécurité" />

    <div class="relative min-h-screen bg-body text-main overflow-hidden g-grid dark">
        <!-- Fond décoratif -->
        <div class="absolute -top-24 right-1/4 w-[500px] h-[500px] rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(0,195,255,0.05) 0%, transparent 70%);" />
        <div class="absolute bottom-[-120px] left-10 w-[500px] h-[500px] rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(36,226,179,0.03) 0%, transparent 70%);" />

        <!-- Header -->
        <div class="sticky top-0 z-50 border-b border-subtle/50 glass">
            <div class="mx-auto max-w-7xl flex items-center justify-between px-6 py-3.5">
                <div class="flex items-center gap-3">
                    <img src="https://atech-cybersecurite.com/assets/img/logo-mark.png" alt="ATECH" class="h-9 w-9 object-contain" />
                    <span class="section-title text-lg text-main tracking-tight">ATECH</span>
                </div>
                <div class="flex items-center gap-2.5">
                    <Link v-if="canLogin" :href="route('login')" class="rounded-xl border px-4 py-2 text-[13px] font-semibold transition-all duration-200 border-subtle text-sub hover:border-subtle bg-transparent">
                        Se connecter
                    </Link>
                    <Link v-if="canRegister" :href="route('register')" class="btn-cyber text-[13px] px-4 py-2">
                        S'inscrire
                    </Link>
                </div>
            </div>
        </div>

        <!-- Hero Section -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 pt-20 pb-16 grid grid-cols-1 lg:grid-cols-[1.05fr_0.95fr] gap-8 sm:gap-10 items-center">
            <div class="hidden lg:block">
                <div class="flex items-center gap-3 mb-5">
                    <img src="https://atech-cybersecurite.com/assets/img/logo-mark.png" alt="ATECH" class="h-11 w-11 object-contain" />
                    <div class="leading-tight">
                        <p class="section-title text-xl text-main">ATECH Cybersécurité</p>
                        <p class="text-[11px] mono tracking-[0.16em] text-sub uppercase">SOC &bull; Intervention &bull; Supervision</p>
                    </div>
                </div>

                <h1 class="section-title text-[32px] sm:text-[34px] leading-[1.15] text-main mb-5">
                    Pilotage des interventions techniques, en un seul espace.
                </h1>

                <p class="text-[14px] sm:text-[15px] text-sub leading-relaxed mb-8 max-w-lg">
                    Centralisez vos tickets d'intervention, suivez l'avancement en temps réel
                    et collaborez avec vos techniciens depuis une interface conçue pour les
                    équipes de cybersécurité.
                </p>
            </div>

            <!-- Login Card -->
            <div>
                <div class="cyber-card rounded-[26px] p-7 sm:p-9">
                    <p class="text-[11px] mono tracking-[0.22em] text-[#57c8ff] uppercase mb-2">ATECH &bull; PLATEFORME</p>
                    <h2 class="section-title text-[22px] text-main leading-tight mb-3">Accédez à votre espace</h2>
                    <p class="text-sm text-sub leading-relaxed mb-6">Plateforme de gestion des interventions de cybersécurité.</p>
                    <Link :href="route('login')" class="btn-cyber w-full py-3 text-[14px] text-center">Accéder à la plateforme</Link>
                </div>
            </div>
        </div>

        <!-- Pourquoi choisir ATECH -->
        <section class="py-16 sm:py-24 bg-[#060b14]/50">
            <div class="mx-auto max-w-7xl px-6">
                <h2 class="section-title text-3xl sm:text-4xl text-center mb-12 sm:mb-16">Pourquoi choisir ATECH ?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    <div class="cyber-card p-6 sm:p-8 hover:border-accent/30 transition-all">
                        <h3 class="font-bold text-lg mb-3 text-accent">Réactivité 24/7</h3>
                        <p class="text-sm text-sub leading-relaxed">Une équipe SOC disponible en continu pour vos incidents critiques.</p>
                    </div>
                    <div class="cyber-card p-6 sm:p-8 hover:border-accent/30 transition-all">
                        <h3 class="font-bold text-lg mb-3 text-accent">Expertise locale</h3>
                        <p class="text-sm text-sub leading-relaxed">Une équipe certifiée, basée à Dakar, qui comprend les enjeux du marché sénégalais.</p>
                    </div>
                    <div class="cyber-card p-6 sm:p-8 hover:border-accent/30 transition-all">
                        <h3 class="font-bold text-lg mb-3 text-accent">Suivi transparent</h3>
                        <p class="text-sm text-sub leading-relaxed">Tableaux de bord en temps réel et rapports détaillés pour chaque intervention.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="py-16 sm:py-20">
            <div class="mx-auto max-w-7xl px-6 grid grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-12 text-center">
                <div class="space-y-1"><div class="text-3xl sm:text-4xl font-bold text-accent">98%</div><div class="text-[10px] sm:text-xs text-sub uppercase tracking-wider">taux de résolution</div></div>
                <div class="space-y-1"><div class="text-3xl sm:text-4xl font-bold text-accent">< 2h</div><div class="text-[10px] sm:text-xs text-sub uppercase tracking-wider">temps de réponse</div></div>
                <div class="space-y-1"><div class="text-3xl sm:text-4xl font-bold text-accent">24/7</div><div class="text-[10px] sm:text-xs text-sub uppercase tracking-wider">disponibilité</div></div>
                <div class="space-y-1"><div class="text-3xl sm:text-4xl font-bold text-accent">100%</div><div class="text-[10px] sm:text-xs text-sub uppercase tracking-wider">suivi en temps réel</div></div>
            </div>
        </section>

        <!-- À propos -->
        <section class="py-16 sm:py-24 border-t border-subtle">
            <div class="mx-auto max-w-3xl px-6 text-center">
                <h2 class="section-title text-3xl sm:text-4xl mb-8">À propos d'ATECH</h2>
                <p class="text-sub mb-10 leading-relaxed text-lg">Entreprise de cybersécurité basée à Dakar, spécialisée dans l'intervention technique, le SOC et la supervision.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-sub">
                    <div class="bg-card p-4 rounded-xl border border-subtle">✓ Audit sécurité</div>
                    <div class="bg-card p-4 rounded-xl border border-subtle">✓ Intervention technique</div>
                    <div class="bg-card p-4 rounded-xl border border-subtle">✓ Supervision SOC</div>
                    <div class="bg-card p-4 rounded-xl border border-subtle">✓ Formation & sensibilisation</div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-[#0b1627] py-16 sm:py-20 border-t border-subtle">
            <div class="mx-auto max-w-7xl px-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">
                <div class="space-y-3"><img src="https://atech-cybersecurite.com/assets/img/logo-mark.png" class="h-10" /><p class="text-sm text-sub">ATECH - Cybersécurité</p><p class="text-xs text-muted">Dakar, Sénégal</p></div>
                <div class="space-y-3"><h4 class="font-bold text-main">Liens rapides</h4><ul class="space-y-2 text-sm text-sub"><li>Accueil</li><li>Se connecter</li><li>S'inscrire</li><li>À propos</li></ul></div>
                <div class="space-y-3"><h4 class="font-bold text-main">Services</h4><ul class="space-y-2 text-sm text-sub"><li>Gestion des tickets</li><li>Suivi opérationnel</li><li>Rapports</li></ul></div>
                <div class="space-y-3"><h4 class="font-bold text-main">Contact</h4><p class="text-sm text-sub">contact@atech.sn</p><p class="text-sm text-sub">+221 XX XXX XX XX</p></div>
            </div>
            <div class="text-center mt-16 pt-8 border-t border-subtle text-xs text-muted">© 2026 ATECH Cybersécurité. Tous droits réservés.</div>
        </footer>
    </div>
</template>
