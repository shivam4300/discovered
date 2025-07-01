import { removeBadgeNotification } from '../redux/badgeNotifications';
import { useAppSelector, useAppDispatch } from '../redux/config/store';
import useBadges from './useBadges';

export default function useBadgeNotifications() {
	const dispatch = useAppDispatch();
	const badges = useBadges();
	const notifications = useAppSelector((state) => state.badgeNotifications.list);

	return {
		notifications: notifications.map((itemId) => {
			return badges.find((badge) => badge.itemId === itemId);
		}),

		removeNotification: (id: string) => {
			dispatch(removeBadgeNotification(id));
		},
	};
}
