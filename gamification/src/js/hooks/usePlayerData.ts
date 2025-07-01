import { useAppSelector } from '../redux/config/store';

export default function usePlayerData() {
	const playerData = useAppSelector((state) => state.playerData.data);
	const isPlayerDataLoaded = useAppSelector((state) => state.playerData.loaded);

	return {
		playerData,
		isPlayerDataLoaded,
	};
}