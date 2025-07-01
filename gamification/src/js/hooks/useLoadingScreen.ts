import { useAppDispatch, useAppSelector } from "../redux/config/store";
import { setLoading } from "../redux/loadingScreen";

export default function useLoadingScreen() {
	const dispatch = useAppDispatch();
	const isLoading = useAppSelector(state => state.loadingScreen.isLoading);

	function toggleLoadingScreen() {
		dispatch(setLoading(!isLoading));
	}

	return {
		isLoading,
		toggleLoadingScreen,
	};
}