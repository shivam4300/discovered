import { useAppDispatch } from "../../../redux/config/store";
import { executeCloudScript } from "../../../redux/playfab";

export default function useResetPlayer() {
	const dispatch = useAppDispatch();

	return {
		resetPlayer: () => {
			dispatch(executeCloudScript({ functionName: 'ResetPlayer' }));
		}
	}
}