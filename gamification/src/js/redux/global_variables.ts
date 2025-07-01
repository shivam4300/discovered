import { createSlice } from '@reduxjs/toolkit';
import { getXrApi } from '../api/apiBridge';
import createDebouncedAsyncAction from './async/createDebouncedAsyncAction';
import xrAction from './async/xrAction';

export const getGlobalVariables = createDebouncedAsyncAction(
	'playfab-xr/getGlobalVariables',
	xrAction(() => {
		return getXrApi().Client.GetGlobalVariable();
	})
);

const initialState = {
	isLoaded: false,
	xpLevels: [] as number[],
	seasonPassXpLevels: [] as number[],
	RarityOrder: [] as string[],
	currencyIconMap: {} as Record<string, string>,
	relatedStats: [] as string[][],
	pages: [] as Record<string, any>[],
	EarnOrBurn: {
		requiredPointsToBurn: 0,
		rewardsForWatching: 0,
	},
	AdPolls: {
		showAdPollsInTimes: 15,
	},
	WeeklyChallengesReset: {
		day: 0,
		hour: 0,
	},
	PreviousWeekLeaderboard: [] as LeaderboardEntry[],
	SignUpContentIcon: {} as Record<string, string>,
	SignUpContentEmerging: {} as Record<string, string>,
	SignUpContentBrand: {} as Record<string, string>,
	SignUpContentFan: {} as Record<string, string>,
	SignUpContentCommon: {} as Record<string, string>,
	CreatorPinsSection: {} as Record<string, string>,
	BadgesSection: {} as Record<string, string>,
	WeeklyLeaderboardModal: {} as Record<string, string>,
	WeeklyLeaderboardChallenge: {} as Record<string, string>,
	WeeklyLeaderboardHelper: {} as Record<string, string>,
	CreatorProfileTutorial: {} as Record<string, string>,
	FanProfileTutorial: {} as Record<string, string>,
	FeaturedVideosHelper: {} as Record<string, string>,
};

const global_variables = createSlice({
	name: 'global_variables',
	reducers: {},
	extraReducers: (builder) => {
		builder.addCase(getGlobalVariables.actions.fulfilled, (state, action) => {
			return {
				...state,
				isLoaded: true,
				...action.payload.data.GlobalVariables.reduce((acc, curr) => {
					acc[curr.dataKey] = curr.dataVal;
					return acc;
				}, {} as Record<string, any>),
			};
		});
	},
	initialState,
});

export default global_variables;
