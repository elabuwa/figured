<template>
    <div class="overflow-hidden border-t border-l border-r border-gray-400 bg-gray-200 p-8">
        <div class="group p-6 bg-white max-w-screen w-3/5 shadow-lg overflow-hidden mx-auto h-48">
            <form @submit="onSubmit">
                <p class="font-semibold text-lg mb-1 text-gray-900">Check Stock Availability</p>
                <input class="px-4 py-2 leading-tight border border-blue" type="number" v-model="quantity">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-1" type="submit">Check</button>
            </form>
        </div>
        <div class="group p-6 bg-white max-w-screen w-3/5 shadow-lg overflow-hidden mx-auto h-48" v-if="hasResponse">
            <label v-if="response.isAvailable">Cost : {{ response.cost }}</label>
            <label v-if="response.isAvailable==false">Required quantity unavailable. Max available units : {{ response.totalAvailable }}</label>

        </div>
    </div>
</template>

<script>
export default {
    name: "quantityChecker.vue",
    data() {
        return {
            'quantity' : 0,
            'hasResponse' : false,
            'response' : {
                'isAvailable' : false,
                'cost' : 0,
                'totalAvailable' : 0
            }
        }
    },
    methods: {
        onSubmit(e){
            e.preventDefault();
            this.hasResponse = false;
            var self = this;
            axios.post('/api/checkAvailableQuantity',
        {
                quantity: this.quantity
            },{
                'headers' : {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
            .then(function (response) {
                self.hasResponse = true;
                self.response = response.data;
            })
            .catch(function (error) {
                alert(error.response.data.message)
            })
            .finally(function () {
                // always executed
            });
        }
    }
}
</script>

<style scoped>

</style>
