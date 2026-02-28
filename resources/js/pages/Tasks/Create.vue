<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem, User } from '@/types';
import { Form, Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import TaskController from '@/actions/App/Http/Controllers/TaskController';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Tasks',
        href: TaskController.index().url,
    },
    {
        title: 'Create',
        href: '#',
    },
];

interface ProjectCreateProps {
    users: {
        data: User[];
    };
    projects: {
        data: Project[];
    };
    taskStatuses: TaskStatus[];
    taskPriorities: TaskPriority[];
}

defineProps<ProjectCreateProps>();

const currentDate = computed(() => {
    return new Date().toISOString().split('T')[0];
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="card">
                <Form
                    class="grid gap-4"
                    :action="TaskController.store()"
                    #default="{ errors }"
                >
                    <div class="flex flex-col gap-1">
                        <label
                            for="title"
                            class="block text-sm/6 font-medium text-primary"
                        >
                            Title
                        </label>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            class="border p-1"
                            :class="{
                                'border-red-500': errors['title'],
                            }"
                        />
                        <p class="text-xs text-red-500 italic">
                            {{ errors['title'] }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label
                            for="description"
                            class="block text-sm/6 font-medium text-primary"
                        >
                            Description
                        </label>
                        <input
                            id="description"
                            type="text"
                            name="description"
                            class="border p-1"
                            :class="{
                                'border-red-500': errors['description'],
                            }"
                        />
                        <p class="text-xs text-red-500 italic">
                            {{ errors['description'] }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label
                            for="user_id"
                            class="block text-sm/6 font-medium text-primary"
                        >
                            User
                        </label>
                        <select
                            name="user_id"
                            id="user_id"
                            class="bg-neutral-secondary-medium border-default-medium text-heading rounded-base focus:ring-brand focus:border-brand placeholder:text-body block w-full border px-3 py-2.5 text-sm shadow-xs"
                            :class="{
                                'border-red-500': errors['user_id'],
                            }"
                        >
                            <option selected>Choose a user</option>
                            <option
                                v-for="user in users.data"
                                :key="user.id"
                                :value="user.id"
                            >
                                {{ user.name }}
                            </option>
                        </select>
                        <p class="text-xs text-red-500 italic">
                            {{ errors['user_id'] }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label
                            for="user_id"
                            class="block text-sm/6 font-medium text-primary"
                        >
                            Project
                        </label>
                        <select
                            name="project_id"
                            id="project_id"
                            class="bg-neutral-secondary-medium border-default-medium text-heading rounded-base focus:ring-brand focus:border-brand placeholder:text-body block w-full border px-3 py-2.5 text-sm shadow-xs"
                            :class="{
                                'border-red-500': errors['project_id'],
                            }"
                        >
                            <option selected>Choose a project</option>
                            <option
                                v-for="project in projects.data"
                                :key="project.id"
                                :value="project.id"
                            >
                                {{ project.title }}
                            </option>
                        </select>
                        <p class="text-xs text-red-500 italic">
                            {{ errors['project_id'] }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label
                            for="due_date"
                            class="block text-sm/6 font-medium text-primary"
                        >
                            Due Date
                        </label>
                        <input
                            id="due_date"
                            type="date"
                            name="due_date"
                            :min="currentDate"
                            class="bg-neutral-secondary-medium border-default-medium text-heading rounded-base focus:ring-brand focus:border-brand placeholder:text-body block w-full border px-3 py-2.5 text-sm shadow-xs"
                            :class="{
                                'border-red-500': errors['due_date'],
                            }"
                        />
                        <p class="text-xs text-red-500 italic">
                            {{ errors['due_date'] }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label
                            for="status"
                            class="block text-sm/6 font-medium text-primary"
                            >Status</label
                        >
                        <select
                            name="status"
                            id="status"
                            class="bg-neutral-secondary-medium border-default-medium text-heading rounded-base focus:ring-brand focus:border-brand placeholder:text-body block w-full border px-3 py-2.5 text-sm shadow-xs"
                            :class="{
                                'border-red-500': errors['status'],
                            }"
                        >
                            <option selected>Choose a status</option>
                            <option
                                v-for="taskStatus in taskStatuses"
                                :key="taskStatus.value"
                                :value="taskStatus.value"
                            >
                                {{ taskStatus.label }}
                            </option>
                        </select>
                        <p class="text-xs text-red-500 italic">
                            {{ errors['status'] }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label
                            for="priority"
                            class="block text-sm/6 font-medium text-primary"
                        >Status</label
                        >
                        <select
                            name="priority"
                            id="priority"
                            class="bg-neutral-secondary-medium border-default-medium text-heading rounded-base focus:ring-brand focus:border-brand placeholder:text-body block w-full border px-3 py-2.5 text-sm shadow-xs"
                            :class="{
                                'border-red-500': errors['priority'],
                            }"
                        >
                            <option selected>Choose a priority</option>
                            <option
                                v-for="taskPriority in taskPriorities"
                                :key="taskPriority.value"
                                :value="taskPriority.value"
                            >
                                {{ taskPriority.label }}
                            </option>
                        </select>
                        <p class="text-xs text-red-500 italic">
                            {{ errors['priority'] }}
                        </p>
                    </div>

                    <Button type="submit">Create Task</Button>
                </Form>
            </div>
        </div>
    </AppLayout>
</template>
