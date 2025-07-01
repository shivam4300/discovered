import { useEffect } from 'react';
import { useAppDispatch, useAppSelector } from '../redux/config/store';
import { getOtherPlayerProfile } from '../redux/other_players';

export function useOtherPlayerProfile(playFabId:string, forceRefresh = false) {
	const dispatch = useAppDispatch();

	const otherPlayerProfile = useAppSelector((state) => state.other_players.profiles[playFabId]);

	useEffect(() => {
		if (!otherPlayerProfile && playFabId) {
			dispatch(getOtherPlayerProfile(playFabId));
		}
	}, [otherPlayerProfile, playFabId, dispatch]);

	useEffect(() => {
		if (forceRefresh) {
			dispatch(getOtherPlayerProfile(playFabId));
		}
	}, [dispatch, playFabId, forceRefresh]);
	
	return otherPlayerProfile;
}
