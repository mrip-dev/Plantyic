@props(['method', 'url', 'summary'])
<div class="bg-slate-900/80 rounded-xl p-6 mb-6 shadow flex flex-col gap-2 border border-slate-800">
    <div class="flex items-center gap-3 mb-1">
        <span class="font-mono text-xs px-2 py-1 rounded bg-[#BA1A1A] text-white">{{ $method }}</span>
        <span class="font-mono text-xs text-slate-200">{{ $url }}</span>
    </div>
    <div class="text-slate-200 text-base font-semibold">{{ $summary }}</div>
    {{ $slot }}
</div>
