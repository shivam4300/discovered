import { useEffect, useMemo } from 'react';
import {
	IRootState,
	useAppSelector,
	useAppDispatch,
} from '../redux/config/store';
import { getItemInventory } from '../redux/inventory';
import { notificationGenerator } from '../redux/notifications';
import usePlayerInformation from '../components/profile/hooks/usePlayerInformation';
import getUserId from '../utils/getUserId';

export default function useNotifications() {
	const dispatch = useAppDispatch();
	const notifications = useAppSelector((state) => state.notifications.list);
	const inventory = useAppSelector((state) => state.inventory);

	const { PlayFabId } = usePlayerInformation();

	useEffect(() => {
		if (PlayFabId) {
			dispatch(getItemInventory());
		}
	}, [PlayFabId, dispatch]);

	const notification = useMemo(() => {
		let itemNotifs = inventory
			.filter(
				(n) =>
					n.type.title == 'Notifications' && Boolean(n.playfab.InstanceData)
			)
			.map((n) =>
				notificationGenerator({
					title: n.playfab.InstanceData?.Title,
					message: n.playfab.InstanceData?.Message,
					type: 'Items',
					entityId: n.playfab.ItemInstanceId,
				})
			);

		const arr = [
			...JSON.parse(
				localStorage.getItem(`notifications_${getUserId()}`) || '[]'
			),
			...itemNotifs,
		];

		arr.sort(
			(a, b) =>
				new Date(a.Timestamp).getTime() - new Date(b.Timestamp).getTime()
		);

		return arr.reverse();
	}, [notifications, inventory]);

	return notification;
}
