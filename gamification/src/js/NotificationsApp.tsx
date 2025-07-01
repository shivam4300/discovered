import { useCallback, useEffect,useState } from 'react';
import { useAppDispatch } from './redux/config/store';
import useNotifications from './hooks/useNotifications';
import { removeNotification } from './redux/notifications';
import { raw } from './utils/textUtils';
import useConsumable from './components/inventory/hooks/useConsumable';

export default function NotificationsApp() {
	const { consumeItem } = useConsumable();
	const dispatch = useAppDispatch();

	const notification = useNotifications();

	console.log('app', notification);

	const onClose = useCallback(
		(id) => {
			if (notification) {
				dispatch(removeNotification(id));
			}
		},
		[dispatch, notification]
	);

	//
	const onClick = useCallback(
		(notif) => {
			onClose(notif.NotificationId);

			if (notif.type == 'Items') {
				consumeItem({
					ItemInstanceId: notif.EntityId,
				});
			}

			if (notif?.Link) {
				setTimeout(() => {
					window.location.href = notif.Link;
				}, 500);
			}
		},
		[consumeItem, onClose]
	);


	const [totalNoti, setTotalNoti] = useState(0);
	useEffect(() => {
		let dis_remove_notifs = document.getElementById('conf_btn');
		let dis_clear_notifs = document.querySelector('.ClearMyNotification') as HTMLElement;
		let dis_count_notifs = document.querySelector(
			'.show_notification .NotiCount'
		) as HTMLElement;

		let total_noti = Number(dis_count_notifs.getAttribute('dis-notif-count')) + notification.length;

		dis_count_notifs.innerHTML = total_noti.toString(); 

		if (!total_noti) {
			dis_clear_notifs.style.display = 'none';
			dis_count_notifs.style.display = 'none';
		} else {
			dis_clear_notifs.style.display = 'block';
			dis_count_notifs.style.display = 'block';
		}

		setTotalNoti(total_noti);

		let delete_notif = () => {
			notification.forEach((notif) => {
				dispatch(removeNotification(notif.NotificationId));

				if (notif.type === 'Items') {
					consumeItem({
						ItemInstanceId: notif.EntityId,
					});
				}
			});
		};

		dis_remove_notifs?.addEventListener('click', delete_notif);


		return () => {
			dis_remove_notifs?.removeEventListener('click', delete_notif);
		};
	}, [consumeItem, dispatch, notification]);

	if (!totalNoti)
	return (
		<div className="text-center gam-no-noti-data">
			No New Notification Available.
		</div>
	);

	return (
		<>
			{
				notification.length?
				<div className="gam-notifications noti_data">
					{notification?.map(
						(notif) =>
							notif?.Title && (
								<div
									className="noti_wrapper"
									key={notif.NotificationId}
									onClick={() => onClick(notif)}
								>

										<div className="scale">
											{notif?.Icon ? (
												<div className="noti_img">
													<img src={notif?.Icon} />
												</div>
											) : (
												<div className="noti_img">
													<img src="/repo/images/mini_logo.webp" />
												</div>
											)}
											<div className="content">
												<div className="info">
													{notif?.Title && (
														<div className="title" {...raw(notif?.Title)} />
													)}
													{notif?.Message && (
														<div className="text" {...raw(notif?.Message)} />
													)}
												</div>
											</div>
										</div>
										{notif?.Timestamp && (
											<p className="text">
												{new Intl.DateTimeFormat('en-US').format(
													new Date(notif?.Timestamp)
												)}
											</p>
										)}
								</div>
							)
					)}
				</div>
				: ''
			}
		</>
	);
}
