<template>
        <div class="sidebar--container" :class="(open) ? 'open--side--bar' : ''" >
            <slot name="header"/>
            <slot name="body"/>
            <slot name="footer"/>
        </div>
</template>

<script>
    export default {

        props: {
            open : {
                type: Boolean,
                required: true,
                default: false
            }
        }
    }
</script>

<style scoped>
.sidebar--container{
    position: fixed;
    top: 0;
    right: 0;
    height: 90vh;
    background-color: rgb(235, 237, 231);
    z-index: 1000;
}

.open--side--bar{
        animation-name: open;
        animation-duration: .5s;
        animation-direction: reverse;
        animation-timing-function: ease-in;
    }
.close--side--bar {
    animation-name: close;
    animation-duration: .5s;
    animation-timing-function: ease-in;
}
@keyframes open {
    0%{
        transform: translateX(0);
    }
    100% {
        transform: translateX(100%);
    }
}
@keyframes close {

    0% {
        transform: translateX(0%);
    }


    100% {
        transform: translateX(100%);
    }
}
</style>
