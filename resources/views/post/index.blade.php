<x-app-layout>


    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 text-gray-900">
                    <x-category-tabs>
                        No Categories
                    </x-category-tabs>
                </div>
            </div>
            <div class="mt-8 text-gray-900">
                @forelse ($posts as $post)
                    <x-post-item :post="$post" />
                @empty
                    <div>
                        <p class="text-gray-400 text-center text-2xl">No posts found.</p>
                    </div>
                @endforelse
            </div>
            {{ $posts->onEachSide(2)->links('pagination::simple-tailwind') }}
        </div>
    </div>
</x-app-layout>
