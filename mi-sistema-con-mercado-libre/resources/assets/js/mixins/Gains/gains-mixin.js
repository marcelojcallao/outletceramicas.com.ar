export default {

    methods: {

        async loadData(page=1) {                                                                
            
            this.spinner = true;

            let url = "/api/gains/index?page=" + page;

            if (this.GainsFromDateGetter) {
                const date = `${this.$moment(this.GainsFromDateGetter).format("YYYY-MM-DD")} 00:00:00`;
                url = `${url}&from=${date}`;
            }

            if (this.GainsToDateGetter) {
                const date = `${this.$moment(this.GainsToDateGetter).format("YYYY-MM-DD")} 23:59:59`;
                url = `${url}&to=${date}`;
            }

            if (this.ListProductsGetter.length > 0) {
                url = `${url}&product_id=${this.ListProductsGetter[0].id}`;
            }

            const { data:earning } = await this.$store.dispatch("listOfEarnings", url)
                .catch((err) => {
                    Vue.swal.fire({
                        title: "PRODUCTO SIN STOCK",
                        text: err.response.data.message,
                        icon: "error",
                        showDenyButton: true,
                        denyButtonColor: "#dd6b55",
                        denyButtonText: "Cerrar",
                        showConfirmButton: false,
                    });
                })
                .finally(() => this.spinner = false)

            if (earning) {
                this.$store.dispatch("gainsSetList", earning.data);
                this.$store.dispatch("gainsSetPagination", earning.pagination);

                const totalEarnings = earning.data.reduce((acc, current) => {
                    return acc + parseFloat(current.earning)
                }, 0);

                this.$store.dispatch("gainsSetTotalEarnings", totalEarnings);

            }
        },
    },

}