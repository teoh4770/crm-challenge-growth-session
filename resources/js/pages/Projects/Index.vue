<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Form, Head } from '@inertiajs/vue3';

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
    projects: {
        data: any[];
    };
}

defineProps<ProjectIndexProps>();

function getStatusTagSeverity(status: "active" | "inactive") {
    const tagSeverityMapping = {
        "active": "success",
        "inactive": "warn",
    }

    return tagSeverityMapping[status];
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
                    href="#"
                    class="w-fit"
                    label="Add Client"
                    icon="pi pi-plus"
                />

                <DataTable
                    :dt="{
                        headerCell: {
                            background: '{surface.800}',
                            color: '{surface.100}',
                        },
                    }"
                >
                    <Column field="name" header="Name" :sortable="true" />
                    <Column
                        field="email"
                        header="Email"
                        :sortable="true"
                    ></Column>
                    <Column
                        field="status"
                        header="Status"
                        :sortable="true"
                    >
                        <template #body="slotProps">
                            <Tag :value="slotProps.data.status" :severity="getStatusTagSeverity(slotProps.data.status)" />
                        </template>
                    </Column>
                    <Column
                        field="company"
                        header="Company"
                        :sortable="true"
                    ></Column>
                    <Column
                        field="address"
                        header="Address"
                        :sortable="true"
                    ></Column>
                    <Column
                        field="phone"
                        header="Phone"
                        :sortable="true"
                    ></Column>
                    <Column header="Actions">
                        <template #body>
                            <div class="flex gap-1">
                                <Button
                                    as="a"
                                    href="#"
                                    label="Edit"
                                    size="small"
                                    raised
                                />
                                <Form>
                                    <Button
                                        type="submit"
                                        label="Delete"
                                        size="small"
                                        severity="secondary"
                                        raised
                                    />
                                </Form>
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </div>
        </div>
    </AppLayout>
</template>
