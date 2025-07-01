/* eslint-disable space-before-function-paren */
import { useEffect } from 'react';
import { useAppDispatch, useAppSelector } from '../redux/config/store';
import { answerPoll, getPolls } from '../redux/polls';
import { updatePlayerData } from '../redux/playerData';
import { PayloadAction } from '@reduxjs/toolkit';
import usePlayerInformation from '../components/profile/hooks/usePlayerInformation';

export default function usePolls() {
	const { polls, votes, results, isLoaded } = useAppSelector(
		(state) => state.polls
	);

	const { PlayFabId: playerId } = usePlayerInformation();

	const dispatch = useAppDispatch();

	useEffect(() => {
		if (!isLoaded && playerId) {
			dispatch(getPolls());
		}
	}, [dispatch, isLoaded, playerId]);

	const { pollAnswers: answers } = useAppSelector(
		(state) => state.playerData?.data || {}
	);

	async function answerPolls(pollsAnswers: [string, string][]) {
		const a = pollsAnswers.map(([instanceId, choiceId]) => {
			const resp = dispatch(
				answerPoll({ InstanceId: instanceId, AnswerId: choiceId })
			);
			return resp;
		});

		return Promise.all(a).then(
			(
				responses: PayloadAction<
					GenericApiCallResponse<AnswerPollResponse>,
					any,
					any
				>[]
			) => {
				dispatch(
					updatePlayerData({
						pollAnswers: {
							...answers,
							...responses.reduce((acc, resp, index) => {
								const correctAnswer =
									resp.payload.data?.PlayerVote?.RightAnswers?.[0]?.id;

								return {
									...acc,
									[resp.meta.arg.InstanceId]: {
										choice: resp.payload.data?.PlayerVote.Answer,
										correctAnswer,
									},
								};
							}, {}),
						},
					})
				);

				return responses;
			}
		);
	}

	return {
		polls,
		votes,
		results,
		answers,
		answerPolls,

		answerPoll: async (pollInstanceId: string, choiceId: string) => {
			return answerPolls([[pollInstanceId, choiceId]]);
		},
		refresh: () => dispatch(getPolls()),
	};
}
