<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    ticket: { type: Object, required: true },
    techniciens: { type: Array, default: () => [] },
})

const user = computed(() => usePage().props.auth.user)

const commentText = ref('')
const commentInternal = ref(false)
const commentErrors = ref({})
const commentProcessing = ref(false)

const evaluationNote = ref(5)
const evaluationComment = ref('')
const evaluationErrors = ref({})
const evaluationProcessing = ref(false)
const showEvaluationForm = ref(false)

const selectedTechnicien = ref(props.ticket.technicien?.id?.toString() || '')
const assignProcessing = ref(false)

const selectedStatus = ref('')
const statusUpdateProcessing = ref(false)

const statusBadgeClass = (status) => ({
    'Ouvert': 'badge-ouvert',
    'En cours': 'badge-en-cours',
    'Rapport en rédaction': 'badge-rapport',
    'Résolu': 'badge-resolu',
    'Fermé': 'badge-ferme',
    'Annulé': 'badge-ferme',
}[status] || 'badge-ferme')

const priorityBadgeClass = (priority) => ({
    'Critique': 'badge-critique',
    'Haute': 'badge-haute',
    'Moyenne': 'badge-moyenne',
    'Basse': 'badge-basse',
}[priority] || 'badge-basse')

const progressionSteps = [
    { key: 'Ouvert', label: 'Ouvert' },
    { key: 'En cours', label: 'En cours' },
    { key: 'Rapport en rédaction', label: 'Rapport' },
    { key: 'Résolu', label: 'Résolu' },
    { key: 'Fermé', label: 'Fermé' },
]

const currentStepIndex = computed(() => {
    const current = props.ticket.statut
    const idx = progressionSteps.findIndex(s => s.key === current)
    return idx >= 0 ? idx : 0
})

function isStepComplete(index) {
    return index <= currentStepIndex.value
}

function isStepCurrent(index) {
    return index === currentStepIndex.value
}

function addComment() {
    if (!commentText.value.trim()) return
    commentProcessing.value = true
    commentErrors.value = {}
    router.post(route('tickets.comments.store', props.ticket.id), {
        text: commentText.value,
        internal: commentInternal.value,
    }, {
        preserveScroll: true,
        onSuccess: () => { commentText.value = ''; commentInternal.value = false },
        onError: (errs) => { commentErrors.value = errs },
        onFinish: () => { commentProcessing.value = false },
    })
}

function submitEvaluation() {
    evaluationProcessing.value = true
    evaluationErrors.value = {}
    router.post(route('tickets.evaluations.store', props.ticket.id), {
        note: evaluationNote.value,
        commentaire: evaluationComment.value,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            evaluationNote.value = 5
            evaluationComment.value = ''
            showEvaluationForm.value = false
        },
        onError: (errs) => { evaluationErrors.value = errs },
        onFinish: () => { evaluationProcessing.value = false },
    })
}

function updateStatus(statut) {
    if (!statut) return
    statusUpdateProcessing.value = true
    router.patch(route('tickets.update-status', props.ticket.id), {
        statut: statut,
    }, {
        preserveScroll: true,
        onSuccess: () => { selectedStatus.value = '' },
        onFinish: () => { statusUpdateProcessing.value = false },
    })
}

function assignTechnicien() {
    if (!selectedTechnicien.value) return
    assignProcessing.value = true
    router.post(route('tickets.assign', props.ticket.id), {
        technicien_id: selectedTechnicien.value,
    }, {
        preserveScroll: true,
        onFinish: () => { assignProcessing.value = false },
    })
}
</script>

