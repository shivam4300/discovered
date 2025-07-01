import { useEffect } from 'react';
import { useAppDispatch, useAppSelector } from '../redux/config/store';
import { deleteItem } from '../redux/inventory';

export default function useInventoryExpiration() {
	const inventory = useAppSelector((state) => state.inventory);
	const dispatch = useAppDispatch();
	
	useEffect(() => {
		const timeouts = inventory.reduce((list, item) => {
			if (item.playfab.Expiration) {
				const date = new Date(item.playfab.Expiration);
				const ms = date.getTime() - Date.now();
				list.push(setTimeout(() => {
					dispatch(deleteItem(item.playfab.ItemInstanceId));
				}, ms));
			}
			return list;
		}, []);

		return () => {
			timeouts.forEach(timeout => clearTimeout(timeout));
		};
	}, [inventory, dispatch]);
}