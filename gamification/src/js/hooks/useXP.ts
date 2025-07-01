import { useAppSelector } from '../redux/config/store';
import { statsSelector } from '../selectors/statistics';
import useGlobalVariables from './useGlobalVariables';


export default function useXP() {
	const { level, xp } = useAppSelector(statsSelector);
	const globalVariables = useGlobalVariables();
	const currentLevelXp = globalVariables.xpLevels[level - 1] || 0;
	const nextLevelXp = globalVariables.xpLevels[level] || Number.MAX_SAFE_INTEGER;

	return {
		xp,
		nextLevelXp,
		currentLevelXp,
		level,
	};
}