import { createSlice } from '@reduxjs/toolkit';
import createDebouncedAsyncAction from './async/createDebouncedAsyncAction';
import { getPlayerCombinedInfo } from './playfab';
import xrAction from './async/xrAction';
import { getXrApi } from '../api/apiBridge';

export type IPlayerStatistics = {
	xp: number;
	points: number;
	level: number;
	stars: number;
	user_type: number;
	player_received_pinhead: number;
	player_completed_fan_tutorial: number;
	player_completed_creator_tutorial: number;
	player_answered_profilepolls: number;
};

export const getPlayerStatistics = createDebouncedAsyncAction(
	'playfab-xr/GetPlayerStatistics',
	xrAction(getXrApi().Client.GetPlayerStatistics)
);

const defaultPlayerStatistics: IPlayerStatistics = {
	xp: 0,
	points: 0,
	level: 0,
	stars: 0,
	user_type: 0,
	player_received_pinhead: 0,
	player_completed_fan_tutorial: 0,
	player_completed_creator_tutorial: 0,
	player_answered_profilepolls: 0,
};

const statistics = createSlice({
	name: 'statistics',
	reducers: {
		updateLocalStatistic: (
			state,
			action: { payload: { name: keyof IPlayerStatistics; value: number } }
		) => {
			const { name, value } = action.payload;
			state[name] = value;
		},
	},
	extraReducers: (builder) => {
		builder.addCase(getPlayerStatistics.actions.fulfilled, (state, action) => {
			const newStats = action.payload.data?.Statistics?.reduce((c, stat) => {
				c[stat.StatisticName] = stat.Value;
				return c;
			}, {} as Record<string, number>);

			return {
				...state,
				...newStats,
			};
		});
		builder.addCase(
			getPlayerCombinedInfo.actions.fulfilled,
			(state, action) => {
				const newStats =
					action.payload.data?.InfoResultPayload?.PlayerStatistics?.reduce(
						(
							c: IPlayerStatistics,
							stat: { StatisticName: keyof IPlayerStatistics; Value: number }
						) => {
							c[stat.StatisticName] = stat.Value;
							return c;
						},
						{}
					);

				return {
					...state,
					...newStats,
				};
			}
		);
	},
	initialState: defaultPlayerStatistics,
});

export default statistics;

export const { updateLocalStatistic } = statistics.actions;
