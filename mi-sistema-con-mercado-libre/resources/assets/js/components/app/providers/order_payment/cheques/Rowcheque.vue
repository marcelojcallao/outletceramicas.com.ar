<template>
	<div class="row--cheque">
		<multiselect-check name="www" />
	</div>
</template>

<script>
	import multiselectCheck from './multiselectCheck.vue';
	export default {

		name: 'RowCheque',

		props: {
			index: {
				type: Number,
				required: true
			}
		}
	}
</script>

<style lang="scss" scoped>
	.row--cheque{
		display: flex;
	}

</style>
