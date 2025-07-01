import { useEffect } from 'react';
import { useAppDispatch, useAppSelector } from '../redux/config/store';
import { answerPoll, getPollResults } from '../redux/polls';

export default function Polls() {
	const dispatch = useAppDispatch();

	const polls = useAppSelector((state) => state.polls.polls);
	const votes = useAppSelector((state) => state.polls.votes);
	const results = useAppSelector((state) => state.polls.results);

	useEffect(() => {
		polls.forEach((poll) => {
			if (!results[poll.instanceId]) {
				dispatch(getPollResults({ InstanceId: poll.instanceId }));
			}
		});
	}, [polls, dispatch, results]);

	return (
		<div className="polls">
			{polls.map((poll) => {
				const vote = votes[poll.instanceId];

				return (
					<div className="poll" key={poll.instanceId}>
						<h3>
							{poll.poll.question} - {poll.instanceId}
						</h3>

						{poll.poll.choices.map((choice) => {
							const result = results[poll.instanceId]?.find(
								(r) => r.id === choice.id
							);

							return (
								<div className="choice" key={choice.id}>
									<p
										onClick={() =>
											dispatch(
												answerPoll({
													InstanceId: poll.instanceId,
													AnswerId: choice.id,
												})
											)
										}
									>
										{choice.label} {vote?.Answer?.id === choice.id && 'X'}{' '}
										{result && result.results.percent + '% (' + result.results.votes + ')'}
									</p>
								</div>
							);
						})}
					</div>
				);
			})}
		</div>
	);
}
