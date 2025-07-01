import { useEffect } from "react";
import { MISSIONS_TYPES } from "../Constants";
import useGlobalVariables from "./useGlobalVariables";
import useMissions from "./useMissions";
import { useAppDispatch, useAppSelector } from "../redux/config/store";
import { writePlayerEvent } from "../redux/playfab";
import usePlayerInformation from "../components/profile/hooks/usePlayerInformation";

function getNextDayAtHour(day: number, hour: number): Date {
	var d = new Date();
	d.setUTCDate(d.getUTCDate() + (((day + 7 - d.getUTCDay()) % 7) || 7));
	d.setUTCHours(hour, 0, 0, 0);
	return d;
}

export default function useWeeklyChallenges(forceRefresh = false) {
	const {
		WeeklyChallengesReset: {
			hour: resetHour,
			day: resetDay,
		},
	} = useGlobalVariables();

	const { PlayFabId } = usePlayerInformation();
	console.log(PlayFabId,'PlayFabId');
	
	const dispatch = useAppDispatch();
	const missionsLoaded = useAppSelector(s => s.missions.loaded);
	const weeklyChallenges = useMissions(MISSIONS_TYPES.WEEKLY_DISCOVERY_CHALLENGE, forceRefresh);
	console.log(missionsLoaded,weeklyChallenges,'weeklyChallenges');
	
	useEffect(() => {
		if (missionsLoaded && PlayFabId && weeklyChallenges.find(mission => !mission.PlayerStatus)) {
			dispatch(writePlayerEvent({
				name: 'player_fetch_weekly_challenges',
			}));
		}
	}, [missionsLoaded, weeklyChallenges, PlayFabId, dispatch]);

	var challengesEndDate = getNextDayAtHour(resetDay, resetHour);

	return {
		weeklyChallenges,
		challengesEndDate,
	}
}