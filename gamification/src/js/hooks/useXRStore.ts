import { useState } from "react";
import { useAppDispatch, useAppSelector } from "../redux/config/store";
import { getStoreLoadout, purchaseStoreItem } from "../redux/playfab";
import useCatalog from "./useCatalog";

export default function useXRStore(storeName: string) {
	const dispatch = useAppDispatch();
	const store = useAppSelector(state => state.xr_store.loadout.find(x => x.name === storeName));
	const [canPurchase, setCanPurchase] = useState<boolean>(true);

	const catalog = useCatalog();

	const tiles = store?.tiles?.map((tile:IXRStoreTile) => ({
		...tile,
		item: catalog.items.find(x => x.playfab.ItemId === tile.content.ItemId),
	}))

	async function purchaseTile(TileId: string, CurrencyCode:string) {
		setCanPurchase(false);
		const res = await dispatch(purchaseStoreItem({ TileId, CurrencyCode }));
		if (res.payload.data.RequiresRefresh) {
			await dispatch(getStoreLoadout());
			setCanPurchase(true);
		}
	}

	return {
		store,
		tiles: tiles || [],
		purchaseTile,
		canPurchase,
	};
}