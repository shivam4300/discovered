/* eslint-disable no-console */
import { useCallback, useEffect } from 'react';
import { API_CONFIG, ITEM_CLASSES } from '../Constants';
import { addChatMessage } from '../redux/chat';
import { useAppDispatch, useAppSelector } from '../redux/config/store';
import { getItemInventory } from '../redux/inventory';
import { getMissionInventory } from '../redux/missions';
import { addNotification } from '../redux/notifications';
import {
	updateLocalAvatarUrl,
	updateLocalVirtualCurrency,
	updateLocalDisplayName,
	executeCloudScript,
	writePlayerEvent,
} from '../redux/playfab';
// import { setActivePrediction, setPredictionResult } from '../redux/predictions';
import { updateLocalStatistic } from '../redux/statistics';
import { getStoreLoadout } from '../redux/xr_store';
import { addLocalPoll, generatePollFromRealtime } from '../redux/polls';
import useRealtimeConnection from '../api/react/hooks/useRealtimeConnection';
import { ANY_EVENT } from '../api/Realtime';
import usePlayerInformation from '../components/profile/hooks/usePlayerInformation';
import { addBadgeNotification } from '../redux/badgeNotifications';
import getUserId from '../utils/getUserId';
import { notificationGenerator } from '../redux/notifications';

function debugRealtime(msg: string) {
	const colors = (msg.match(/%c/g) || []).length;
	const d = new Date();
	const date = `%c @ ${
		d.getHours().toString().padStart(2, '0') +
		':' +
		d.getMinutes().toString().padStart(2, '0') +
		':' +
		d.getSeconds().toString().padStart(2, '0')
	}.${d.getMilliseconds()}`;
	switch (colors) {
		case 0:
			console.log(msg + date);
			break;
		case 1:
			console.log(msg + date, 'color: #ccc;', 'color: grey;');
			break;
		case 2:
			console.log(
				msg + date,
				'color: #ccc;',
				'color: #ffbca0;',
				'color: grey;'
			);
			break;
		case 3:
			console.log(
				msg + date,
				'color: #ccc;',
				'color: #ffbca0;',
				'color: teal;',
				'color: grey;'
			);
			break;
		default:
		case 4:
			console.log(
				msg + date,
				'color: #ccc;',
				'color: #ffbca0;',
				'color: teal;',
				'color: yellow',
				'color: grey;'
			);
			break;
	}
}

export default function useRealtime() {
	const { versions, playerStatus } = useAppSelector((state) => state.realtime);

	const dispatch = useAppDispatch();

	const { PlayFabId: playerId, SessionTicket } = usePlayerInformation();
	console.log(playerId,'playerId');
	
	const onMessage = useCallback(
		(data) => {
			const eventName = data.EventName;

			let msg = `%crealtime %c${eventName}`;

			switch (eventName) {
				case 'player_rule_executed':
					msg = `%crealtime %c${eventName} %c[${data.DisplayName}]`;
					break;
				case 'player_triggered_action_executed_cloudscript':
					msg = `%crealtime %c${eventName} %c[${data.FunctionName}]`;
					break;
				case 'player_action_executed':
					msg = `%crealtime %c${eventName} %c[${data.ActionName}]`;
					break;
				case 'player_entered_segment':
				case 'player_left_segment':
					msg = `%crealtime %c${eventName} %c[${data.SegmentName}]`;
					break;
				case 'player_statistic_changed':
					dispatch(
						updateLocalStatistic({
							name: data.StatisticName,
							value: data.StatisticValue,
						})
					);
					msg = `%crealtime %c${eventName} %c[${data.StatisticName}: ${data.StatisticPreviousValue} > ${data.StatisticValue}]`;
					break;
				case 'player_consumed_item':
				case 'player_inventory_item_added':
					dispatch(getItemInventory());
					msg = `%crealtime %c${eventName} %c[${data.DisplayName}]`;

					if (data.ItemId.includes('m-')) {
						dispatch(getMissionInventory());
					}

					if (data.Class === ITEM_CLASSES.BADGE) {
						dispatch(addBadgeNotification(data.ItemId));
					}
					break;
				case 'player_virtual_currency_balance_changed':
					dispatch(
						updateLocalVirtualCurrency({
							currency: data.VirtualCurrencyName,
							amount: data.VirtualCurrencyBalance,
						})
					);
					msg = `%crealtime %c${eventName} %c[${data.VirtualCurrencyName}: ${data.VirtualCurrencyPreviousBalance} > ${data.VirtualCurrencyBalance}]`;
					// if (data.VirtualCurrencyPreviousBalance < data.VirtualCurrencyBalance) {
					// 	dispatch(addNotification(notificationGenerator({
					// 		title: 'You earned some coins!',
					// 		icon: 'trophy',
					// 		message: `Congratulations! You earned ${data.VirtualCurrencyBalance - data.VirtualCurrencyPreviousBalance} coins!`,
					// 	})));
					// }
					break;
				case 'player_displayname_changed':
					dispatch(updateLocalDisplayName(data.DisplayName));
					msg = `%crealtime %c${eventName} %c[${data.PreviousDisplayName} > ${data.DisplayName}]`;
					break;
				case 'player_changed_avatar':
					dispatch(updateLocalAvatarUrl(data.ImageUrl));
					break;
				case 'player_notification_pushed':
					dispatch(addNotification(data));
					break;
				case 'store':
				case 'player_store_cleared':
					dispatch(getStoreLoadout());
					break;
				case 'notifications':
					dispatch(addNotification(data));
					break;
				case 'player_objective_progress':
				case 'player_objective_completed':
				case 'player_mission_completed':
					dispatch(getMissionInventory());
					msg = `%crealtime %c${eventName} %c[${data.MissionId} > ${data.ObjectiveId}]`;
					break;
				case 'weekly_challenges_updated':
					dispatch(
						writePlayerEvent({
							name: 'player_fetch_weekly_challenges',
						})
					);
					break;
				case 'xr_chat_message':
					dispatch(addChatMessage(data));
					break;
				/*case 'match_prediction_instantiated':
				dispatch(setActivePrediction(data));
				msg = `%crealtime %c${eventName} %c[${data.PredictionData?.displayName}]`;
				break;
			case 'prediction_resolved':
				dispatch(setPredictionResult(data));
				msg = `%crealtime %c${eventName}`;
				break;/** */
				case 'title_poll_instantiated':
					dispatch(addLocalPoll(generatePollFromRealtime(data)));

					dispatch(
						addNotification(
							notificationGenerator({
								title: 'New poll available, click on social page to access it!',
								message: '',
								link: '/profile?user=' + getUserId(),
							})
						)
					);
					break;
				case 'player_poll_instantiated':
					msg = `%crealtime %c${eventName} %c${data.PollInstance} %c${data.PollQuestion}`;
					dispatch(addLocalPoll(generatePollFromRealtime(data)));
					break;
			}

			debugRealtime(msg);
		},
		[dispatch]
	);

	const { addListener, removeListener } = useRealtimeConnection(
		API_CONFIG.realtime.apiUrl,
		API_CONFIG.playfab.appId,
		playerId,
		SessionTicket,
	);

	useEffect(() => {
		addListener(ANY_EVENT, onMessage);

		return () => {
			removeListener(ANY_EVENT, onMessage);
		};
	}, []);

	return {
		newsVersion: versions.news,
		playerStatus,
	};
}
