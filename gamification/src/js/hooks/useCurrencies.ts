import { useSelector } from 'react-redux';
import { IRootState, useAppDispatch } from '../redux/config/store';
import { writePlayerEvent } from '../redux/playfab';
import useGlobalVariables from './useGlobalVariables';

export default function useCurrencies() {
	const dispatch = useAppDispatch();
	const wallet = useSelector((state:IRootState) => state.playfab.currencies);
	const { currencyIconMap } = useGlobalVariables();

	function grantCurrency(currencyId: string) {
		dispatch(writePlayerEvent({ name: `grant_${currencyId}_currency` }));
	}

	const formattedWallet = Object.keys(wallet).reduce((acc, curr) => {
		acc[curr] = {
			amount: wallet[curr],
			icon: currencyIconMap[curr],
		};
		return acc;
	}, {} as Record<string, { amount: number, icon: string }>);

	return {
		wallet: formattedWallet,
		grantCurrency,
	};
}