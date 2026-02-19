@props(['items'])
<div class="p-4">
    @if(isset($items) && method_exists($items, 'links'))
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <span class="text-sm text-gray-500 dark:text-gray-400">
                Showing {{ $items->firstItem() ?? 0 }}-{{ $items->lastItem() ?? 0 }} of {{ $items->total() }}
            </span>
            {{ $items->onEachSide(1)->links() }}
        </div>
    @endif
</div>
