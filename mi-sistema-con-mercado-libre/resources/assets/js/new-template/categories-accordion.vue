<template>
  <sidebar-menu :menu="menu" :relative="true"/>
</template>

<script>
import { SidebarMenu } from 'vue-sidebar-menu'
    export default {
        components: {
            SidebarMenu
        },
        data() {
            return {
                menu: [
                    {
                        header: true,
                        title: 'CATEGORIAS',
                        hiddenOnCollapse: true
                    },
                    {
                        title: 'Dashboard',
                        icon: 'fa fa-user'
                    },
                    {
                        title: 'Charts',
                        icon: 'fa fa-chart-area',
                        child: [
                            {
                                title: 'Sub Link',
                                child: [
                                    {
                                        title: 'Sub Link',
                                        child: [
                                            {
                                                title: 'Sub Link'
                                            },
                                            {
                                                title: 'Sub Link'
                                            }
                                        ]
                                    },
                                    {
                                        title: 'Sub Link'
                                    }
                                ]
                            },
                            {
                                title: 'Sub Link'
                            }
                        ]
                    }
                ]
            }
        }
    }
</script>
<style>
    .vsm--header {
        margin:auto;
        width: 20rem;
        font-family: "Hind", Verdana, Geneva, Tahoma, sans-serif
    }
</style>