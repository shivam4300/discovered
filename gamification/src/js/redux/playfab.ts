import { createSlice } from '@reduxjs/toolkit';

import createDebouncedAsyncAction from './async/createDebouncedAsyncAction';
import xrAction from './async/xrAction';
import { getXrApi, playfabClientApi, playfabCloudScriptApi } from '../api/apiBridge';


type IPlayfabState = typeof initialState;
type Currencies = {
	[currency:string]: number;
};

type IGenericId = {
	UserId: string,
	ServiceName: string,
};

export const authPlayfab = createDebouncedAsyncAction(
	'playfab-xr/auth',
	xrAction(({ jwt }) => {
		return getXrApi().Auth.LoginWithJWT({
			JWT: jwt
		}, true);
	}),
);

export const updateDisplayName = createDebouncedAsyncAction(
	'playfab/UpdateUserTitleDisplayName',
	(displayName: string) => {
		return playfabClientApi('UpdateUserTitleDisplayName', {
			DisplayName: displayName,
		});
	},
);

export const addGenericId = createDebouncedAsyncAction(
	'playfab/AddGenericID',
	({ UserId, ServiceName }:IGenericId) => {
		return playfabClientApi('AddGenericID', {
			GenericId: {
				UserId,
				ServiceName,
			},
		});
	},
);

export const getPlayFabIDsFromGenericIDs = createDebouncedAsyncAction(
	'playfab/GetPlayFabIDsFromGenericIDs',
	(genericIds:{ genericIds: IGenericId[] }) => {
		return playfabClientApi('GetPlayFabIDsFromGenericIDs', {
			GenericIds: genericIds,
		});
	},
);

export const getLiveBroadcasts = createDebouncedAsyncAction(
	'playfab-xr/getLiveBroadcasts',
	xrAction(() => {
		return getXrApi().Service.GetLiveBroadcast({ TimeRange: 5 });
	}),
);

export const writePlayerEvent = createDebouncedAsyncAction(
	'playfab-xr/writePlayerEvent',
	xrAction(({ name, body = null }:{ name:string, body?:unknown }) => {
		const opts:{ EventName:string, Body?:string } = {
			EventName: name,
		};

		if (body) {
			opts.Body = JSON.stringify(body);
		}

		console.log(opts);
		return getXrApi().Client.WritePlayerEvent(opts);
	}),
);

export const sendHeartbeat = createDebouncedAsyncAction(
	'playfab/sendHeartbeat',
	(data = {}) => {
		return playfabCloudScriptApi('ExecuteFunction', {
			FunctionName: 'Heartbeat',
			FunctionParameter: data,
			GeneratePlayStreamEvent: true,
		});
	},
);

export const executeCloudScript = createDebouncedAsyncAction(
	'playfab/executeCloudScript',
	({ functionName, data = {} }:{ functionName:string, data:unknown }) => {
		return playfabClientApi('ExecuteCloudScript', {
			FunctionName: functionName,
			FunctionParameter: data,
			GeneratePlayStreamEvent: true,
		});
	},
);

export const getVirtualCurrency = createDebouncedAsyncAction(
	'playfab-xr/getVirtualCurrency',
	xrAction(() => {
		return getXrApi().Client.GetVirtualCurrency();
	}),
);

export const openDropChest = createDebouncedAsyncAction(
	'playfab-xr/openDropChest',
	xrAction((data:{ ContainerItemId:string, Amount: number }) => {
		return getXrApi().Client.OpenDropChest(data);
	}),
);

export const unlockContainerItem = createDebouncedAsyncAction(
	'playfab-xr/unlockContainerItem',
	xrAction((data:{ ContainerItemId:string }) => {
		return getXrApi().Client.UnlockContainerItem(data);
	}),
);

export const getItemInventory = createDebouncedAsyncAction(
	'playfab-xr/getItemInventory',
	xrAction(() => {
		return getXrApi().Client.GetItemInventory();
	}),
);

export const consumeInventoryItem = createDebouncedAsyncAction(
	'playfab-xr/consumeInventoryItem',
	xrAction((data:{ ItemInstanceId:string, ConsumeCount:number }) => {
		return getXrApi().Client.ConsumeItem(data);
	}),
);

export const getStoreLoadout = createDebouncedAsyncAction(
	'playfab-xr/getStoreLoadout',
	xrAction(() => {
		return getXrApi().Client.GetStoreLoadout();
	}),
);

export const purchaseStoreItem = createDebouncedAsyncAction(
	'playfab-xr/purchaseStoreItem',
	xrAction((data: { TileId:string, CurrencyCode:string }) => {
		return getXrApi().Client.PurchaseStoreItem(data);
	}),
);

