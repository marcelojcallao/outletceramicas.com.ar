export const DAILY_MOVEMENT_DATE = (state, value) => state.movement.date = value;

export const DAILY_MOVEMENT_TYPE = (state, value) => state.movement.type = value;

export const DAILY_MOVEMENT_DESCRIPTION = (state, value) => state.movement.description = value;

export const DAILY_MOVEMENT_IMPORT = (state, value) => state.movement.import = value;

export const DAILY_MOVEMENT_LIST = (state, value) => state.list = value;

export const DAILY_MOVEMENT_SALDO = (state, value) => state.saldo = value;

export const DELETE_DAILY_MOVEMENT = (state, value) => {

	const index = state.list.findIndex(dailyMovement => dailyMovement.id === value);
	console.log("🚀 ~ file: Mutations.js:16 ~ index:", index)

	if (index) {
		state.list.splice(index, 1)
	}
}
