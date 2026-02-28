<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { useConfirm } from 'primevue';
import TaskController from '@/actions/App/Http/Controllers/TaskController';
import Pagination from '@/components/Pagination.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Tasks',
        href: '#',
    },
];

interface TaskIndexProps {
    can: {
        delete_task: boolean;
    };
    tasks: object;
}

const confirm = useConfirm();

const props = defineProps<TaskIndexProps>();

function deleteTask(taskId: number) {
    confirm.require({
        message: 'Are you sure you want to delete this task?',
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
            const taskDestroyRoute = TaskController.destroy(taskId);

            router.visit(taskDestroyRoute.url, {
                method: taskDestroyRoute.method,
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
                    :href="TaskController.create().url"
                    class="w-fit"
                    label="Add Task"
                    icon="pi pi-plus"
                />

                <DataTable
                    :value="tasks.data"
                    :dt="{
                        headerCell: {
                            background: '{surface.800}',
                            color: '{surface.100}',
                        },
                    }"
                >
                    <Column field="title" header="Title" :sortable="true" />
                    <Column
                        field="description"
                        header="Description"
                        :sortable="true"
                    />
                    <Column
                        field="project.title"
                        header="Project"
                        :sortable="true"
                    />
                    <Column field="user.name" header="User" :sortable="true" />
                    <Column field="status" header="Status" :sortable="true" />
                    <Column
                        field="due_date"
                        header="Due Date"
                        :sortable="true"
                    />
                    <Column header="Actions">
                        <template #body="slotProps">
                            <div class="flex gap-1">
                                <Button
                                    as="a"
                                    :href="
                                        TaskController.edit(slotProps.data.id)
                                            .url
                                    "
                                    label="Edit"
                                    size="small"
                                    raised
                                />
                                <Button
                                    v-if="can.delete_task"
                                    label="Delete"
                                    size="small"
                                    severity="secondary"
                                    raised
                                    @click="deleteTask(slotProps.data.id)"
                                />
                            </div>
                        </template>
                    </Column>
                </DataTable>

                <Pagination :pagination="tasks.meta" />
            </div>
        </div>

        <ConfirmDialog></ConfirmDialog>
    </AppLayout>
</template>
