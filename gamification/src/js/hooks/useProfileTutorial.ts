import { useAppDispatch, useAppSelector } from '../redux/config/store';
import {
	openLeaderboard,
	toggleOverlay,
	updateCreatorState,
	openCreatorChallenge,
	updateTutorialState,
} from '../redux/profileTutorial';

export default function useProfileTutorial() {
	const dispatch = useAppDispatch();
	const leaderboard = useAppSelector(
		(state) => state.profileTutorial.leaderboard
	);

	const creator = useAppSelector((state) => state.profileTutorial.creator);

	function setLeaderboardState(state = false) {
		dispatch(openLeaderboard(state));
	}

	function toggleTutorialOverlayState(state: boolean) {
		dispatch(toggleOverlay(state));
	}

	function toggleCreatorState() {
		dispatch(updateCreatorState(!creator));
	}

	function setChallengeState(active = false) {
		dispatch(openCreatorChallenge(active));
	}

	function setTutorialState(active = false) {
		dispatch(updateTutorialState(active));
	}

	return {
		leaderboard,
		setLeaderboardState,
		toggleTutorialOverlayState,
		toggleCreatorState,
		setChallengeState,
		setTutorialState,
	};
}
