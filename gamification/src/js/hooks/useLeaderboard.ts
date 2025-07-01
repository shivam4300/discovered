import { useEffect } from 'react';
import { useAppDispatch, useAppSelector } from '../redux/config/store';
import { IPlayerStatistics } from '../redux/statistics';
import { getLeaderboard } from '../redux/leaderBoard';
import usePlayerInformation from '../components/profile/hooks/usePlayerInformation';

export default function useLeaderboard(statisticName: keyof IPlayerStatistics, forceUpdate = false) {
	const leaderboard = useAppSelector(
		(state) => state.leaderBoard[statisticName]
	);

	const { PlayFabId: playerId } = usePlayerInformation();

	const dispatch = useAppDispatch();

	useEffect(() => {
		if (playerId && (!leaderboard || forceUpdate) ) {
			dispatch(getLeaderboard({ StatisticName: statisticName }));
		}
	}, [statisticName, playerId, dispatch, leaderboard, forceUpdate]);

	return {
		updateLeaderboard: () =>
			dispatch(getLeaderboard({ StatisticName: statisticName })),
		leaderboard,
	};
}
