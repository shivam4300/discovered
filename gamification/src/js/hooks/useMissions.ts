import { useEffect } from 'react';
import { useAppDispatch, useAppSelector } from '../redux/config/store';
import { getMissionInventory } from '../redux/missions';
import usePlayerInformation from '../components/profile/hooks/usePlayerInformation';


export default function useMissions(filter:string = null, forceRefresh = false) {
	const { loaded, list:missions } = useAppSelector((state) => state.missions);
	const dispatch = useAppDispatch();
	const { PlayFabId } = usePlayerInformation();

	useEffect(() => {
		if (PlayFabId && (!loaded || forceRefresh)) {
			dispatch(getMissionInventory());
		}
	}, [missions, dispatch, PlayFabId, forceRefresh, loaded]);

	if (!filter) return missions;
	return missions.filter(mission => mission.type.title === filter);
}