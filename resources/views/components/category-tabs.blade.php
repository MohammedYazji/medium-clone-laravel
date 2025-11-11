<ul class="flex flex-wrap text-sm font-medium text-center text-gray-600 border-b border-gray-200 justify-center">
    <li class="me-2">
        <a href="/" aria-current="page" class="{{
            request('category')
            ? 'inline-block p-4 text-black rounded-t-lg hover:bg-gray-100'
            : 'inline-block p-4 text-white bg-gray-800 rounded-t-lg active'
            }}">
            All
        </a>
    </li>
    @forelse ($categories as $category)
        <li class="me-2">
            <a href="{{ route('post.byCategory', $category) }}" aria-current="page" class="{{
                Route::currentRouteNamed('post.byCategory') && request('category')->id == $category->id
                ? 'inline-block p-4 text-white bg-gray-800 rounded-t-lg active'
                : 'inline-block p-4 text-black rounded-t-lg hover:bg-gray-100'
                }}">
                {{ $category->name }}
            </a>
        </li>
    @empty
        {{ $slot }}
    @endforelse

</ul>