<template>
    <Head :title="'Ticket ' + ticket.ref" />

    <AppLayout>
        <div class="space-y-7">
            <Link
                :href="route('tickets.index')"
                class="inline-flex items-center gap-2 text-muted hover:text-main text-[13px] font-medium transition-colors duration-200"
            >
                <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Retour aux tickets
            </Link>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="cyber-card rounded-[20px] p-6">
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            <span class="mono text-[13px] text-[#69b9ff] font-semibold tracking-wider">{{ ticket.ref }}</span>
                            <span :class="statusBadgeClass(ticket.statut)">{{ ticket.statut }}</span>
                            <span :class="priorityBadgeClass(ticket.priorite)">{{ ticket.priorite }}</span>
                            <span class="chip bg-[#1a2538] text-sub">{{ ticket.categorie }}</span>
                        </div>
                        <h3 class="section-title text-[20px] text-main mb-4">{{ ticket.titre }}</h3>
                        <p class="text-[14px] text-sub leading-relaxed whitespace-pre-line">{{ ticket.description }}</p>
                        <div class="mt-4 pt-4 border-t border-subtle text-[12px] text-muted space-y-1">
                            <p>Créé le {{ ticket.created_at }}</p>
                            <p v-if="ticket.updated_at">Mis à jour le {{ ticket.updated_at }}</p>
                        </div>
                    </div>

                    <div class="cyber-card rounded-[20px] p-6">
                        <h4 class="section-title text-[16px] text-main mb-5">Progression</h4>
                        <div class="flex items-center justify-between gap-1">
                            <template v-for="(step, index) in progressionSteps" :key="step.key">
                                <div class="flex flex-col items-center gap-2 flex-1 min-w-0">
                                    <div
                                        class="w-[30px] h-[30px] rounded-full flex items-center justify-center text-[11px] font-bold shrink-0 transition-all duration-300"
                                        :class="isStepComplete(index)
                                            ? isStepCurrent(index)
                                                ? 'bg-[#1d82ff] text-white shadow-[0_0_12px_rgba(29,130,255,0.5)]'
                                                : 'bg-[#24e0b1] text-[#02101b]'
                                            : 'bg-[#1a2538] text-muted border border-subtle'"
                                    >
                                        <template v-if="isStepComplete(index) && !isStepCurrent(index)">
                                            <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        </template>
                                        <template v-else>{{ index + 1 }}</template>
                                    </div>
                                    <span
                                        class="text-[10px] text-center leading-tight font-semibold"
                                        :class="isStepCurrent(index) ? 'text-main' : isStepComplete(index) ? 'text-[#24e0b1]' : 'text-muted'"
                                    >{{ step.label }}</span>
                                </div>
                                <div
                                    v-if="index < progressionSteps.length - 1"
                                    class="h-[2px] flex-1 min-w-[20px] rounded-full transition-all duration-300"
                                    :class="isStepComplete(index + 1) ? 'bg-[#24e0b1]' : 'bg-[#1d3658]'"
                                />
                            </template>
                        </div>
                    </div>

                    <div v-if="ticket.history && ticket.history.length > 0" class="cyber-card rounded-[20px] p-6">
                        <h4 class="section-title text-[16px] text-main mb-5">Historique</h4>
                        <div class="space-y-0">
                            <div
                                v-for="(entry, i) in ticket.history"
                                :key="entry.id"
                                class="relative pl-6 pb-5 last:pb-0"
                                :class="i < ticket.history.length - 1 ? 'border-l-2 border-subtle' : ''"
                            >
                                <div class="absolute left-[-5px] top-0 w-[8px] h-[8px] rounded-full bg-[#1d82ff] border-2 border-[#0b1627]" />
                                <div>
                                    <p class="text-[13px] text-main font-medium">{{ entry.action }}</p>
                                    <p class="text-[12px] text-muted mt-0.5">
                                        Par <span class="text-[#69b9ff] font-medium">{{ entry.user?.name }}</span>
                                        &mdash;
                                        <span class="text-muted">{{ entry.created_at }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="ticket.report" class="cyber-card rounded-[20px] p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="section-title text-[16px] text-main">Rapport d'intervention</h4>
                            <a :href="route('reports.download', ticket.id)"
                                class="rounded-xl border border-[#24e0b1] bg-[#24e0b1]/10 text-[#24e0b1] px-4 py-2 text-[12px] font-semibold hover:bg-[#24e0b1]/20 transition-colors inline-flex items-center gap-1.5">
                                <svg class="w-[14px] h-[14px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                PDF
                            </a>
                        </div>
                        <div class="space-y-4 text-[14px] text-sub leading-relaxed">
                            <div>
                                <p class="text-[11px] text-[#6ea6cc] uppercase tracking-wider mb-1">Travaux effectués</p>
                                <p class="whitespace-pre-line">{{ ticket.report.travaux }}</p>
                            </div>
                            <div class="grid sm:grid-cols-3 gap-3">
                                <div class="bg-[#0d1d30] border border-[#1c3a52] rounded-[14px] px-4 py-3">
                                    <p class="text-[11px] text-[#6fa2c6] uppercase">Durée</p>
                                    <p class="text-[14px] text-[#dceeff] font-semibold mt-1">{{ ticket.report.duree_heures }}h{{ ticket.report.duree_minutes }}</p>
                                </div>
                                <div class="bg-[#0d1d30] border border-[#1c3a52] rounded-[14px] px-4 py-3">
                                    <p class="text-[11px] text-[#6fa2c6] uppercase">Catégorie</p>
                                    <p class="text-[14px] text-[#dceeff] font-semibold mt-1">{{ ticket.categorie }}</p>
                                </div>
                                <div class="bg-[#0d1d30] border border-[#1c3a52] rounded-[14px] px-4 py-3">
                                    <p class="text-[11px] text-[#6fa2c6] uppercase">Signé par</p>
                                    <p class="text-[14px] text-[#dceeff] font-semibold mt-1">{{ ticket.report.signed_by }}</p>
                                </div>
                            </div>
                            <div v-if="ticket.report.materiel && ticket.report.materiel !== '-'">
                                <p class="text-[11px] text-[#6ea6cc] uppercase tracking-wider mb-1">Matériel utilisé</p>
                                <p>{{ ticket.report.materiel }}</p>
                            </div>
                            <div v-if="ticket.report.observations">
                                <p class="text-[11px] text-[#6ea6cc] uppercase tracking-wider mb-1">Observations</p>
                                <p class="whitespace-pre-line">{{ ticket.report.observations }}</p>
                            </div>
                            <div v-if="ticket.report.recommandations">
                                <p class="text-[11px] text-[#6ea6cc] uppercase tracking-wider mb-1">Recommandations</p>
                                <p class="whitespace-pre-line">{{ ticket.report.recommandations }}</p>
                            </div>
                        </div>
                        <p class="mt-4 text-[12px] text-muted border-t border-subtle pt-3">Rapport soumis le {{ ticket.report.created_at }}</p>
                    </div>

                    <div v-if="ticket.evaluation" class="cyber-card rounded-[20px] p-6">
                        <h4 class="section-title text-[16px] text-main mb-4">Évaluation</h4>
                        <div class="flex items-center gap-1 mb-3">
                            <svg v-for="s in 5" :key="s" class="w-5 h-5" :class="s <= ticket.evaluation.note ? 'text-[#ffcc3a]' : 'text-[#2a3a55]'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            <span class="ml-2 text-[14px] font-semibold text-main">{{ ticket.evaluation.note }}/5</span>
                        </div>
                        <p v-if="ticket.evaluation.commentaire" class="text-[13px] text-sub whitespace-pre-line">{{ ticket.evaluation.commentaire }}</p>
                    </div>

                    <div class="cyber-card rounded-[20px] p-6">
                        <h4 class="section-title text-[16px] text-main mb-2">Commentaires</h4>
                        <p class="text-[11px] text-muted mb-5">Publics (client + tech) et internes (tech/admin uniquement)</p>

                        <div v-if="ticket.comments && ticket.comments.length > 0" class="space-y-4 mb-6">
                            <div v-for="comment in ticket.comments" :key="comment.id"
                                class="flex gap-3"
                                :class="{ 'opacity-70': comment.internal }">
                                <div class="w-[34px] h-[34px] rounded-full bg-[#1a2f47] border border-subtle flex items-center justify-center text-[13px] font-bold text-[#69b9ff] font-['Space_Grotesk'] shrink-0">
                                    {{ comment.user?.name?.charAt(0)?.toUpperCase() || '?' }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[13px] font-semibold text-main">{{ comment.user?.name }}</span>
                                        <span v-if="comment.internal" class="text-[10px] px-2 py-0.5 rounded-full bg-[#2a243a] text-[#b68cff] font-semibold uppercase tracking-wider">Interne</span>
                                        <span class="text-[11px] text-muted">{{ comment.created_at }}</span>
                                    </div>
                                    <p class="text-[13px] text-sub whitespace-pre-line">{{ comment.text }}</p>
                                </div>
                            </div>
                            <div v-if="ticket.comments.length === 0" class="text-[13px] text-muted py-4">Aucun commentaire pour le moment.</div>
                        </div>

                        <form @submit.prevent="addComment" class="space-y-3 border-t border-subtle pt-5">
                            <textarea v-model="commentText" rows="3" class="field w-full resize-y" placeholder="Ajouter un commentaire..."></textarea>
                            <p v-if="commentErrors.text" class="text-[#ff5c7a] text-[12px]">{{ commentErrors.text }}</p>
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <label v-if="user?.role === 'technicien' || user?.role === 'admin'" class="flex items-center gap-2 cursor-pointer text-[12px] text-sub">
                                    <input type="checkbox" v-model="commentInternal" class="rounded border-subtle bg-[#0b1729] text-[#2ea8ff] focus:ring-[#2ea8ff]" />
                                    Note interne (tech/admin uniquement)
                                </label>
                                <div v-else />
                                <button type="submit" :disabled="!commentText.trim() || commentProcessing"
                                    class="btn-cyber" :class="{ 'opacity-50 pointer-events-none': !commentText.trim() || commentProcessing }">
                                    <svg v-if="commentProcessing" class="w-[15px] h-[15px] mr-1.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    Envoyer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="cyber-card rounded-[20px] p-5">
                        <h4 class="text-[11px] uppercase tracking-[0.15em] text-muted font-semibold mb-4">Client</h4>
                        <p class="text-[14px] font-semibold text-main">{{ ticket.client?.name }}</p>
                        <p class="text-[12px] text-muted mt-0.5">{{ ticket.client?.email }}</p>
                    </div>

                    <div class="cyber-card rounded-[20px] p-5">
                        <h4 class="text-[11px] uppercase tracking-[0.15em] text-muted font-semibold mb-4">Technicien</h4>
                        <p v-if="ticket.technicien" class="text-[14px] font-semibold text-main">{{ ticket.technicien.name }}</p>
                        <p v-else class="text-[13px] text-muted">Non assigné</p>
                    </div>

                    <div class="cyber-card rounded-[20px] p-5">
                        <h4 class="text-[11px] uppercase tracking-[0.15em] text-muted font-semibold mb-4">Échéance</h4>
                        <div class="flex items-center gap-2">
                            <svg class="w-[15px] h-[15px] text-muted" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="text-[13px] text-main font-medium">{{ ticket.created_at }}</span>
                        </div>
                    </div>

                    <div v-if="ticket.report" class="cyber-card rounded-[20px] p-5">
                        <h4 class="text-[11px] uppercase tracking-[0.15em] text-muted font-semibold mb-4">Rapport</h4>
                        <p class="text-[13px] text-[#24e0b1] font-semibold">✓ Rapport disponible</p>
                        <p class="text-[11px] text-muted mt-1">{{ ticket.report.signed_by }} &bull; {{ ticket.report.created_at }}</p>
                    </div>

                    <div v-if="ticket.report && !ticket.evaluation && user?.role === 'client'" class="cyber-card rounded-[20px] p-5">
                        <h4 class="text-[11px] uppercase tracking-[0.15em] text-muted font-semibold mb-4">Évaluation</h4>
                        <button
                            @click="showEvaluationForm = !showEvaluationForm"
                            class="btn-cyber w-full"
                        >
                            <svg class="w-[15px] h-[15px] mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                            Évaluer l'intervention
                        </button>
                        <div v-if="showEvaluationForm" class="mt-4 border-t border-subtle pt-4 space-y-4">
                            <div>
                                <label class="label">Note</label>
                                <div class="flex items-center gap-1 mt-1.5">
                                    <button v-for="s in 5" :key="s" type="button" @click="evaluationNote = s" class="focus:outline-none transition-transform hover:scale-110">
                                        <svg class="w-[32px] h-[32px]" :class="s <= evaluationNote ? 'text-[#ffcc3a]' : 'text-[#2a3a55]'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    </button>
                                </div>
                                <p v-if="evaluationErrors.note" class="text-[#ff5c7a] text-[12px] mt-1.5">{{ evaluationErrors.note }}</p>
                            </div>
                            <div>
                                <label for="eval_comment" class="label">Commentaire</label>
                                <textarea
                                    id="eval_comment"
                                    v-model="evaluationComment"
                                    rows="3"
                                    class="field w-full resize-y"
                                    placeholder="Partagez votre expérience..."
                                ></textarea>
                                <p v-if="evaluationErrors.commentaire" class="text-[#ff5c7a] text-[12px] mt-1.5">{{ evaluationErrors.commentaire }}</p>
                            </div>
                            <button
                                @click="submitEvaluation"
                                :disabled="evaluationProcessing"
                                class="btn-cyber w-full"
                            >
                                {{ evaluationProcessing ? 'Envoi...' : 'Envoyer l\'évaluation' }}
                            </button>
                        </div>
                    </div>

                    <div v-if="ticket.piece_jointe" class="cyber-card rounded-[20px] p-5">
                        <h4 class="text-[11px] uppercase tracking-[0.15em] text-muted font-semibold mb-4">Pièce jointe</h4>
                        <div class="rounded-[16px] border border-subtle bg-body p-4">
                            <p class="text-[12px] text-sub uppercase tracking-wide mb-1">Fichier fourni</p>
                            <p class="text-[15px] font-semibold text-main mt-1">{{ ticket.piece_jointe.split('/').pop() }}</p>
                            <a :href="'/storage/' + ticket.piece_jointe" target="_blank"
                                class="mt-3 inline-flex items-center gap-2 rounded-xl border border-[#24e0b1] bg-[#24e0b1]/10 text-[#24e0b1] px-4 py-2 text-[13px] font-semibold hover:bg-[#24e0b1]/20 transition-colors">
                                <svg class="w-[15px] h-[15px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Télécharger
                            </a>
                        </div>
                    </div>

                    <div v-if="user?.role === 'admin'" class="cyber-card rounded-[20px] p-5 space-y-4">
                        <h4 class="text-[11px] uppercase tracking-[0.15em] text-muted font-semibold mb-4">Assignation</h4>
                        <div class="space-y-3">
                            <select v-model="selectedTechnicien" class="field w-full">
                                <option value="">Sélectionner un technicien</option>
                                <option v-for="tech in techniciens" :key="tech.id" :value="tech.id.toString()">{{ tech.name }}</option>
                            </select>
                            <button @click="assignTechnicien" :disabled="!selectedTechnicien || assignProcessing"
                                class="btn-cyber w-full" :class="{ 'opacity-50 pointer-events-none': !selectedTechnicien || assignProcessing }">
                                {{ assignProcessing ? 'Assignation...' : 'Assigner' }}
                            </button>
                        </div>
                        <div class="border-t border-subtle pt-4">
                            <h4 class="text-[11px] uppercase tracking-[0.15em] text-muted font-semibold mb-3">Changer le statut</h4>
                            <div class="grid grid-cols-2 gap-2">
                                <button v-for="s in ['Ouvert','En cours','Rapport en rédaction','Résolu','Fermé', 'Annulé']" :key="s"
                                    @click="updateStatus(s)"
                                    :disabled="statusUpdateProcessing || ticket.statut === s"
                                    class="rounded-xl text-[12px] font-semibold py-2 px-3 transition-colors border"
                                    :class="ticket.statut === s
                                        ? 'border-[#2ea8ff] bg-[#2ea8ff]/15 text-[#2ea8ff]'
                                        : 'border-subtle text-muted hover:border-[#2b8ed3] hover:text-main'"
                                >{{ s }}</button>
                            </div>
                        </div>
                    </div>

                    <div v-if="user?.role === 'client' && ticket.statut === 'Ouvert'" class="cyber-card rounded-[20px] p-5">
                        <h4 class="text-[11px] uppercase tracking-[0.15em] text-muted font-semibold mb-4">Actions</h4>
                        <button
                            @click="updateStatus('Annulé')"
                            :disabled="statusUpdateProcessing"
                            class="rounded-xl text-[12px] font-semibold py-2 px-3 transition-colors border border-[#ff5c7a] bg-[#ff5c7a]/15 text-[#ff5c7a] hover:bg-[#ff5c7a]/25 w-full"
                        >Annuler le ticket</button>
                    </div>

                    <div v-if="user?.role === 'technicien'" class="cyber-card rounded-[20px] p-5 space-y-3">
                        <h4 class="text-[11px] uppercase tracking-[0.15em] text-muted font-semibold mb-4">Actions technicien</h4>

                        <div>
                            <h4 class="text-[11px] uppercase tracking-[0.15em] text-muted font-semibold mb-3">Changer le statut</h4>
                            <div class="grid grid-cols-2 gap-2">
                                <button v-for="s in ['Ouvert','En cours','Rapport en rédaction','Résolu','Fermé']" :key="s"
                                    @click="updateStatus(s)"
                                    :disabled="statusUpdateProcessing || ticket.statut === s"
                                    class="rounded-xl text-[12px] font-semibold py-2 px-3 transition-colors border"
                                    :class="ticket.statut === s
                                        ? 'border-[#2ea8ff] bg-[#2ea8ff]/15 text-[#2ea8ff]'
                                        : 'border-subtle text-muted hover:border-[#2b8ed3] hover:text-main'"
                                >{{ s }}</button>
                            </div>
                        </div>

                        <div class="border-t border-subtle pt-3">
                            <Link
                                :href="route('tickets.report.create', ticket.id)"
                                class="btn-cyber w-full"
                            >
                                <svg class="w-[15px] h-[15px] mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                Rédiger le rapport
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
