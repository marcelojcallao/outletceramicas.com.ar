<template>
    <slideout-panel></slideout-panel>
</template>

<script>
export default {
    name: 'add_customer_modal',
    
    methods: {
        showPanel() {
            const panel1Handle = this.$showPanel({
            component : 'OrderNew',
            props: {
                //any data you want passed to your component
            }
            })

            panel1Handle.promise
            .then(result => {

            });
        }
    }

}
</script>

<style>

</style>