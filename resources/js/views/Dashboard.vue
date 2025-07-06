<script setup lang="ts">
import {RouteName} from "@/enums/route.enum";
import CenterButton from "@/components/buttons/CenterButton.vue";
</script>

<template>
    <div class="app-container">
        <h1>Микро-сервис обработки платежей</h1>
        <div class="buttons-container">
            <CenterButton
                button-text="Платежи"
                :to="{ name: RouteName.PAYMENTS }"
            />
            <CenterButton
                button-text="Пользователи"
                :to="{ name: RouteName.USERS }"
            />
        </div>
        <router-view></router-view>
    </div>
</template>

<style scoped>
h1 {
    font-size: 32px;
    font-weight: bold;
}

.app-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    text-align: center;
}

.buttons-container {
    display: flex;
    justify-content: center;
    margin: 30px 0;
}
</style>
