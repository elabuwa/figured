<template>
    <div>
        <h3>Quantity Checker</h3>
    </div>
</template>

<script>
import axios, {isCancel, AxiosError} from 'axios';

export default {
    name: "quantityChecker.vue",
    data() {
        return {
            'quantity' : 0,
            'isAvailable' : false,
            'cost' : 0,
            'totalAvailable' : 0
        }
    },
    methods: {
        checkQuantity(){

        }
    }
}
</script>

<style scoped>

</style>
