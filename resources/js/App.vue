<script lang="ts">
import { defineComponent } from 'vue';
import CenterButton from './components/buttons/CenterButton.vue';
import UsersTable from './components/tables/UsersTable.vue';
import PaymentsTable from "@/components/tables/PaymentsTable.vue";
import { RouteName } from '@/enums/route.enum'
import '../css/reset.css'
import '../css/global.css';
import 'vue-good-table-next/dist/vue-good-table-next.css'

export default defineComponent({
    name: 'App',
    components: {
        CenterButton,
        UsersTable,
        PaymentsTable
    },
    setup() {
        return {RouteName}
    }
});
</script>

<template>
    <router-view></router-view>
</template>
