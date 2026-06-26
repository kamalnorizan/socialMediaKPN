<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

defineProps({
    posts: Array,
});

const page = usePage();

const authUser = page.props.auth.user;
const flashSuccess = computed(() => page.props.flash.success);

// watch(flashSuccess, (newValue) => {
//     if (newValue) {
//         // alert(newValue); // You can replace this with a more user-friendly notification system
//     }
// });

const postForm = useForm({
    content: '',
});

function submitForm() {
    postForm.post('/posts', {
        onSuccess: () => {
            postForm.reset();
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
}

function deletePost(id) {
    if (confirm('Delete this post?')) {
        router.delete(route('posts.destroy', id));
    }
}

const activeCommentPostId = ref(null);

const commentForm = useForm({
    content: '',
});

function toggleCommentForm(postId) {
    if (activeCommentPostId.value === postId) {
        activeCommentPostId.value = null; // Close the comment form if it's already open for this post
    } else {
        activeCommentPostId.value = postId; // Open the comment form for this post
    }
}

function submitComment(postId) {
    commentForm.post(route('comments.store', postId), {
        onSuccess: () => {
            commentForm.reset();
            activeCommentPostId.value = null; // Reset the active comment post ID after successful submission
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
}

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
                <div v-if="flashSuccess" class="bg-green-100 p-4 rounded-lg mb-4">
                    {{ flashSuccess }}
                </div>

                <!-- Create Post Form -->
                <div class="mb-5 rounded-xl bg-white shadow-sm ring-1 ring-gray-100">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-sm font-semibold text-white">
                        {{ avatar(authUser?.name) }}
                    </div>
                    <div class="p-5">
                        <form @submit.prevent="submitForm">
                            <textarea v-model="postForm.content" placeholder="What's on your mind?"
                                class="w-full rounded-lg border border-gray-300 p-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            <p v-if="postForm.errors.content" class="text-red-500 text-sm mt-1">{{
                                postForm.errors.content }}
                            </p>
                            <button type="submit"
                                class="mt-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                Post
                            </button>
                        </form>
                    </div>
                </div>
                <div class="mt-4">
                    <label for="limit" class="mr-2 text-sm font-semibold text-gray-700">Posts per page:</label>
                    <select id="limit" v-model="posts.per_page" style="width: 80px;"
                        @change="router.get(route('posts.index', { limit: posts.per_page }))"
                        class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                    </select>
                </div>
                <div v-for="(post, index) in posts.data" :key="post.id"
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

                            <button @click="toggleCommentForm(post.id)"
                                class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm text-gray-500 hover:bg-gray-100 hover:text-indigo-600 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                Comment
                            </button>

                            <template v-if="post.user?.id === authUser?.id">
                                <a :href="route('posts.edit', post.uuid)"
                                    class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm text-gray-500 hover:bg-gray-100 hover:text-indigo-600 transition-colors ml-auto">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                <button @click="deletePost(post.uuid)"
                                    class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm text-gray-500 hover:bg-gray-100 hover:text-red-600 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </template>
                        </div>

                        <form v-if="activeCommentPostId === post.id" @submit.prevent="submitComment(post.uuid)"
                            class="mt-4">
                            <textarea v-model="commentForm.content" placeholder="Write a comment..."
                                class="w-full rounded-lg border border-gray-300 p-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            <p v-if="commentForm.errors.content" class="text-red-500 text-sm mt-1">{{
                                commentForm.errors.content
                                }}</p>
                            <button type="submit"
                                class="mt-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                Comment
                            </button>
                        </form>

                        <div v-if="post.comments && post.comments.length > 0" class="mt-4 space-y-2">
                            <p class="text-xs font-semibold uppercase tra cking-wide text-gray-400 mb-2">
                                Comments ({{ post.comments.length }})
                            </p>
                            <div v-for="comment in post.comments" :key="comment.id"
                                class="flex items-start gap-2 rounded-lg bg-gray-50 p3">
                                <div
                                    class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-pink-400 to-rose-500 text-xs font-semibold text-white">
                                    {{ avatar(comment.user?.name) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ comment.user?.name }}</p>
                                    <p class="text-sm text-gray-700">{{ comment.content }}</p>
                                    <p class="text-xs text-gray-400">{{ timeAgo(comment.created_at) }}</p>
                                </div>
                            </div>
                        </div>
                        <p v-else class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">No Comments
                            Yet</p>
                    </div>
                </div>

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2"
                    v-if="!posts || posts.length === 0">
                    Tiada Data Post</p>
                <div v-else>
                    <div class="mt-4">
                        <button v-if="posts.current_page > 1"
                            @click="router.get(route('posts.index', { page: posts.current_page - 1 }))"
                            class="mr-2 rounded-lg bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring focus:ring-gray-200 focus:ring-opacity-50">
                            Previous
                        </button>
                        Showing page {{ posts.current_page }} of {{ posts.last_page }} ({{ posts.data.length }} of {{
                        posts.total
                        }} total posts)
                        <button v-if="posts.current_page < posts.last_page"
                            @click="router.get(route('posts.index', { page: posts.current_page + 1 }))"
                            class="rounded-lg bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring focus:ring-gray-200 focus:ring-opacity-50">
                            Next
                        </button>
                    </div>
                    <div class="mt-4">
                        <button v-for="page in posts.last_page" :key="page"
                            @click="router.get(route('posts.index', { page: page }))"
                            :class="{'bg-gray-300': page === posts.current_page, 'bg-gray-200': page !== posts.current_page}"
                            class="mr-2 rounded-lg px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring focus:ring-gray-200 focus:ring-opacity-50">
                            {{ page }}
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>


</template>
