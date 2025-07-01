import { createSlice } from '@reduxjs/toolkit';

const initialState = {
	isLoading: false,
};

const loadingScreen = createSlice({
	name: 'loadingScreen',
	reducers: {
		setLoading: (state, action:{ payload: boolean }) => {
			state.isLoading = action.payload;
		},
	},
	initialState,
});

export default loadingScreen;

export const { setLoading } = loadingScreen.actions;