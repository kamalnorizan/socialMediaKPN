<script setup>

import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const notifications = computed(() => {
    return page.props.notifications?.items ?? [];
});

const unreadCount = computed(() => {
    return page.props.notifications?.unread_count ?? 0;
});

const goToPost = (notification) => {
    if (notification.data.post_uuid) {
        window.location.href = `/posts/${notification.data.post_uuid}`;
    }
};

</script>


<template>
    <div class="position-relative">
        <button class="btn btn-primary position-relative">
            <i class="bi bi-bell"></i>
            <span v-if="unreadCount > 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ unreadCount }}
                <span class="visually-hidden">unread notifications</span>
            </span>
        </button>
        Test
    </div>

</template>
