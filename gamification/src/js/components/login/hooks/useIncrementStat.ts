import { useAppDispatch, useAppSelector } from "../../../redux/config/store";
import { executeCloudScript } from "../../../redux/playfab";
import { updateLocalStatistic } from "../../../redux/statistics";

export default function useIncrementStat() {
	const dispatch = useAppDispatch();
	const stats = useAppSelector(state => state.statistics);
	
	return async (statName, value) => {
		dispatch(updateLocalStatistic({ name: statName, value: stats[statName] + value }));
		dispatch(executeCloudScript({ functionName: 'IncrementStat', data: { StatisticName: statName, Value: value } }));
	}
}