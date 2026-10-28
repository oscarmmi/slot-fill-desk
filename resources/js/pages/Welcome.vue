<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

type SlotResult = {
    id: number;
    source_text: string;
    status?: string;
    date: string | null;
    window: string | null;
    city: string | null;
    missing: string[];
};

const props = defineProps<{
    recentJobs: SlotResult[];
    result: SlotResult | null;
}>();

const form = useForm({ source_text: '' });

const useRequest = (sourceText: string) => {
    form.source_text = sourceText;
    document.querySelector<HTMLTextAreaElement>('#source_text')?.focus();
};

const submit = () => {
    form.post('/slot-jobs', {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const prettySlot = (value: string | null) => value ?? 'Not found';
</script>

<template>
    <Head title="Slot Fill Desk · Harbor Desk" />

    <div class="min-h-screen bg-[#f4f5f2] text-[#18211f]">
        <header class="border-b border-[#e3e7e2] bg-white">
            <div
                class="mx-auto flex max-w-[1440px] items-center justify-between px-5 py-4 sm:px-8 lg:px-12"
            >
                <a
                    href="/"
                    class="flex items-center gap-3"
                    aria-label="Slot Fill Desk home"
                >
                    <span
                        class="flex size-10 items-center justify-center rounded-xl bg-[#173d37] text-[#e6f2e8]"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            class="size-5"
                            aria-hidden="true"
                        >
                            <path
                                d="M4 7.5h16M7.5 4v7m9-7v7M5 12h14v8H5z"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <path
                                d="m9 16 2 2 4-4"
                                stroke="#a6d2a8"
                                stroke-width="1.7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </span>
                    <span>
                        <span class="block text-sm font-bold tracking-tight"
                            >Harbor Desk</span
                        >
                        <span
                            class="block text-[11px] font-medium tracking-[0.14em] text-[#75817d] uppercase"
                            >Slot Fill Desk</span
                        >
                    </span>
                </a>

                <div
                    class="flex items-center gap-2 rounded-full border border-[#dfe8df] bg-[#f5faf5] px-3 py-1.5 text-xs font-semibold text-[#32694d]"
                >
                    <span class="relative flex size-2">
                        <span
                            class="absolute inline-flex size-full animate-ping rounded-full bg-[#6fac7c] opacity-40"
                        />
                        <span
                            class="relative inline-flex size-2 rounded-full bg-[#5f9b6c]"
                        />
                    </span>
                    Desk ready
                </div>
            </div>
        </header>

        <main
            class="mx-auto grid max-w-[1440px] gap-8 px-5 py-8 sm:px-8 sm:py-10 lg:grid-cols-[minmax(0,1.55fr)_minmax(330px,0.85fr)] lg:gap-10 lg:px-12 lg:py-12"
        >
            <section>
                <div class="mb-7 max-w-2xl">
                    <div
                        class="mb-4 inline-flex items-center gap-2 rounded-full bg-[#e6eee7] px-3 py-1.5 text-[11px] font-bold tracking-[0.13em] text-[#426a4c] uppercase"
                    >
                        <span class="text-sm">✳</span> Intake workspace
                    </div>
                    <h1
                        class="text-3xl font-semibold tracking-[-0.045em] text-[#172421] sm:text-[42px] sm:leading-[1.08]"
                    >
                        Turn a request into a
                        <span class="text-[#4c8062]">ready-to-book</span> slot.
                    </h1>
                    <p
                        class="mt-4 max-w-xl text-[15px] leading-7 text-[#68746f]"
                    >
                        Paste a Harbor Desk message. Slot Fill Desk finds the
                        date, time window, and city—then flags anything that
                        still needs a human.
                    </p>
                </div>

                <div
                    class="overflow-hidden rounded-2xl border border-[#e1e6e1] bg-white shadow-[0_16px_50px_-36px_rgba(32,57,47,0.3)]"
                >
                    <div
                        class="flex items-center justify-between border-b border-[#edf0ed] px-5 py-4 sm:px-7"
                    >
                        <div>
                            <p class="text-sm font-bold">New booking request</p>
                            <p class="mt-1 text-xs text-[#87918c]">
                                Paste the customer’s message as written
                            </p>
                        </div>
                        <span
                            class="rounded-md bg-[#f3f5f2] px-2 py-1 font-mono text-[10px] font-semibold tracking-wide text-[#7d8983]"
                            >INBOX → SLOTS</span
                        >
                    </div>

                    <form class="p-5 sm:p-7" @submit.prevent="submit">
                        <label
                            for="source_text"
                            class="mb-2 block text-xs font-bold tracking-wide text-[#53615a] uppercase"
                            >Request text</label
                        >
                        <textarea
                            id="source_text"
                            v-model="form.source_text"
                            rows="5"
                            maxlength="5000"
                            placeholder="Example: Pickup tomorrow afternoon in Miami"
                            class="w-full resize-y rounded-xl border border-[#dce3dd] bg-[#fcfdfb] p-4 text-[15px] leading-7 text-[#21302a] transition outline-none placeholder:text-[#a5aea8] focus:border-[#78a287] focus:ring-4 focus:ring-[#e8f1e9]"
                            :aria-invalid="Boolean(form.errors.source_text)"
                        />
                        <div
                            class="mt-2 flex min-h-5 items-start justify-between gap-4"
                        >
                            <p
                                v-if="form.errors.source_text"
                                class="text-xs font-medium text-[#b3503b]"
                            >
                                {{ form.errors.source_text }}
                            </p>
                            <p v-else class="text-xs text-[#929c96]">
                                No addresses or sensitive details needed.
                            </p>
                            <span
                                class="shrink-0 text-[11px] text-[#a0a9a3] tabular-nums"
                                >{{ form.source_text.length }}/5000</span
                            >
                        </div>

                        <div
                            class="mt-5 flex flex-col-reverse items-stretch justify-between gap-3 border-t border-[#edf0ed] pt-5 sm:flex-row sm:items-center"
                        >
                            <span class="text-xs text-[#7d8983]"
                                >Required slots
                                <span class="font-semibold text-[#53615a]"
                                    >date · window · city</span
                                ></span
                            >
                            <button
                                type="submit"
                                :disabled="
                                    form.processing ||
                                    form.source_text.trim().length < 3
                                "
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#173d37] px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#22564a] focus:ring-4 focus:ring-[#d9e8dd] focus:outline-none disabled:cursor-not-allowed disabled:opacity-45"
                            >
                                <svg
                                    v-if="form.processing"
                                    class="size-4 animate-spin"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    aria-hidden="true"
                                >
                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="9"
                                        stroke="currentColor"
                                        stroke-width="3"
                                        opacity=".25"
                                    />
                                    <path
                                        d="M21 12a9 9 0 0 0-9-9"
                                        stroke="currentColor"
                                        stroke-width="3"
                                        stroke-linecap="round"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    class="size-4"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M5 12h14m-6-6 6 6-6 6"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                                {{
                                    form.processing
                                        ? 'Reading request…'
                                        : 'Extract booking slots'
                                }}
                            </button>
                        </div>
                    </form>
                </div>

                <section
                    v-if="result"
                    class="mt-6 overflow-hidden rounded-2xl border border-[#dce8dc] bg-white shadow-[0_16px_50px_-36px_rgba(32,57,47,0.3)]"
                    aria-live="polite"
                >
                    <div
                        class="flex flex-wrap items-center justify-between gap-3 border-b border-[#edf0ed] px-5 py-4 sm:px-7"
                    >
                        <div class="flex items-center gap-3">
                            <span
                                class="flex size-9 items-center justify-center rounded-full"
                                :class="
                                    result.missing.length
                                        ? 'bg-[#fff1dc] text-[#a96c21]'
                                        : 'bg-[#e7f2e8] text-[#3d7953]'
                                "
                            >
                                <svg
                                    v-if="!result.missing.length"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    class="size-5"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="m5 12.5 4.5 4.5L19 7"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                                <svg
                                    v-else
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    class="size-5"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M12 8v5m0 3h.01M10.3 4.8 2.9 17.6A1.6 1.6 0 0 0 4.3 20h15.4a1.6 1.6 0 0 0 1.4-2.4L13.7 4.8a1.9 1.9 0 0 0-3.4 0Z"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-bold">
                                    {{
                                        result.missing.length
                                            ? 'Review needed'
                                            : 'Slots extracted'
                                    }}
                                </p>
                                <p class="mt-0.5 text-xs text-[#87918c]">
                                    Request #{{ result.id }} ·
                                    {{ 3 - result.missing.length }} of 3 fields
                                    found
                                </p>
                            </div>
                        </div>
                        <span
                            class="rounded-full px-3 py-1 text-[10px] font-bold tracking-[0.12em] uppercase"
                            :class="
                                result.missing.length
                                    ? 'bg-[#fff5e7] text-[#9b6b2f]'
                                    : 'bg-[#edf6ed] text-[#477653]'
                            "
                        >
                            {{
                                result.missing.length ? 'Needs review' : 'Ready'
                            }}
                        </span>
                    </div>

                    <div class="grid gap-3 p-5 sm:grid-cols-3 sm:p-7">
                        <div
                            v-for="slot in [
                                { key: 'date', label: 'Date', icon: '◷' },
                                {
                                    key: 'window',
                                    label: 'Time window',
                                    icon: '◴',
                                },
                                { key: 'city', label: 'City', icon: '⌖' },
                            ]"
                            :key="slot.key"
                            class="rounded-xl border p-4"
                            :class="
                                result.missing.includes(slot.key)
                                    ? 'border-dashed border-[#e9d4ae] bg-[#fffbf3]'
                                    : 'border-[#e5ebe5] bg-[#fbfcfa]'
                            "
                        >
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-[11px] font-bold tracking-wide text-[#85918a] uppercase"
                                    >{{ slot.label }}</span
                                >
                                <span class="text-base text-[#7e9b84]">{{
                                    slot.icon
                                }}</span>
                            </div>
                            <p
                                class="mt-3 text-[15px] font-semibold capitalize"
                                :class="
                                    result[
                                        slot.key as 'date' | 'window' | 'city'
                                    ]
                                        ? 'text-[#20322a]'
                                        : 'text-[#b1803c]'
                                "
                            >
                                {{
                                    prettySlot(
                                        result[
                                            slot.key as
                                                'date' | 'window' | 'city'
                                        ],
                                    )
                                }}
                            </p>
                        </div>
                    </div>

                    <div
                        v-if="result.missing.length"
                        class="flex flex-wrap items-center gap-2 border-t border-[#edf0ed] bg-[#fffdf8] px-5 py-4 sm:px-7"
                    >
                        <span class="mr-1 text-xs font-semibold text-[#7a7060]"
                            >Still needed</span
                        >
                        <span
                            v-for="field in result.missing"
                            :key="field"
                            class="rounded-full border border-[#ecd9b8] bg-[#fff7e9] px-3 py-1 text-xs font-semibold text-[#9b6b2f] capitalize"
                            >{{ field }}</span
                        >
                    </div>
                </section>

                <div
                    class="mt-6 flex items-start gap-3 rounded-xl border border-[#e3e9e2] bg-[#edf2ec] px-4 py-3.5 text-xs leading-5 text-[#637269]"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        class="mt-0.5 size-4 shrink-0 text-[#5d8368]"
                        aria-hidden="true"
                    >
                        <path
                            d="M12 3v2m0 14v2M3 12h2m14 0h2m-3.6-6.4-1.4 1.4M8 16l-1.4 1.4m10.8 0L16 16M8 8 6.6 6.6M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"
                            stroke="currentColor"
                            stroke-width="1.6"
                            stroke-linecap="round"
                        />
                    </svg>
                    <p>
                        <span class="font-bold text-[#43584a]"
                            >How it works:</span
                        >
                        precise date, time-window, and city rules run first. An
                        optional AI pass is only used to look at fields those
                        rules could not fill.
                    </p>
                </div>
            </section>

            <aside class="space-y-6">
                <section
                    class="rounded-2xl border border-[#dfe6df] bg-white p-5 shadow-[0_16px_50px_-36px_rgba(32,57,47,0.3)] sm:p-6"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-bold">
                                Try a sample request
                            </p>
                            <p class="mt-1 text-xs leading-5 text-[#87918c]">
                                Load a seeded example, then run it through the
                                desk.
                            </p>
                        </div>
                        <span
                            class="rounded-md bg-[#f3f5f2] px-2 py-1 font-mono text-[10px] font-semibold text-[#89948d]"
                            >DEMO</span
                        >
                    </div>

                    <div v-if="recentJobs.length" class="mt-5 space-y-2.5">
                        <button
                            v-for="job in recentJobs.slice(0, 3)"
                            :key="job.id"
                            type="button"
                            class="group w-full rounded-xl border border-[#e8ece8] bg-[#fcfdfb] p-3.5 text-left transition hover:border-[#b8d0bd] hover:bg-[#f7fbf6] focus:ring-2 focus:ring-[#dceade] focus:outline-none"
                            @click="useRequest(job.source_text)"
                        >
                            <span
                                class="flex items-center justify-between gap-3"
                            >
                                <span
                                    class="line-clamp-2 text-[13px] leading-5 font-medium text-[#34423a]"
                                    >“{{ job.source_text }}”</span
                                >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    class="size-4 shrink-0 text-[#9ba69e] transition group-hover:translate-x-0.5 group-hover:text-[#4b805d]"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M5 12h14m-6-6 6 6-6 6"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </span>
                            <span class="mt-2.5 flex flex-wrap gap-1.5">
                                <span
                                    v-if="job.window"
                                    class="rounded bg-[#edf3ee] px-2 py-1 text-[10px] font-semibold text-[#55745e] capitalize"
                                    >{{ job.window }}</span
                                >
                                <span
                                    v-if="job.city"
                                    class="rounded bg-[#edf3ee] px-2 py-1 text-[10px] font-semibold text-[#55745e]"
                                    >{{ job.city }}</span
                                >
                                <span
                                    v-if="job.missing.length"
                                    class="rounded bg-[#fff4e5] px-2 py-1 text-[10px] font-semibold text-[#9a713e]"
                                    >{{ job.missing.length }} missing</span
                                >
                                <span
                                    v-else
                                    class="rounded bg-[#edf6ed] px-2 py-1 text-[10px] font-semibold text-[#477653]"
                                    >Complete</span
                                >
                            </span>
                        </button>
                    </div>
                    <p
                        v-else
                        class="mt-5 rounded-xl bg-[#f7f9f6] p-4 text-xs leading-5 text-[#839087]"
                    >
                        No requests yet. Try “Pickup tomorrow afternoon in
                        Miami”.
                    </p>
                </section>

                <section
                    class="overflow-hidden rounded-2xl bg-[#183c35] p-5 text-white sm:p-6"
                >
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-bold">Extraction pipeline</p>
                        <span
                            class="rounded-full border border-white/15 px-2.5 py-1 text-[9px] font-bold tracking-[0.14em] text-[#c3d9c7] uppercase"
                            >Guardrails first</span
                        >
                    </div>
                    <div class="mt-5 space-y-0">
                        <div class="flex gap-3 pb-4">
                            <span
                                class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-white/10 font-mono text-[11px] text-[#cde2cf]"
                                >01</span
                            >
                            <div>
                                <p class="text-xs font-bold">Request text</p>
                                <p
                                    class="mt-1 text-[11px] leading-5 text-[#c2d0c6]"
                                >
                                    Plain-language booking note
                                </p>
                            </div>
                        </div>
                        <div
                            class="ml-3.5 h-4 border-l border-dashed border-white/25"
                        />
                        <div class="flex gap-3 py-1 pb-4">
                            <span
                                class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-[#d6ebd6] font-mono text-[11px] font-bold text-[#24563f]"
                                >02</span
                            >
                            <div>
                                <p class="text-xs font-bold">
                                    Rules-based extraction
                                </p>
                                <p
                                    class="mt-1 text-[11px] leading-5 text-[#c2d0c6]"
                                >
                                    ISO & relative dates · time words · city
                                    allow-list
                                </p>
                            </div>
                        </div>
                        <div
                            class="ml-3.5 h-4 border-l border-dashed border-white/25"
                        />
                        <div class="flex gap-3 pt-1">
                            <span
                                class="flex size-7 shrink-0 items-center justify-center rounded-lg bg-white/10 font-mono text-[11px] text-[#cde2cf]"
                                >03</span
                            >
                            <div>
                                <p class="text-xs font-bold">
                                    Optional Gemini pass
                                </p>
                                <p
                                    class="mt-1 text-[11px] leading-5 text-[#c2d0c6]"
                                >
                                    Only unresolved fields · 12-second timeout ·
                                    safe fallback
                                </p>
                            </div>
                        </div>
                    </div>
                    <div
                        class="mt-5 border-t border-white/10 pt-4 text-[10px] leading-4 text-[#b7c9bc]"
                    >
                        No API key required. The desk stays usable in
                        retrieval-only mode.
                    </div>
                </section>

                <div
                    class="flex items-center justify-between px-1 text-[10px] font-medium tracking-wide text-[#929d96] uppercase"
                >
                    <span>Harbor Desk · Intake tools</span>
                    <span>Demo workspace</span>
                </div>
            </aside>
        </main>
    </div>
</template>
