<div class="prose prose-sm max-w-none text-gray-500">
    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
        <div>
            <dt class="text-sm font-medium text-gray-900">Category</dt>
            <dd class="mt-1 text-sm text-gray-500">
                {{ $product->category->category_name ?? 'N/A' }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-900">Stock Quantity</dt>
            <dd class="mt-1 text-sm text-gray-500">{{ $product->stock }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-900">Weight</dt>
            <dd class="mt-1 text-sm text-gray-500">{{ $product->weight ?? 'N/A' }} kg</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-900">Dimensions</dt>
            <dd class="mt-1 text-sm text-gray-500">{{ $product->dimensions ?? 'N/A' }}</dd>
        </div>
    </dl>
</div>
