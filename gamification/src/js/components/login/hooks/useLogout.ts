import { useDispatch } from 'react-redux';
import { getXrApi } from '../../../api/apiBridge';

export function useLogout() {
	const dispatch = useDispatch();

	function logout() {
		dispatch({ type: 'USER_LOGOUT' });
		getXrApi()
			.Auth.Logout();
	}

	return {
		logout,
	};
}