export const sendMissionInput = createDebouncedAsyncAction(
	'playfab-xr/sendMissionInput',
	xrAction((data) => {
		return getXrApi().Client.SendMissionInput({
			ItemId: data.mission_id,
			ObjectiveId: data.objective_id,
			Input: data.answer,
		});
	}),
);

export const getInstanceLeaderboard = createDebouncedAsyncAction(
	'playfab-xr/getInstanceLeaderboard',
	xrAction((data) => {
		return getXrApi().Client.GetInstanceLeaderboard({
			CustomInstanceId: data.instanceId,
			StatName: data.statName,
		});
	}),
);

export const getPlayerCombinedInfo = createDebouncedAsyncAction(
	'playfab/getPlayerCombinedInfo',
	() => {
		return playfabClientApi('GetPlayerCombinedInfo', {
			InfoRequestParameters: {
				GetPlayerProfile: true,
				GetPlayerStatistics: true,
				GetUserVirtualCurrency: true,
				GetUserReadOnlyData: true,
				GetUserData: true,
				ProfileConstraints: {
					ShowDisplayName: true,
					ShowCreated: false,
					ShowOrigination: false,
					ShowLastLogin: true,
					ShowBannedUntil: false,
					ShowStatistics: true,
					ShowCampaignAttributions: false,
					ShowPushNotificationRegistrations: false,
					ShowLinkedAccounts: false,
					ShowContactEmailAddresses: false,
					ShowTotalValueToDateInUsd: false,
					ShowValuesToDate: false,
					ShowVirtualCurrencyBalances: false,
					ShowTags: false,
					ShowLocations: false,
					ShowAvatarUrl: true,
					ShowMemberships: false,
					ShowExperimentVariants: false,
				},
			},
		});
	},
);


const initialState = {
	PlayFabId: '',
	DisplayName: '',
	AvatarUrl: '',
	currencies: {} as Currencies,
	LiveBroadcasts: [] as IBroadcast[],
	needRefresh: true,
	SessionTicket: '',
};

const playfab = createSlice({
	name: 'playfab',
	reducers: {
		updateLocalDisplayName: (state, action) => {
			state.DisplayName = action.payload;
		},
		updateLocalAvatarUrl: (state, action) => {
			state.AvatarUrl = action.payload;
		},
		setNeedRefresh: (state, action) => {
			state.needRefresh = action.payload;
		},
		updateLocalVirtualCurrency: (state:IPlayfabState, action) => {
			const { currency, amount } = action.payload;

			return {
				...state,
				currencies: {
					...state.currencies,
					[currency]: amount,
				},
			};
		},
	},
	extraReducers: (builder) => {
		builder.addCase(authPlayfab.actions.fulfilled, (state:IPlayfabState, action) => {
			return {
				...state,
				...action.payload.data.LoginResult,
				needRefresh: true,
			};
		});
		builder.addCase(getStoreLoadout.actions.fulfilled, (state:IPlayfabState, action) => {
			return {
				...state,
				StoreLoadout: [...action.payload.data?.StoreLoadout],
			};
		});
		builder.addCase(getLiveBroadcasts.actions.fulfilled, (state:IPlayfabState, action) => {
			const broadcasts = (action.payload.data?.Broadcasts || []) as IBroadcast[];

			return {
				...state,
				LiveBroadcasts: broadcasts,
			};
		});
		builder.addCase(getVirtualCurrency.actions.fulfilled, (state:IPlayfabState, action) => {
			return {
				...state,
				currencies: Object.entries(action.payload.data.VirtualCurrencies).reduce((c, [currency, amount]) => {
					c[currency] = amount;
					return c;
				}, {} as Currencies),
			};
		});
		builder.addCase(getPlayerCombinedInfo.actions.fulfilled, (state:IPlayfabState, action) => {
			return {
				...state,
				DisplayName: action.payload.data.InfoResultPayload.PlayerProfile?.DisplayName,
				AvatarUrl: action.payload.data.InfoResultPayload.PlayerProfile?.AvatarUrl,
				currencies: {
					...action.payload.data.InfoResultPayload.UserVirtualCurrency,
				},
			};
		});
		builder.addCase(updateDisplayName.actions.fulfilled, (state, action) => {
			state.DisplayName = action.payload.data.DisplayName;
		});
		builder.addCase(sendHeartbeat.actions.fulfilled, () => {});
		builder.addCase(executeCloudScript.actions.fulfilled, () => {});
		builder.addCase(writePlayerEvent.actions.fulfilled, () => {});
		builder.addCase(addGenericId.actions.fulfilled, () => {});
		builder.addCase(getPlayFabIDsFromGenericIDs.actions.fulfilled, () => {});
	},
	initialState,
});

export default playfab;

export const { setNeedRefresh, updateLocalDisplayName, updateLocalAvatarUrl, updateLocalVirtualCurrency } = playfab.actions;