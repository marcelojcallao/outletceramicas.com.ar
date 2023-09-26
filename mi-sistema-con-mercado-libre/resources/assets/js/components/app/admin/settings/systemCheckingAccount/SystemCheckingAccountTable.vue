<template>
    <div id='table' >
        <v-client-table
            ref='checkingAccountTable'
            :columns='columns' 
            :data='rows' 
            :options='options'
        >
        </v-client-table>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    export default {
        
        name: '', 
        
        data(){
            return {
                columns : [
                    'number',
                    'bank',
                    'code',
                    'active'
                ],
                rows : [],
                options: {
                    filterable: false,
                    orderBy: { column: false },
                    sortable: [],
                    uniqueKey : 'id',
                    pagination: { dropdown:false },
                    headings: {
                    },
                    templates: {
                    }
                }
            }
        },
        
        computed: {
            
            ...mapGetters([
                'SystemCheckingAccountList'
            ])
        },
            
        methods: {
            
            async loadTableData(){

                return await this.$store.dispatch('getCheckingAccounts');
            }
        },

        watch: {

            SystemCheckingAccountList(n){
                this.rows = n;
            }
        },
        
        async mounted(){

            const { data:checkAccounts } = await this.loadTableData();

            if (checkAccounts) {
                this.$store.dispatch('systemCheckingAccountSetList', checkAccounts);
            }
        }
    }
</script>