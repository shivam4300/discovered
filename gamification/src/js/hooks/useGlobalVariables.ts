import { useEffect } from 'react';
import { useAppDispatch, useAppSelector } from '../redux/config/store';
import { getGlobalVariables } from '../redux/global_variables';
import usePlayerInformation from '../components/profile/hooks/usePlayerInformation';

export default function useGlobalVariables() {
	const dispatch = useAppDispatch();
	const { PlayFabId: playerId } = usePlayerInformation();
	const globalVariables = useAppSelector((state) => state.global_variables);

	useEffect(() => {
		if (!globalVariables.isLoaded && playerId) {
			dispatch(getGlobalVariables());
		}
	}, [globalVariables.isLoaded, playerId, dispatch]);

	return {
		...globalVariables,
		PreviousWeekLeaderboard: Array.isArray(globalVariables.PreviousWeekLeaderboard) ? globalVariables.PreviousWeekLeaderboard : [],
	};
}
