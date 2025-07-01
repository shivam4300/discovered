import { createSlice } from '@reduxjs/toolkit';
import { getXrApi } from '../api/apiBridge';
import createDebouncedAsyncAction from './async/createDebouncedAsyncAction';
import xrAction from './async/xrAction';
import { IPlayerStatistics } from './statistics';

export const getLeaderboard = createDebouncedAsyncAction(
	'playfab-xr/getLeaderboard',
	xrAction(({ StatisticName }: { StatisticName: keyof IPlayerStatistics }) =>
		getXrApi().Client.GetLeaderboard({
			StatisticName,
			StartPosition: 0,
			ProfileConstraints: { ShowAvatarUrl: true, ShowDisplayName: true },
		})
	)
);

const leaderboard = createSlice({
	name: 'leaderboard',
	initialState: {} as Record<keyof IPlayerStatistics, LeaderboardEntry[]>,
	reducers: {},
	extraReducers: (builder) => {
		builder.addCase(getLeaderboard.actions.fulfilled, (state, action) => {
			state[action.meta.arg.StatisticName] =
				action.payload.data?.Leaderboard || [];
		});
	},
});

export default leaderboard;
