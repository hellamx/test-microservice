<script setup lang="ts">
import { ref } from 'vue'
import PaymentsTable from "@/components/tables/PaymentsTable.vue"
import {RouteName} from "@/enums/route.enum";
import CenterButton from "@/components/buttons/CenterButton.vue";

const isLoading = ref(false)

const exportToExcel = async () => {
    try {
        isLoading.value = true

        const response = await fetch('/api/internal/payments/export', {
            method: 'GET',
            headers: {
                'Accept': 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            }
        })

        if (!response.ok) {
            console.warn('Ошибка при экспорте файла')
            return
        }

        const blob = await response.blob()
        const url = window.URL.createObjectURL(blob)

        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', 'payments.xlsx')
        document.body.appendChild(link)
        link.click()
        link.remove()

        window.URL.revokeObjectURL(url)
    } catch (error) {
        alert('Ошибка при экспорте файла.')
        console.error(error)
    } finally {
        isLoading.value = false
    }
}
</script>


<template>
    <div class="top-menu">
        <h2>Платежи</h2>
        <div class="button-group">
            <button @click="exportToExcel" :disabled="isLoading">
                {{ isLoading ? "Экспортируем..." : "Экспорт данных в Excel" }}
            </button>
            <CenterButton
                button-text="Вернуться назад"
                :to="{ name: RouteName.DASHBOARD }"
            />
        </div>
    </div>
    <PaymentsTable />
</template>

<style lang="scss" scoped>
.top-menu {
    background: rgb(66, 185, 131);
    font-size: 24px;
    padding: 20px;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: bold;

    button {
        background: #fff;
        color: rgb(66, 185, 131);;
        outline: 0;
        border-radius: 3px;
        outline: none;
        padding: 12px 16px;
        font-weight: bold;
        font-size: 16px;
        cursor: pointer;
        border: 2px solid transparent;

        &:hover {
            transition: all .2s;
            background: rgb(66, 185, 131);;
            color: #fff;
            border: 2px solid #fff;
        }
    }
}
</style>
