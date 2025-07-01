import { useMemo } from "react";
import { useAppSelector } from "../redux/config/store";
import useCatalog from "./useCatalog";

type useInventoryCatalogItemsOptions = {
	includeItemClasses?: string[];
	excludeItemClasses?: string[];
}

/**
 * Merges inventory data with catalog data. Inventory data takes precendence.
 */
export default function useInventoryCatalogItems({
	includeItemClasses,
	excludeItemClasses,
}: useInventoryCatalogItemsOptions = {}) {
	const inventory = useAppSelector((state) => state.inventory);
	const catalog = useCatalog();

	return useMemo(() => {
		const arr:IXRInventoryItemParsedData[] = inventory.reduce((carry, item) => {
			if (includeItemClasses && includeItemClasses.indexOf(item.playfab.ItemClass) === -1) return carry;
			if (excludeItemClasses && excludeItemClasses.indexOf(item.playfab.ItemClass) > -1) return carry;

			const matchingCatalogItem = catalog.items.find(x => {
				return x.itemId === item.itemId;
			});

			const mergedItem = {
				...item,
				...matchingCatalogItem,
				playfab: {
					...matchingCatalogItem?.playfab,
					...item.playfab,
				},
				data: {
					...matchingCatalogItem?.data,
					...item.data,
				}
			};
			
			carry.push(mergedItem);

			return carry;
		}, []);

		return arr;
	}, [inventory, catalog, includeItemClasses, excludeItemClasses]);
}