import usePlayerInformation from "../components/profile/hooks/usePlayerInformation";
import { useAppSelector } from "../redux/config/store";
import useCatalog from "./useCatalog";

export default function useAppReady() {
	let isReady = false;

	const { PlayFabId: playerId } = usePlayerInformation();
	const { isLoaded: globalVariablesLoaded } = useAppSelector((state) => state.global_variables);
	
	const catalog = useCatalog();
	const catalogLoaded = Boolean(catalog.items.length);

	isReady = Boolean(playerId && globalVariablesLoaded && catalogLoaded);
	
	return isReady;
}