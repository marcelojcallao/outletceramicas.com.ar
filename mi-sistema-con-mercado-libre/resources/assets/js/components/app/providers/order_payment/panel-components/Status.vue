<template>
    <div>
        <span class="label label-warning">{{data.status}}</span>
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
