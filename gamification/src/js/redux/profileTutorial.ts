import { createSlice } from '@reduxjs/toolkit';

const initialState = {
	leaderboard: false,
	tutorialOverlay: false,
	creator: false,
	challenge: false,
	tutorial: false,
};

const profileTutorial = createSlice({
	name: 'profileTutorialState',
	initialState,
	reducers: {
		openLeaderboard: (state, action: { payload: boolean }) => {
			state.leaderboard = action.payload;
		},
		toggleOverlay: (state, action: { payload: boolean }) => {
			state.tutorialOverlay = action.payload;
		},
		updateCreatorState: (state, action: { payload: boolean }) => {
			state.creator = action.payload;
		},
		openCreatorChallenge: (state, action: { payload: boolean }) => {
			state.challenge = action.payload;
		},
		updateTutorialState: (state, action: { payload: boolean }) => {
			state.tutorial = action.payload;
		},
	},
});

export const {
	openLeaderboard,
	toggleOverlay,
	updateCreatorState,
	openCreatorChallenge,
	updateTutorialState,
} = profileTutorial.actions;
export default profileTutorial;
