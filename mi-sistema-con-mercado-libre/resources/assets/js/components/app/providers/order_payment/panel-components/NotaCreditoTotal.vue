<template>
    <div>
        {{ data.total | currency }}
    </div>
</template>

<script>
    export default {
        
        props: {
            data: {
                required: true
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>