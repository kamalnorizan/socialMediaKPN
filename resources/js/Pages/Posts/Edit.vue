<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    post: Object,
});

const form = useForm({
    content: props.post.content,
});

function submit() {
    form.patch(route('posts.update', props.post.uuid), {
        onSuccess: () => {
            // Handle success, e.g., show a success message or redirect
        },
    });
}
</script>

<template>
    <Head title="Edit Post" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit Post
            </h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
                <div class="rounded-xl bg-white shadow-sm ring-1 ring-gray-100 p-6">
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                            <textarea
                                v-model="form.content"
                                rows="5"
                                class="w-full rounded-lg border border-gray-300 p-3 text-sm text-gray-900 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            ></textarea>
                            <p v-if="form.errors.content" class="text-red-500 text-sm mt-1">{{ form.errors.content }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50 focus:outline-none focus:ring focus:ring-indigo-200"
                            >
                                Save Changes
                            </button>
                            <a
                                :href="route('posts.index')"
                                class="rounded-lg px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100"
                            >
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
