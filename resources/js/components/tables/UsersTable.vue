<script lang="ts">
import {defineComponent, ref, onMounted, computed} from 'vue';
import { VueGoodTable } from 'vue-good-table-next';
import type { Column } from 'vue-good-table-next';
import 'vue-good-table-next/dist/vue-good-table-next.css';
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';
import { watch } from 'vue';
import { debounce } from 'lodash';

interface User {
    id: number;
    login: string;
    project_name: string;
    kzt_balance: number;
    usd_balance: number;
    rub_balance: number;
}

export default defineComponent({
    name: 'UsersTable',
    components: { VueGoodTable },
    setup() {
        const columns = ref<Column[]>([
            { label: 'ID', field: 'id', type: 'number', width: '80px', sortable: false },
            { label: 'Логин', field: 'login', sortable: false },
            { label: 'Проект', field: 'project_name', sortable: false },
            {
                label: 'KZT',
                field: 'kzt_balance',
                type: 'number',
                sortable: false,
                formatFn: (value: number) =>
                    value.toLocaleString('ru-RU', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
            },
            {
                label: 'USD',
                field: 'usd_balance',
                type: 'number',
                sortable: false,
                formatFn: (value: number) =>
                    `$${value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`,
            },
            {
                label: 'RUB',
                field: 'rub_balance',
                type: 'number',
                sortable: false,
                formatFn: (value: number) =>
                    value.toLocaleString('ru-RU', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
            },
        ]);
        const rows = ref<User[]>([]);
        const isLoading = ref(false);
        const totalRecords = ref(0);
        const currentPage = ref(1);
        const perPage = ref(20);
        const route = useRoute();
        const router = useRouter();
        const filters = ref({
            id: '',
            login: '',
            project_name: '',
        });

        const fetchUsers = async () => {
            isLoading.value = true;
            try {
                const response = await axios.get('/api/internal/users/get', {
                    params: {
                        page: currentPage.value,
                        per_page: perPage.value,
                        id: filters.value.id || '',
                        login: filters.value.login || '',
                        project_name: filters.value.project_name || '',
                    }
                });

                rows.value = response.data.data.map((user: any) => ({
                    ...user,
                    kzt_balance: Number(user.wallets[0].balance),
                    usd_balance: Number(user.wallets[1].balance),
                    rub_balance: Number(user.wallets[2].balance),
                }));
                totalRecords.value = response.data.total;
            } catch (err) {
                console.error('Ошибка загрузки:', err);
            } finally {
                isLoading.value = false;
            }
        };

        const onPageChange = ({ currentPage: newPage }: { currentPage: number }) => {
            currentPage.value = newPage;
            router.push({
                query: {
                    ...route.query,
                    page: newPage.toString(),
                    ...filters.value
                }
            });
            fetchUsers();
        };

        const onPerPageChange = ({ currentPerPage }: { currentPerPage: number }) => {
            perPage.value = currentPerPage;
            currentPage.value = 1;
            fetchUsers();
        };

        const paginationOptions = computed(() => ({
            enabled: true,
            mode: 'pages',
            perPage: perPage.value,
            setCurrentPage: currentPage.value,
            position: 'bottom',
            perPageDropdown: [10, 20, 30, 50],
            nextLabel: 'Вперед',
            prevLabel: 'Назад',
            external: true,
        }));

        const goToPrevPage = () => {
            if (currentPage.value > 1) {
                currentPage.value -= 1;
                router.push({ query: { ...route.query, page: currentPage.value.toString() } });
                fetchUsers();
            }
        };

        const goToNextPage = () => {
            const totalPages = Math.ceil(totalRecords.value / perPage.value);
            if (currentPage.value < totalPages) {
                currentPage.value += 1;
                router.push({ query: { ...route.query, page: currentPage.value.toString() } });
                fetchUsers();
            }
        };

        const updateQueryAndFetch = debounce(() => {
            const query: Record<string, any> = {
                page: '1',
                per_page: perPage.value.toString(),
                id: filters.value.id || undefined,
                login: filters.value.login || undefined,
                project_name: filters.value.project_name || undefined,
            };

            Object.keys(query).forEach((key) => {
                if (query[key] === undefined || query[key] === '') {
                    delete query[key];
                }
            });

            router.push({ query });
            currentPage.value = 1;
            fetchUsers();
        }, 400);

        watch(filters, updateQueryAndFetch, { deep: true });

        onMounted(() => {
            const pageParam = Number(route.query.page);
            if (!isNaN(pageParam) && pageParam > 0) {
                currentPage.value = pageParam;
            }

            filters.value.id = (route.query.id as string) || '';
            filters.value.login = (route.query.login as string) || '';
            filters.value.project_name = (route.query.project_name as string) || '';

            fetchUsers();
        });


        return {
            columns,
            rows,
            isLoading,
            totalRecords,
            currentPage,
            perPage,
            onPageChange,
            onPerPageChange,
            paginationOptions,
            goToPrevPage,
            goToNextPage,
            filters
        };
    },
});
</script>

<template>
    <div class="users-table-container">
        <VueGoodTable
            :columns="columns"
            :rows="rows"
            :total-rows="totalRecords"
            :is-loading="isLoading"
            :pagination-options="paginationOptions"
            @on-page-change="onPageChange"
            @on-per-page-change="onPerPageChange"
        >
            <template #table-column="{ column }">
                <div v-if="column.field === 'id'" class="column-filter">
                    <strong>{{ column.label }}</strong>
                    <input
                        v-model="filters.id"
                        type="text"
                        placeholder="Поиск"
                        class="filter-input"
                    />
                </div>
                <div v-else-if="column.field === 'login'" class="column-filter">
                    <strong>{{ column.label }}</strong>
                    <input
                        v-model="filters.login"
                        type="text"
                        placeholder="Поиск"
                        class="filter-input"
                    />
                </div>
                <div v-else-if="column.field === 'kzt_balance'" class="column-filter">
                    <strong>{{ column.label }}</strong>
                    <input
                        disabled
                        type="text"
                        placeholder="Поиск"
                        class="filter-input"
                    />
                </div>
                <div v-else-if="column.field === 'usd_balance'" class="column-filter">
                    <strong>{{ column.label }}</strong>
                    <input
                        disabled
                        type="text"
                        placeholder="Поиск"
                        class="filter-input"
                    />
                </div>
                <div v-else-if="column.field === 'rub_balance'" class="column-filter">
                    <strong>{{ column.label }}</strong>
                    <input
                        disabled
                        type="text"
                        placeholder="Поиск"
                        class="filter-input"
                    />
                </div>
                <div v-else-if="column.field === 'project_name'" class="column-filter">
                    <strong>{{ column.label }}</strong>
                    <input
                        v-model="filters.project_name"
                        type="text"
                        placeholder="Поиск"
                        class="filter-input"
                    />
                </div>
                <div v-else>
                    <strong>{{ column.label }}</strong>
                </div>
            </template>
            <template #pagination-bottom="props">
                <div class="pagination-controls">
                    <button @click="goToPrevPage" :disabled="currentPage === 1">Назад</button>
                    <span class="current-page--label">
                        {{ currentPage}}
                    </span>
                    <button @click="goToNextPage" :disabled="currentPage >= Math.ceil(totalRecords / perPage)">Вперед</button>
                </div>
            </template>
        </VueGoodTable>
    </div>
</template>

<style scoped>
.users-table-container {
    margin: 20px;
}

:deep(.vgt-table) {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border: none !important;
    border-bottom: 1px solid #DCDFE6 !important;
}

:deep(.vgt-inner-wrap) {
    box-shadow: none !important;
}

:deep(thead th) {
    color: #fff !important;
    padding-top: 20px !important;
    padding-bottom: 20px !important;
    text-align: left !important;
}

:deep(td) {
    border-right: none !important;
    border-bottom: none !important;
}

:deep(tbody) {
    border-bottom: 1px solid #000 !important;
}

:deep(.sortable button::before) {
    cursor: pointer;
    border-top: 5px solid #fff !important;
}

:deep(.sortable button::after) {
    cursor: pointer;
    border-bottom: 5px solid #fff !important;
}

:deep(td:last-child) {
    border-right: 1px solid #DCDFE6 !important;
}

:deep(.vgt-table.striped th) {
    background-color: #f8f9fa;
    padding: 12px 15px;
    text-align: left;
    font-weight: 600;
}

:deep(th) {
    background: rgb(66, 185, 131) !important;
}

:deep(.vgt-table.striped tr:nth-child(even)) {
    background-color: #f8f9fa;
}

:deep(.vgt-table.striped td) {
    padding: 10px 15px;
    border-bottom: 1px solid #eee;
}

.column-filter {
    display: flex;
    flex-direction: column;

    input, select {
        margin-top: 10px;
        padding: 8px 15px;
        font-weight: bold;
        border: 0;
        outline: none;
    }
}

.pagination-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
    margin-top: 20px;
}

.pagination-controls button {
    padding: 12px 22px;
    background: #42b983;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
}

.pagination-controls button:disabled {
    background: #cccccc;
    cursor: not-allowed;
}

.current-page--label {
    color: #606266;
    font-weight: bold;
}
</style>
