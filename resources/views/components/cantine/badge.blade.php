@props(['tone' => 'slate'])

@php
    $tones = [
        'emerald' => 'bg-emerald-500/15 text-emerald-200 ring-emerald-400/40',
        'amber' => 'bg-amber-500/15 text-amber-200 ring-amber-400/40',
        'rose' => 'bg-rose-500/15 text-rose-200 ring-rose-400/40',
        'sky' => 'bg-sky-500/15 text-sky-200 ring-sky-400/40',
        'slate' => 'bg-slate-500/15 text-slate-200 ring-slate-400/40',
        'violet' => 'bg-violet-500/15 text-violet-200 ring-violet-400/40',
    ];

    $classes = $tones[$tone] ?? $tones['slate'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset {$classes}"]) }}>
    {{ $slot }}
</span>
