import { createSlice } from '@reduxjs/toolkit';
import { getXrApi } from '../api/apiBridge';
import createDebouncedAsyncAction from './async/createDebouncedAsyncAction';
import xrAction from './async/xrAction';
import { IPlayerStatistics } from './statistics';

export const getLeaderboardAroundPlayer = createDebouncedAsyncAction(
	'playfab-xr/getLeaderboardAroundPlayer',
	xrAction(({ StatisticName }: { StatisticName: keyof IPlayerStatistics }) =>
		getXrApi().Client.GetLeaderboardAroundPlayer({
			StatisticName,
			MaxResultsCount: 1,
			ProfileConstraints: { ShowAvatarUrl: true, ShowDisplayName: true },
		})
	)
);

const leaderBoardAroundPlayer = createSlice({
	name: 'leaderBoardAroundPlayer',
	initialState: {} as Record<keyof IPlayerStatistics, LeaderboardEntry[]>,
	reducers: {},
	extraReducers: (builder) => {
		builder.addCase(
			getLeaderboardAroundPlayer.actions.fulfilled,
			(state, action) => {
				state[action.meta.arg.StatisticName] =
					action.payload.data?.Leaderboard || [];
			}
		);
	},
});

export default leaderBoardAroundPlayer;
