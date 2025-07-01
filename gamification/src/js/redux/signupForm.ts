import { createSlice, PayloadAction } from '@reduxjs/toolkit';

const signupFormState = {
	step: 0,
	accountType: 'icon',
};

const signupForm = createSlice({
	name: 'signupForm',
	initialState: signupFormState,
	reducers: {
		setStep: (state, action: PayloadAction<number>) => {
			state.step = action.payload;
		},
		setAccountType: (state, action: PayloadAction<string>) => {
			state.accountType = action.payload;
		},
	},
});

export const { setStep, setAccountType } = signupForm.actions;

export default signupForm;
