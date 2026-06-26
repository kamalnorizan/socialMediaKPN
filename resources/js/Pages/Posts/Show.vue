<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

defineProps({
    post: Object,
});

const page = usePage();
const authUser = page.props.auth.user;

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
    <Head :title="'Post by ' + post.user?.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <button @click="router.visit(route('posts.index'))"
                    class="text-gray-500 hover:text-indigo-600 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Post</h2>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">

                <!-- Post Card -->
                <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
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

                            <template v-if="post.user?.id === authUser?.id">
                                <a :href="route('posts.edit', post.id)"
                                    class="ml-auto flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm text-gray-500 hover:bg-gray-100 hover:text-indigo-600 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                            </template>
                        </div>

                        <!-- Content -->
                        <p class="text-gray-800 leading-relaxed mb-4 text-base">{{ post.content }}</p>

                        <!-- Comments Section -->
                        <div class="border-t border-gray-100 pt-4 mt-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-3">
                                Comments ({{ post.comments?.length ?? 0 }})
                            </p>

                            <div v-if="post.comments && post.comments.length > 0" class="space-y-3">
                                <div v-for="comment in post.comments" :key="comment.id"
                                    class="flex items-start gap-2 rounded-lg bg-gray-50 p-3">
                                    <div
                                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-pink-400 to-rose-500 text-xs font-semibold text-white">
                                        {{ avatar(comment.user?.name) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ comment.user?.name }}</p>
                                        <p class="text-sm text-gray-700">{{ comment.content }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ timeAgo(comment.created_at) }}</p>
                                    </div>
                                </div>
                            </div>

                            <p v-else class="text-sm text-gray-400">No comments yet.</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
