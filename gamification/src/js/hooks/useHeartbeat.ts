import { useEffect } from 'react';
import { useAppDispatch } from '../redux/config/store';
import { writePlayerEvent } from '../redux/playfab';
import usePlayerInformation from '../components/profile/hooks/usePlayerInformation';

export default function useHeartbeat() {
	const { PlayFabId: playerId } = usePlayerInformation();
	const dispatch = useAppDispatch();

	useEffect(() => {
		let interval;

		if (playerId) {
			interval = setInterval(() => {
				dispatch(writePlayerEvent({
					name: 'heartbeat',
					body: {},
				}));
			}, 10000);
		}

		return () => {
			clearInterval(interval);
		};
	}, [playerId, dispatch]);
}