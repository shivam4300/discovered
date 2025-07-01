import { createSlice } from '@reduxjs/toolkit';
import { getXrApi } from '../api/apiBridge';
import createDebouncedAsyncAction from './async/createDebouncedAsyncAction';
import xrAction from './async/xrAction';

type IOtherPlayersState = {
	profiles: {
		[key:string]: GetPlayerProfileResponse['Profile'],
	},
};
const initialState = {
	profiles: {},
	inventories: {},
} as IOtherPlayersState;

export const getOtherPlayerProfile = createDebouncedAsyncAction(
	'playfab/getOtherPlayerProfile',
	xrAction((playFabId) => {
		return getXrApi().Client.GetPlayerProfile({
			PlayFabId: playFabId,
		});
	})
);

const other_players = createSlice({
	name: 'other_players',
	reducers: {},
	extraReducers: (builder) => {
		builder.addCase(getOtherPlayerProfile.actions.fulfilled, (state:IOtherPlayersState, action) => {
			const profile = action.payload.data.Profile;
			return {
				...state,
				profiles: {
					...state.profiles,
					[profile.PlayerId]: {
						...profile,
					},
				},
			};
		});
	},
	initialState,
});

export default other_players;
