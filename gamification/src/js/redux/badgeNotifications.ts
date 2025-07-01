import { createSlice, PayloadAction } from '@reduxjs/toolkit';

const initialState = {
	list: [] as string[],
};

const badgeNotifications = createSlice({
	name: 'badgeNotifications',
	initialState,
	reducers: {
		addBadgeNotification: (state, action: PayloadAction<string>) => {
			state.list = [...state.list, action.payload];
		},
		removeBadgeNotification: (state, action: PayloadAction<string>) => {
			state.list = state.list.filter((x) => x != action.payload);
		},
	},
});

export const { addBadgeNotification, removeBadgeNotification } =
	badgeNotifications.actions;

export default badgeNotifications;
