import { useState, useEffect } from 'react';
import usePolls from '../../../hooks/usePolls';

export default function useGroupedPolls(customId: string) {
	const { polls, answers, answerPolls } = usePolls();
	const [groupedPolls, setGroupedPolls] = useState<XRPoll[]>([]);

	useEffect(() => {
		setGroupedPolls(
			polls.filter(
				(p) =>
					p.hasAnswered === false &&
					p.poll.customData.groupPollsId === customId &&
					!answers?.[p.instanceId]
			)
		);
	}, [polls, answers, customId]);

	console.log('groupedPolls', groupedPolls, polls);

	return {
		groupedPolls,
		answers,
		answerPolls,
	};
}
