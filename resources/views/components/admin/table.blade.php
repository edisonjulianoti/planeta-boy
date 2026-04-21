<div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-800">
                    {{ $headers ?? '' }}
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
