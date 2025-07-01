import { createSlice } from '@reduxjs/toolkit';
import createDebouncedAsyncAction from './async/createDebouncedAsyncAction';
import { getXrApi, realtimeApi } from '../api/apiBridge';
import { PLAYFAB_CONFIG } from '../Constants';

const defaultRealtimeData = {
	versions: {
		news: -1,
	},
	notifications: [] as IXRRealtimeNotification[],
	lastUpdates: {} as Record<string, string>,
	playerStatus: [] as string[],
};

type IRealtimeState = typeof defaultRealtimeData;

export const getLiveVersion = createDebouncedAsyncAction<IRealtimeState>(
	'realtime/getLiveVersion',
	() => {
		return realtimeApi('client/GetLiveVersion');
	}
);

export const getRealtimeEvents = createDebouncedAsyncAction<IRealtimeState>(
	'realtime/getRealtimeEvents',
	() => {
		return realtimeApi('client/GetRealtimeEvents', {
			PlayFabId: getXrApi().GetPlayFabId(),
		});
	}
);

const realtime = createSlice({
	name: 'realtime',
	reducers: {
		setPlayerStatus: (state:IRealtimeState, action) => {
			const payload:string[] = action.payload;

			return {
				...state,
				playerStatus: [ ...state.playerStatus, ...payload],
			};
		},
	},
	extraReducers: (builder) => {
		builder.addCase(getLiveVersion.actions.fulfilled, (state, action) => {
			if (state.versions.news !== action.payload.news) {
				state.versions.news = action.payload.news;
			}
		});
		builder.addCase(getRealtimeEvents.actions.fulfilled, (state, action) => {
			const payload = action.payload[`playstream/${PLAYFAB_CONFIG.appId}/${getXrApi().GetPlayFabId()}`] || {};

			if (state.playerStatus.length === 0 && Object.keys(payload).length === 0) return state;
			const playerStatus = Object.entries(payload).reduce((acc, [status, lastUpdate]) => {
				if (lastUpdate !== state.lastUpdates[status]) {
					acc.push(status);
				}
				return acc;
			}, [] as string[]);

			return {
				...state,
				lastUpdates: { ...payload },
				playerStatus,
			};
		});
	},
	initialState: defaultRealtimeData,
});

export default realtime;

export const { setPlayerStatus } = realtime.actions;