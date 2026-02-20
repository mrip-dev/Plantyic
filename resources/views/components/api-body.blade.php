@props(['fields'])
@if (!empty($fields))
    <div class="mt-2">
        <div class="text-xs text-slate-400 mb-1">Body (JSON):</div>
        <div class="relative">
            <pre class="bg-slate-800/90 rounded p-3 text-xs text-slate-100 overflow-x-auto border border-slate-700">{
@foreach ($fields as $field)
    "{{ $field['name'] }}": "{{ $field['type'] }}"@if (!$loop->last),@endif
@endforeach
}</pre>
            <button class="copy-btn absolute top-2 right-2 bg-[#BA1A1A] hover:bg-red-700 text-white text-xs px-3 py-1 rounded shadow">Copy</button>
        </div>
    </div>
@endif
