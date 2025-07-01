import { useEffect } from 'react';
import { getCatalog } from '../redux/catalog';

import { useAppDispatch, useAppSelector } from '../redux/config/store';
import { getGlobalVariables } from '../redux/global_variables';
import { getItemInventory } from '../redux/inventory';
import { getPlayerCombinedInfo } from '../redux/playfab';
import { getPolls } from '../redux/polls';

export default function usePlayFab() {
	const {
		PlayFabId,
		DisplayName,
		AvatarUrl,
		currencies,
	} = useAppSelector((state) => state.playfab);
	
	const alreadyLoaded = Boolean(DisplayName);

	const dispatch = useAppDispatch();

	useEffect(() => {
		if (!alreadyLoaded && PlayFabId) {
			console.log('usePlayFab', PlayFabId);
			dispatch(getItemInventory());
			dispatch(getCatalog());
			dispatch(getPlayerCombinedInfo());
			dispatch(getGlobalVariables());
			dispatch(getPolls());
		}
	}, [PlayFabId, alreadyLoaded, dispatch]);

	return {
		playerId: PlayFabId,
		currencies,
		DisplayName,
		AvatarUrl,
	};
}