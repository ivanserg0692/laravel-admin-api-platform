@props(['items'])
<nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4">
    <span class="text-sm text-gray-500 dark:text-gray-400">
        Showing  {{ $items->firstItem() ?? 0 }}-{{ $items->lastItem() ?? 0 }} of {{ $items->total() }}
    </span>
    {{ $items->onEachSide(1)->links() }}
</nav>
