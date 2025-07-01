import { useEffect } from 'react';
import usePlayFab from './hooks/usePlayFab';
import useRealtime from './hooks/useRealtime';
import useLogin from './components/login/hooks/useLogin';
import { getCookie } from './utils/cookies';
import { useLogout } from './components/login/hooks/useLogout';
import useWeeklyChallenges from './hooks/useWeeklyChallenges';

export default function GamificationServices() {
	const { playerId } = usePlayFab();

	const { login } = useLogin();
	const { logout } = useLogout();
	
	if(window.location.pathname != '/media_stream/mstream'){
		useRealtime(); 
	}
	useWeeklyChallenges();

	

	useEffect(() => {
		const jwt = getCookie('AuthTkn');

		if (!playerId && jwt) {
			login(jwt);
		} else if (!jwt) {
			logout();
		}
	}, [playerId, login]);

	return null;
}
