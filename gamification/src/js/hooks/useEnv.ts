import { useAppSelector } from '../redux/config/store';

export default function useEnv() {
	const lang = useAppSelector((state) => state.env.Lang);

	return {
		lang,
	};
}