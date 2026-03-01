<script setup lang="ts">
import ProjectController from '@/actions/App/Http/Controllers/ProjectController';
import Pagination from '@/components/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { FilterMatchMode } from '@primevue/core/api';
import { useConfirm } from 'primevue';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Projects',
        href: '#',
    },
];

interface ProjectIndexProps {
    can: {
        delete_project: boolean;
    };
    projects: object;
}

const confirm = useConfirm();

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    status: { value: null, matchMode: FilterMatchMode.EQUALS },
});
const statuses = ref(['pending', 'in_progress', 'on_hold', 'review', 'completed', 'cancelled']);

defineProps<ProjectIndexProps>();

function deleteProject(id: number) {
    confirm.require({
        message: 'Are you sure you want to delete this project?',
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
            const projectDestroyRoute = ProjectController.destroy(id);

            router.visit(projectDestroyRoute.url, {
                method: projectDestroyRoute.method,
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
            <div class="card grid gap-4">
                <Button
                    as="a"
                    :href="ProjectController.create().url"
                    class="w-fit"
                    label="Add Project"
                    icon="pi pi-plus"
                />

                <DataTable
                    v-model:filters="filters"
                    :globalFilterFields="['status']"
                    filterDisplay="row"
                    :value="projects.data"
                    :dt="{
                        headerCell: {
                            background: '{surface.800}',
                            color: '{surface.100}',
                        },
                    }"
                >
                    <template #header>
                        <div class="flex justify-end">
                            <IconField>
                                <InputIcon>
                                    <i class="pi pi-search" />
                                </InputIcon>
                                <InputText v-model="filters['global'].value" placeholder="Keyword Search" />
                            </IconField>
                        </div>
                    </template>
                    <template #empty>No projects found. </template>
                    <template #loading
                        >Loading projects data. Please wait.
                    </template>
                    <Column field="title" header="Title" :sortable="true" />
                    <Column
                        field="description"
                        header="Description"
                        :sortable="true"
                    />
                    <Column
                        field="client.name"
                        header="Client"
                        :sortable="true"
                    />
                    <Column field="title" header="Project" :sortable="true" />
                    <Column field="user.name" header="User" :sortable="true" />
                    <Column field="status" header="Status" :sortable="true" :showFilterMenu="false">
                        <template #body="{ data }">
                            <Tag :value="data.status" />
                        </template>
                        <template #filter="{ filterModel, filterCallback }">
                            <Select v-model="filterModel.value" @change="filterCallback()" :options="statuses" placeholder="Select One" :showClear="true">
                                <template #option="slotProps">
                                    <Tag :value="slotProps.option" />
                                </template>
                            </Select>
                        </template>
                    </Column>
                    <Column
                        field="deadline"
                        header="Deadline"
                        :sortable="true"
                    />
                    <Column header="Actions">
                        <template #body="slotProps">
                            <div class="flex gap-1">
                                <Button
                                    as="a"
                                    :href="
                                        ProjectController.edit(
                                            slotProps.data.id,
                                        ).url
                                    "
                                    label="Edit"
                                    size="small"
                                    raised
                                />
                                <Button
                                    v-if="can.delete_project"
                                    label="Delete"
                                    size="small"
                                    severity="secondary"
                                    raised
                                    @click="deleteProject(slotProps.data.id)"
                                />
                            </div>
                        </template>
                    </Column>
                </DataTable>

                <Pagination :pagination="projects.meta" />
            </div>
        </div>

        <ConfirmDialog></ConfirmDialog>
    </AppLayout>
</template>
