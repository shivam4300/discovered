import { useMemo } from 'react';
import { ITEM_CLASSES } from '../Constants';
import { useAppSelector } from '../redux/config/store';
//import useGlobalVariables from './useGlobalVariables';

export default function useBadges() {
	const inventory = useAppSelector((state) => state.inventory);
	const catalog = useAppSelector((state) => state.catalog.items);

	//const { RarityOrder } = useGlobalVariables();

	const catalogBadges = catalog.filter(
		(item) => item.playfab.ItemClass === ITEM_CLASSES.BADGE
	);
	const badges = inventory.filter(
		(item) => item.playfab.ItemClass === ITEM_CLASSES.BADGE
	);

	return useMemo(() => {
		const arr = catalogBadges.map((badge) => {
			const foundBadge = badges.find((x) => {
				return x.itemId === badge.itemId;
			});
			const inventoryBadge = foundBadge || { playfab: {} };
			return {
				...badge,
				playfab: {
					...badge.playfab,
					...inventoryBadge.playfab,
				},
				isInInventory: Boolean(foundBadge),
			};
		}) as Badge[];

		//arr.sort((a, b) => {
		//	return (
		//		RarityOrder.indexOf(a.data.Rarity) -
		//			RarityOrder.indexOf(b.data.Rarity) ||
		//		a.playfab.DisplayName.localeCompare(b.playfab.DisplayName)
		//	);
		//});

		return arr;
	}, [catalogBadges, badges, inventory, catalog]);
}
