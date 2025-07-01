/* eslint-disable @typescript-eslint/space-before-function-paren */
import { PayloadAction, createSlice } from '@reduxjs/toolkit';
import { getXrApi } from '../api/apiBridge';
import createDebouncedAsyncAction from './async/createDebouncedAsyncAction';
import xrAction from './async/xrAction';

export const getPolls = createDebouncedAsyncAction(
	'playfab-xr/getPolls',
	xrAction(() => getXrApi().Client.GetPoll())
);

export const getPoll = createDebouncedAsyncAction(
	'playfab-xr/getSinglePoll',
	xrAction((InstanceId: string) => getXrApi().Client.GetPoll({ InstanceId }))
);

export const answerPoll = createDebouncedAsyncAction(
	'playfab-xr/answerPoll',
	xrAction(
		async ({ InstanceId, AnswerId, MatchId = null, TimeToAnswer = null }) => {
			const answer = await getXrApi().Client.AnswerPoll({
				InstanceId,
				AnswerId,
				MatchId,
				TimeToAnswer,
			});
			return answer;
		}
	)
);

export const getPollResults = createDebouncedAsyncAction(
	'playfab-xr/GetPollResults',
	xrAction(({ InstanceId }) => getXrApi().Client.GetPollResults({ InstanceId }))
);

type RealtimePoll = {
	PollInstance: string;
	PollTimestamp: string;
	PollVoteExpiration: null;
	PollExpiration: null;
	PollItemId: string;
	PollQuestion: string;
	PollChoices: { id: string; label: string }[];
	PollCustomData: any;
};

export function generatePollFromRealtime(data: RealtimePoll): XRPoll {
	return {
		expiration: new Date(data.PollExpiration).valueOf() || null,
		instanceId: data.PollInstance,
		matchId: '',
		poll: {
			customData: data.PollCustomData,
			itemId: data.PollItemId,
			choices: data.PollChoices,
			question: data.PollQuestion,
		},
		timestamp: data.PollTimestamp,
		voteExpiration: new Date(data.PollVoteExpiration).valueOf() || null,
		hasAnswered: false,
	};
}

const polls = createSlice({
	name: 'polls',
	initialState: {
		isLoaded: false,
		polls: [] as XRPoll[],
		votes: {} as Record<string, XRPollPlayerVote>,
		results: {} as Record<string, XRPollResult[]>,
		rewards: 0 as Number,
	},
	reducers: {
		addLocalPoll: (state, action: PayloadAction<XRPoll>) => {
			state.polls = [...state.polls, action.payload];
		},
		removeLocalPoll: (state, action: PayloadAction<XRPoll>) => {
			state.polls = state.polls.filter(
				(p) => p.instanceId !== action.payload.instanceId
			);
		},
		setRewards: (state, action: PayloadAction<Number>) => {
			state.rewards = action.payload;
		},
	},
	extraReducers: (builder) => {
		builder.addCase(getPolls.actions.fulfilled, (state, action) => {
			state.polls = [
				...action.payload.data.Polls.filter(
					(p) => !p.expiration || new Date((p.expiration).toString().replace(/:([0-9]{3}Z)/, '.$1')).valueOf() > Date.now()
				).map(p => ({
					...p,
					expiration: (p.expiration)?.toString()?.replace(/:([0-9]{3}Z)/, '.$1'),
				})),
			];
		});
		builder.addCase(getPoll.actions.fulfilled, (state, action) => {
			state.polls = [
				...state.polls.filter(
					(p) => p.instanceId !== action.payload.data.Poll.instanceId
				),
				action.payload.data.Poll,
			];
		});

		builder.addCase(answerPoll.actions.fulfilled, (state, action) => {
			state.results[action.meta.arg.InstanceId] =
				action.payload.data.PollResults;

			state.votes[action.meta.arg.InstanceId] = action.payload.data.PlayerVote;
		});

		builder.addCase(answerPoll.actions.rejected, (state, action) => {
			if (action.payload.code === 400) {
				state.polls = [
					...state.polls.filter(
						(p) => p.instanceId !== action.payload.params.InstanceId
					),
				];
			}
		});

		builder.addCase(getPollResults.actions.fulfilled, (state, action) => {
			state.results[action.meta.arg.InstanceId] =
				action.payload.data.PollResults;
		});
	},
});

export default polls;

export const { addLocalPoll, removeLocalPoll, setRewards } = polls.actions;
