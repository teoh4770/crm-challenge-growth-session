<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { index, create, edit } from '@/routes/clients';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useConfirm } from 'primevue';
import ClientController from '@/actions/App/Http/Controllers/ClientController';
import Pagination from '@/components/Pagination.vue';
import { FilterMatchMode } from '@primevue/core/api';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Clients',
        href: index().url,
    },
];

interface ClientIndexProps {
    can: {
        manage_clients: boolean;
    };
    clients: object;
}

defineProps<ClientIndexProps>();

const confirm = useConfirm();

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    status: { value: null, matchMode: FilterMatchMode.EQUALS }
});
const statuses = ref(['active', 'inactive']);

const createClientRoute = computed(() => {
    return create().url;
});

function getStatusTagSeverity(status: 'active' | 'inactive') {
    const tagSeverityMapping = {
        active: 'success',
        inactive: 'warn',
    };

    return tagSeverityMapping[status];
}

function deleteClient(id: number) {
    confirm.require({
        message: 'Are you sure you want to delete this client?',
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
            const clientDestroyRoute = ClientController.destroy(id);

            router.visit(clientDestroyRoute.url, {
                method: clientDestroyRoute.method,
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
                    :href="createClientRoute"
                    class="w-fit"
                    label="Add Client"
                    icon="pi pi-plus"
                />

                <DataTable
                    v-model:filters="filters"
                    :globalFilterFields="['name', 'email']"
                    filterDisplay="row"
                    :value="clients.data"
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
                    <template #empty>No clients found. </template>
                    <template #loading
                        >Loading clients data. Please wait.
                    </template>
                    <Column field="name" header="Name" :sortable="true" />
                    <Column
                        field="email"
                        header="Email"
                        :sortable="true"
                    ></Column>
                    <Column field="status" header="Status" :sortable="true" :showFilterMenu="false">
                        <template #body="slotProps">
                            <Tag
                                :value="slotProps.data.status"
                                :severity="
                                    getStatusTagSeverity(slotProps.data.status)
                                "
                            />
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
                        <template #body="slotProps">
                            <div class="flex gap-1">
                                <Button
                                    as="a"
                                    :href="edit(slotProps.data.id).url"
                                    label="Edit"
                                    size="small"
                                    raised
                                />
                                <Button
                                    v-if="can.manage_clients"
                                    type="submit"
                                    label="Delete"
                                    size="small"
                                    severity="secondary"
                                    raised
                                    @click="deleteClient(slotProps.data.id)"
                                />
                            </div>
                        </template>
                    </Column>
                </DataTable>

                <Pagination :pagination="clients.meta" />
            </div>
        </div>

        <ConfirmDialog></ConfirmDialog>
    </AppLayout>
</template>
