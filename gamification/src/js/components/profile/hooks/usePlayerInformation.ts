import usePlayerData from "../../../hooks/usePlayerData";
import { useAppSelector } from "../../../redux/config/store";

export default function usePlayerInformation() {
	const statistics = useAppSelector(state => state.statistics);
	const { playerData, isPlayerDataLoaded } = usePlayerData();

	const {
		PlayFabId,
		DisplayName,
		AvatarUrl,
		SessionTicket,
	} = useAppSelector((state) => state.playfab);

	return {
		statistics,
		playerData,
		isPlayerDataLoaded,
		PlayFabId,
		DisplayName,
		AvatarUrl,
		SessionTicket,
	};
}