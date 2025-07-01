/* eslint-disable @typescript-eslint/naming-convention */
import { createSlice } from '@reduxjs/toolkit';
import { realtimeApi } from '../api/apiBridge';
import { PLAYFAB_CONFIG } from '../Constants';
import { guid } from '../utils/guid';
import createDebouncedAsyncAction from './async/createDebouncedAsyncAction';
import getUserId from '../utils/getUserId';

type INotificationsState = typeof initialState;

const initialState = {
	list:
		JSON.parse(localStorage.getItem(`notifications_${getUserId()}`) || '[]') ||
		[],
};

console.log(initialState, getUserId());

export const getRealtimeNotifications = createDebouncedAsyncAction(
	'realtime/getRealtimeNotifications',
	(playerId: string) => {
		return realtimeApi<IXRRealtimeNotification[]>('client/GetNotifications', {
			AppId: PLAYFAB_CONFIG.appId,
			PlayFabId: playerId,
		});
	}
);

export function notificationGenerator({
	title = '',
	icon = '',
	message = '',
	link = '',
	type = '',
	entityId = '',
}) {
	return {
		NotificationId: guid(),
		Title: title,
		Icon: icon,
		Message: message,
		Link: link,
		EventName: 'local_notification',
		Timestamp: new Date().toISOString(),
		TitleId: PLAYFAB_CONFIG.appId,
		EventId: guid(),
		EntityId: entityId,
		EntityType: 'player',
		Source: 'local',
		SourceType: 'local',
		type: type,
	};
}

const notifications = createSlice({
	name: 'notifications',
	reducers: {
		addNotification: (
			state: INotificationsState,
			action: { payload: IXRRealtimeNotification }
		) => {
			const prevNotification = state.list.find(n => (
				n.Title === action.payload.Title &&
				n.Message === action.payload.Message && 
				n.Link === action.payload.Link
			));
			if (prevNotification) {
				prevNotification.Timestamp = new Date().toISOString();
				return;
			}

			localStorage.setItem(
				`notifications_${getUserId()}`,
				JSON.stringify([...state.list, action.payload])
			);
			state.list.push({
				...action.payload,
				Message: action.payload?.Message,
				Timestamp: new Date().toISOString(),
			});
		},
		removeNotification: (state: INotificationsState, action) => {
			localStorage.setItem(
				`notifications_${getUserId()}`,
				JSON.stringify([
					...state.list.filter(
						(notification) => notification.NotificationId !== action.payload
					),
				])
			);
			return {
				...state,
				list: state.list.filter(
					(notification) => notification.NotificationId !== action.payload
				),
			};
		},
	},
	extraReducers: (builder) => {
		builder.addCase(
			getRealtimeNotifications.actions.fulfilled,
			(state, action) => {
				return {
					...state,
					list: action.payload
						.map((n) => ({
							...n,
						}))
						.filter((n) => !state.list.includes(n.NotificationId)),
				};
			}
		);
	},
	initialState,
});

export default notifications;

export const { addNotification, removeNotification } = notifications.actions;
