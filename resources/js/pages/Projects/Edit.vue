<script setup lang="ts">
import ProjectController from '@/actions/App/Http/Controllers/ProjectController';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem, Client, ProjectStatus, User } from '@/types';
import { Form, Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import ProjectUploadController from '@/actions/App/Http/Controllers/ProjectUploadController';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Projects',
        href: ProjectController.index().url,
    },
    {
        title: 'Edit',
        href: '#',
    },
];

interface Project {
    id: number;
    title: string;
    description: string;
    client: Client;
    user: User;
    deadline: string;
    status: string;
}

interface Media {
    id: number;
    fileName: string;
    size: number;
}

interface ProjectEditProps {
    project: {
        data: Project;
    };
    users: {
        data: User[];
    };
    clients: {
        data: Client[];
    };
    projectStatuses: ProjectStatus[];
    projectMedias: {
        data: Media[]
    };
}

const props = defineProps<ProjectEditProps>();

console.log(props);

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
                    :action="ProjectController.update(project.data.id)"
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
                            :value="project.data.title"
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
                            :value="project.data.description"
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
                            for="client_id"
                            class="block text-sm/6 font-medium text-primary"
                            >Client</label
                        >
                        <select
                            name="client_id"
                            id="client_id"
                            class="bg-neutral-secondary-medium border-default-medium text-heading rounded-base focus:ring-brand focus:border-brand placeholder:text-body block w-full border px-3 py-2.5 text-sm shadow-xs"
                            :class="{
                                'border-red-500': errors['client_id'],
                            }"
                        >
                            <option>Choose a client</option>
                            <option
                                v-for="client in clients.data"
                                :key="client.id"
                                :value="client.id"
                                :selected="
                                    client.id === project.data.client?.id
                                "
                            >
                                {{ client.name }}
                            </option>
                        </select>
                        <p class="text-xs text-red-500 italic">
                            {{ errors['client_id'] }}
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
                                :selected="user.id === project.data.user?.id"
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
                            for="deadline"
                            class="block text-sm/6 font-medium text-primary"
                        >
                            Deadline
                        </label>
                        <input
                            id="deadline"
                            type="date"
                            name="deadline"
                            :value="project.data.deadline"
                            :min="currentDate"
                            class="bg-neutral-secondary-medium border-default-medium text-heading rounded-base focus:ring-brand focus:border-brand placeholder:text-body block w-full border px-3 py-2.5 text-sm shadow-xs"
                            :class="{
                                'border-red-500': errors['deadline'],
                            }"
                        />
                        <p class="text-xs text-red-500 italic">
                            {{ errors['deadline'] }}
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
                                v-for="projectStatus in projectStatuses"
                                :key="projectStatus.value"
                                :value="projectStatus.value"
                                :selected="
                                    projectStatus.value === project.data.status
                                "
                            >
                                {{ projectStatus.label }}
                            </option>
                        </select>
                        <p class="text-xs text-red-500 italic">
                            {{ errors['status'] }}
                        </p>
                    </div>

                    <div class="flex justify-end gap-2">
                        <Button as="a" :href="ProjectController.index().url" class="w-fit" severity="secondary">Cancel</Button>
                        <Button type="submit" class="w-fit">Update Project</Button>
                    </div>
                </Form>
            </div>

            <Card>
                <template #content>
                    <div class="grid gap-4">
                        <Form class="grid gap-4" :action="ProjectUploadController(project.data.id)" #default="{ errors }">
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
                            <DataTable :value="projectMedias.data" tableStyle="min-width: 50rem">
                                <Column field="fileName" header="File Name"></Column>
                                <Column field="size" header="Size">
                                    <template #body="slotProps">
                                        <span class="text-slate-300">{{ slotProps.data.size }} KB</span>
                                    </template>
                                </Column>
                                <Column header="Actions">
                                    <template #body>
                                        <div class="space-x-2">
                                            <Button as="a" label="Download" size="small" />
                                            <Button as="a" label="Delete" severity="danger" size="small" />
                                        </div>
                                    </template>
                                </Column>
                            </DataTable>
                        </div>
                    </div>
                </template>
            </Card>
        </div>
    </AppLayout>
</template>
