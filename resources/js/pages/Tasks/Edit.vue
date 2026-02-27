<script setup lang="ts">
import TaskController from '@/actions/App/Http/Controllers/TaskController';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem, User } from '@/types';
import { Form, Head, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import TaskUploadController from '@/actions/App/Http/Controllers/TaskUploadController';
import { useConfirm } from 'primevue';
import ProjectController from '@/actions/App/Http/Controllers/ProjectController';
import ProjectUploadController from '@/actions/App/Http/Controllers/ProjectUploadController';

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
        title: 'Edit',
        href: '#',
    },
];

interface Project {
    id: number;
    title: string;
}

interface Task {
    id: number;
    title: string;
    description: string;
    status: string;
    priority: string;
    due_date: string;
    project: Project;
    user: User;
}

interface TaskStatus {
    value: string;
    label: string;
}

interface TaskPriority {
    value: string;
    label: string;
}

interface ProjectCreateProps {
    task: {
        data: Task;
    };
    users: {
        data: User[];
    };
    projects: {
        data: Project[];
    };
    taskStatuses: TaskStatus[];
    taskPriorities: TaskPriority[];
    taskMedias: {
        data: any[];
    };
}

const confirm = useConfirm();

defineProps<ProjectCreateProps>();

const currentDate = computed(() => {
    return new Date().toISOString().split('T')[0];
});

function deleteMedia(mediaId: number) {
    confirm.require({
        message: 'Are you sure you want to delete this file?',
        header: 'Confirmation',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Cancel',
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: 'Delete',
            severity: 'danger',
        },
        accept: () => {
            const taskUploadDestroyRoute = TaskUploadController.destroy(mediaId);

            router.visit(taskUploadDestroyRoute.url, {
                method: taskUploadDestroyRoute.method,
            });
        },
    });
}
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
                            :value="task.data.title"
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
                            :value="task.data.description"
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
                            <option>Choose a user</option>
                            <option
                                v-for="user in users.data"
                                :key="user.id"
                                :value="user.id"
                                :selected="task.data.user.id === user.id"
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
                                :selected="project.id === task.data.project.id"
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
                            :value="task.data.due_date"
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
                            <option>Choose a status</option>
                            <option
                                v-for="taskStatus in taskStatuses"
                                :key="taskStatus.value"
                                :value="taskStatus.value"
                                :selected="
                                    taskStatus.value === task.data.status
                                "
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
                            >Priority</label
                        >
                        <select
                            name="priority"
                            id="priority"
                            class="bg-neutral-secondary-medium border-default-medium text-heading rounded-base focus:ring-brand focus:border-brand placeholder:text-body block w-full border px-3 py-2.5 text-sm shadow-xs"
                            :class="{
                                'border-red-500': errors['priority'],
                            }"
                        >
                            <option>Choose a priority</option>
                            <option
                                v-for="taskPriority in taskPriorities"
                                :key="taskPriority.value"
                                :value="taskPriority.value"
                                :selected="
                                    taskPriority.value === task.data.priority
                                "
                            >
                                {{ taskPriority.label }}
                            </option>
                        </select>
                        <p class="text-xs text-red-500 italic">
                            {{ errors['priority'] }}
                        </p>
                    </div>

                    <Button type="submit">Edit Task</Button>
                </Form>
            </div>

            <Card>
                <template #content>
                    <div class="grid gap-4">
                        <Form
                            class="grid gap-4"
                            :action="TaskUploadController.store(task.data.id)"
                            #default="{ errors }"
                        >
                            <div class="flex flex-col gap-1">
                                <label
                                    for="file"
                                    class="block text-sm/6 font-medium text-primary"
                                >
                                    File
                                </label>
                                <input
                                    id="file"
                                    type="file"
                                    name="file"
                                    class="border p-1"
                                    :class="{
                                        'border-red-500': errors['file'],
                                    }"
                                />
                                <p class="text-xs text-red-500 italic">
                                    {{ errors['file'] }}
                                </p>
                            </div>

                            <Button type="submit" class="w-fit">Upload</Button>
                        </Form>

                        <div class="card">
                            <DataTable
                                :value="taskMedias.data"
                                tableStyle="min-width: 50rem"
                            >
                                <Column
                                    field="fileName"
                                    header="File Name"
                                ></Column>
                                <Column field="size" header="Size">
                                    <template #body="slotProps">
                                        <span class="text-slate-300"
                                            >{{ slotProps.data.size }} KB</span
                                        >
                                    </template>
                                </Column>
                                <Column header="Actions">
                                    <template #body="slotProps">
                                        <div class="space-x-2">
                                            <Button
                                                as="a"
                                                :href="TaskUploadController.download(slotProps.data.id).url"
                                                label="Download"
                                                size="small"

                                            />
                                            <Button
                                                label="Delete"
                                                severity="danger"
                                                size="small"
                                                @click="
                                                    deleteMedia(
                                                        slotProps.data.id,
                                                    )
                                                "
                                            />
                                        </div>
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                    </div>
                </template>
            </Card>
        </div>

        <ConfirmDialog></ConfirmDialog>
    </AppLayout>
</template>
