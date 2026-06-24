<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    posts: Array,
});

function avatar(name) {
    return name ? name.charAt(0).toUpperCase() : '?';
}

function timeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = Math.floor((now - date) / 1000);

    if (diff < 60) return `${diff}s ago`;
    if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
    if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
    return `${Math.floor(diff / 86400)}d ago`;
}
</script>

<template>

    <Head title="Posts" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Latest Posts
            </h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
                <div v-for="post in posts" :key="post.id"
                    class="mb-5 rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                    <div class="p-5">
                        <!-- Author -->
                        <div class="flex items-center gap-3 mb-4">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-sm font-semibold text-white">
                                {{ avatar(post.user?.name) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">{{ post.user?.name }}</p>
                                <p class="text-xs text-gray-400">{{ timeAgo(post.created_at) }}</p>
                            </div>
                        </div>

                        <!-- Content -->
                        <p class="text-gray-800 leading-relaxed mb-4">{{ post.content }}</p>

                        <!-- Actions -->
                        <div class="flex gap-1 border-t border-gray-100 pt-3">
                            <button
                                class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm text-gray-500 hover:bg-gray-100 hover:text-indigo-600 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                </svg>
                                Like
                            </button>
                            <button
                                class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm text-gray-500 hover:bg-gray-100 hover:text-indigo-600 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                Comment
                            </button>
                        </div>

                        <div v-if="post.comments && post.comments.length > 0" class="mt-4 space-y-2">
                            <p class="text-xs font-semibold uppercase tra cking-wide text-gray-400 mb-2">
                                Comments ({{ post.comments.length }})
                            </p>
                        </div>
                    </div>
                </div>
            </div>s
        </div>
    </AuthenticatedLayout>
d

</template>
