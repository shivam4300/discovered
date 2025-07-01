import { useAppDispatch } from '../../../redux/config/store';
import { consumeInventoryItem } from '../../../redux/playfab';

export default function useConsumable() {
	const dispatch = useAppDispatch();
	async function consumeItem(i) {
		dispatch(
			consumeInventoryItem({
				ItemInstanceId: i.ItemInstanceId,
				ConsumeCount: 1,
			})
		);
	}

	return {
		consumeItem,
	};
}
