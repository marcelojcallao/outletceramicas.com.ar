import {mapActions, mapGetters} from 'vuex';
export default {

    methods : {

        async findSupplier (query) {

            const payload = {
                token : this.User.token,
                provider : query
            }

            const { data:providers } = await this.$store.dispatch('provider_find_by_name', payload);

            if (providers) {
                this.$store.commit('SET_PROVIDERS', providers);
            }

        },

        ...mapActions([
            'updateSupplierData'
        ])
    },

    computed : {

        ...mapGetters([
            'GetProvidersGetters',
            'SupplierGetter'
        ])
    },
}