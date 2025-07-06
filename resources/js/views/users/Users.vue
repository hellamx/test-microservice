<script setup lang="ts">
import UsersTable from "@/components/tables/UsersTable.vue";
import {RouteName} from "@/enums/route.enum";
import CenterButton from "@/components/buttons/CenterButton.vue";
</script>

<template>
    <div class="top-menu">
        <h2>Пользователи</h2>
        <CenterButton
            button-text="Вернуться назад"
            :to="{ name: RouteName.DASHBOARD }"
        />
    </div>
    <UsersTable />
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
