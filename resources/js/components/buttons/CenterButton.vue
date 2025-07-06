<script lang="ts">
import { defineComponent } from 'vue';
import { useRouter } from 'vue-router';

export default defineComponent({
    name: 'CenterButton',
    props: {
        buttonText: {
            type: String,
            default: 'Click me'
        },
        to: {
            type: [String, Object],
            default: null
        }
    },
    emits: ['button-click'],
    setup(props, { emit }) {
        const router = useRouter();

        const handleClick = () => {
            console.log(router);
            if (props.to) {
                router.push(props.to);
            }
            emit('button-click');
        };

        return {
            handleClick
        };
    }
});
</script>

<template>
    <button
        class="center-button"
        @click="handleClick"
    >
        {{ buttonText }}
    </button>
</template>

<style scoped>
.center-button {
    padding: 12px 24px;
    margin: 10px;
    font-size: 16px;
    background-color: #42b983;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color .2s;
    font-weight: bold;
}

.center-button:hover {
    background-color: #33a06f;
}
</style>
