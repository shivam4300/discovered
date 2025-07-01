import { useAppDispatch } from '../../../redux/config/store';
import { authPlayfab } from '../../../redux/playfab';

export default function useLogin() {
	const appDispatch = useAppDispatch();

	async function login(jwt: string) {
		appDispatch(authPlayfab({ jwt: jwt }));
	}

	return {
		login,
	};
}
