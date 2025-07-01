import { createSlice } from '@reduxjs/toolkit';
import { playfabClientApi } from '../api/apiBridge';
import createDebouncedAsyncAction from './async/createDebouncedAsyncAction';
import { getPlayerCombinedInfo } from './playfab';

type IPlayerDataState = typeof initialState;
const initialState = {
	data: null as Record<string, any>,
	loaded: false,
};

const playerDataToValues = (playerData:object) => {
	return Object.entries(playerData).reduce((carry, [key, value]) => {
		const content = value?.Value || value;
		try {
			carry[key] = JSON.parse(content);
		} catch {
			carry[key] = content;
		}
		return carry;
	}, {} as Record<string, any>);
};

export const getPlayerData = createDebouncedAsyncAction<IPlayerDataState>(
	'playfab/getPlayerData',
	() => {
		return playfabClientApi('GetUserData', {});
	}
);

export const updatePlayerData = createDebouncedAsyncAction<IPlayerDataState>(
	'playfab/updatePlayerData',
	(data) => {
		const { permission } = data || 'Public';

		const playerData = { ...data };
		delete playerData.permission;

		for (const key in playerData) {
			if (typeof playerData[key] === 'object') playerData[key] = JSON.stringify(playerData[key]);
		}

		return playfabClientApi('UpdateUserData', { Data: playerData, Permission: permission });
	}
);

const playerData = createSlice({
	name: 'playerData',
	reducers: {
		updateLocalPlayerData: (state, action) => {
			return {
				...state,
				data: {
					...state.data,
					...action.payload,
				},
			};
		},
	},
	extraReducers: (builder) => {
		builder.addCase(getPlayerData.actions.fulfilled, (state, action) => {
			return {
				...state,
				...playerDataToValues(action.payload?.data?.Data),
			};
		});
		builder.addCase(updatePlayerData.actions.pending, (state, action) => {
			return {
				...state,
				loaded: true,
				data: {
					...state.data,
					...action.meta.arg,
				},
			};
		});
		builder.addCase(getPlayerCombinedInfo.actions.fulfilled, (state:IPlayerDataState, action) => {
			const result = action.payload.data.InfoResultPayload;
			return {
				...state,
				loaded: true,
				data: {
					...playerDataToValues(result.UserData),
					...playerDataToValues(result.UserReadOnlyData),
				},
			}
		});
	},
	initialState,
});

export default playerData;

export const { updateLocalPlayerData } = playerData.actions;
