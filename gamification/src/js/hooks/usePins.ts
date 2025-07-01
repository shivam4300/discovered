import { ITEM_CLASSES } from '../Constants';
import { useAppSelector } from '../redux/config/store';
import { useMemo } from 'react';

export default function usePins() {
	const inventory = useAppSelector((state) => state.inventory);

	return useMemo(() => {
		return inventory.filter(
			(item) => item.playfab.ItemClass === ITEM_CLASSES.PINS
		);
	}, [inventory]);
}
