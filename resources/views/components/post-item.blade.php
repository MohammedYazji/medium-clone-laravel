<div class="flex bg-white border border-gray-200 rounded-lg shadow-sm mb-8">
    <div class="p-5 flex-1">
        <a href="{{ route('post.show', ['username' => $post->user->username, 'post' => $post->slug]) }}">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">
                {{ $post->title }}
            </h5>
        </a>
        <div class="mb-3 font-normal text-gray-700">
            {{ Str::words($post->content, 20) }}
        </div>
        <div class="flex items-center gap-4 text-gray-400 text-sm">
            <div class="flex items-center gap-1 text-gray-500">
                By
                <a href="{{ route('profile.show', $post->user->username) }}" class="hover:underline text-gray-600 font-medium">
                    <span>@</span>{{ $post->user->username }}
                </a>

                at
                {{ $post->getFormattedPublishedAt() }}
            </div>
            <span class="inline-flex gap-1 items-center">
                <x-svg-clap-icon />
                {{ $post->claps_count }}
            </span>
        </div>
    </div>
    <a href="{{ route('post.show', ['username' => $post->user->username, 'post' => $post->slug]) }}">
        <img class="w-48 h-full max-h-64 object-cover rounded-r-lg" src="{{ $post->imageUrl('preview') }}"
            alt="Blog image" />
    </a>
</div>
