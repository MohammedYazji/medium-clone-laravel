<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <h1 class="text-2xl mb-4">{{ $post->title }}</h1>

                {{-- User Avatar --}}
                <div class="flex gap-4">
                    <x-user-avatar :user="$post->user" />
                    <div>
                        <x-follow-ctr :user="$post->user" class="flex gap-2">
                            <a href="{{ route('profile.show', $post->user) }}"
                                class="hover:underline">{{ $post->user->name }}</a>
                            @if (auth()->user() && auth()->user()->id !== $post->user->id)
                                &middot;
                                <button href="#" x-text="following ? 'Unfollow' : 'Follow'"
                                    :class="following ? 'text-red-600' : 'text-emerald-500'" @click="follow()">
                                </button>
                            @endif
                        </x-follow-ctr>
                        <div class="flex gap-2 text-gray-500 text-sm">
                            {{ $post->readTime() }} min read
                            &middot;
                            {{ $post->getFormattedPublishedAt() }}
                        </div>
                    </div>
                </div>
                {{-- User Avatar --}}

                {{-- Editing Section --}}
                @if ($post->user_id === Auth::id())
                    <div class="py-4 mt-8 border-gray-200">
                        <x-primary-button href="{{ route('post.edit', $post->slug) }}">
                            Edit Post
                        </x-primary-button>
                        <form class="inline-block" action="{{ route('post.destroy', $post) }}" method="post">
                            @csrf
                            @method('delete')
                            <x-danger-button>
                                Delete Post
                            </x-danger-button>
                        </form>
                    </div>
                @endif
                {{-- Editing Section --}}

                {{-- Clap Section --}}
                <x-clap-button :post="$post" />
                {{-- Clap Section --}}

                {{-- Content Section --}}
                <div class="mt-8 ">
                    <img src="{{ $post->imageUrl('large') }}" alt="{{ $post->title }}" class="w-full">

                    <div class="mt-4">
                        {{ $post->content }}>
                    </div>
                </div>
                {{-- Content Section --}}

                <div class="mt-8">
                    <span class="px-4 py-2 bg-gray-300 rounded-xl">{{ $post->category->name }}</span>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
