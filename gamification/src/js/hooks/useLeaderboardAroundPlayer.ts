import { useEffect } from 'react';
import { useAppDispatch, useAppSelector } from '../redux/config/store';
import { IPlayerStatistics } from '../redux/statistics';
import { getLeaderboardAroundPlayer } from '../redux/leaderBoardAroundPlayer';
import usePlayerInformation from '../components/profile/hooks/usePlayerInformation';

export default function useLeaderboardAroundPlayer(
	statisticName: keyof IPlayerStatistics,
	forceUpdate = false
) {
	const leaderBoardAroundPlayer = useAppSelector(
		(state) => state.leaderBoardAroundPlayer[statisticName]
	);

	const { PlayFabId: playerId } = usePlayerInformation();

	const dispatch = useAppDispatch();

	useEffect(() => {
		if (playerId && (!leaderBoardAroundPlayer || forceUpdate)) {
			dispatch(getLeaderboardAroundPlayer({ StatisticName: statisticName }));
		}
	}, [statisticName, playerId, dispatch, leaderBoardAroundPlayer, forceUpdate]);

	return {
		updateLeaderboardAroundPlayer: () =>
			dispatch(getLeaderboardAroundPlayer({ StatisticName: statisticName })),
		leaderBoardAroundPlayer,
	};
}
