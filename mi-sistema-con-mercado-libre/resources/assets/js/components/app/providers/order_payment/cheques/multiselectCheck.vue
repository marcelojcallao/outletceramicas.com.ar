<script>
import MultiselectBase from './../../../../Base/Multiselect/MultiselectBase';

export default {

	props: {
		index: {
			type: Number,
			required: true
		}
	},

    name: "multiselectCheck",

	extends: MultiselectBase,

    methods:{

    },

	computed: {


	},

	async mounted(){

		const {data} = await this.$store.dispatch('getCheckingAccounts');

		data.map((element, index) => {
			data[index].name = element.bank
		});

		this.options = data

	}
};
</script>

<style lang="scss" scoped></style>
