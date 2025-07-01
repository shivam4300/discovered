import { toUrlEncoded } from './utils/toUrlEncoded';
import { getCookie, setCookie } from './utils/cookies';
import { XRAdActivityTypes } from './enums';

export const SESSION_TICKET_COOKIE_KEY = '_PLAYFABXR_SESSION_TICKET';
export const PLAYFABID_COOKIE_KEY = '_PLAYFABXR_PLAYFABID';
export const ENTITY_TOKEN_COOKIE_KEY = '_PLAYFABXR_ENTITY_TOKEN';

class PlayFabXR {
	static instances:Record<string, PlayFabXR> = {};

	private sessionTicket = null;

	private playfabId = null;

	private entityToken = null;

	private defaultHeaders = {
		'Content-Type': 'application/x-www-form-urlencoded',
	};

	public constructor(private apiUrl:string, private appId:string) {}

	public static GetInstance(apiUrl:string, appId:string):PlayFabXR {
		if (!PlayFabXR.instances[appId]) {
			PlayFabXR.instances[appId] = new PlayFabXR(apiUrl, appId);
		}

		return PlayFabXR.instances[appId];
	}

	public SetSessionTicket(sessionTicket:string) {
		this.sessionTicket = sessionTicket;
	}

	public SetPlayFabId(playfabId:string) {
		this.playfabId = playfabId;
	}

	public SetEntityToken(entityToken:string) {
		this.entityToken = entityToken;
	}

	public GetAppId() {
		return this.appId;
	}

	public GetPlayFabId() {
		if (!this.playfabId) {
			this.playfabId = getCookie(this.appId + PLAYFABID_COOKIE_KEY);
		}
		return this.playfabId;
	}

	public GetSessionTicket() {
		if (!this.sessionTicket) {
			this.sessionTicket = getCookie(this.appId + SESSION_TICKET_COOKIE_KEY);
		}
		return this.sessionTicket;
	}

	public GetEntityToken() {
		if (!this.entityToken) {
			this.entityToken = getCookie(this.appId + ENTITY_TOKEN_COOKIE_KEY);
		}
		return this.entityToken;
	}

	private apiCall<T>(endpoint:string, data:Record<string, any>, headers:Record<string, string>):Promise<GenericApiCallResponse<T>> {
		console.log(endpoint, data,'endpoint',headers);

		return fetch(this.apiUrl + endpoint, {
			method:'POST',
			body: toUrlEncoded(data),
			headers: { ...this.defaultHeaders, ...headers },
		}).then(response => response.json()).then((json:GenericApiCallResponse<T>) => json);
	}

	private onAuth(response:GenericApiCallResponse<AuthResponse>) {
		if (response) {
			this.sessionTicket = response.data.LoginResult.SessionTicket;
			this.entityToken = response.data.LoginResult.EntityToken.EntityToken;
			this.playfabId = response.data.LoginResult.PlayFabId;
			
			const expires = new Date(Date.now() + 1000 * 60 * 60 * 24);
			setCookie(this.appId + SESSION_TICKET_COOKIE_KEY, this.sessionTicket, null, expires);
			setCookie(this.appId + ENTITY_TOKEN_COOKIE_KEY, this.entityToken, null, expires);
			setCookie(this.appId + PLAYFABID_COOKIE_KEY, this.playfabId, null, expires);

		}

		return response;
	}

	public resetAuthCookies = () => {
		setCookie(this.appId + SESSION_TICKET_COOKIE_KEY, '');
		setCookie(this.appId + ENTITY_TOKEN_COOKIE_KEY, '');
		setCookie(this.appId + PLAYFABID_COOKIE_KEY, '');
	};

	private getAuthHeaders = () => ({ 'X-App-Id': this.appId });

	public Auth = {
		RegisterPlayFabUser: (data:{ username:string, email:string, password:string }) => {
			return this.apiCall<AuthResponse>('/auth/RegisterPlayFabUser', data, this.getAuthHeaders()).then(resp => this.onAuth(resp));
		},
		LoginWithPlayFab: (data:{ username:string, password:string }) => {
			return this.apiCall<AuthResponse>('/auth/LoginWithPlayFab', data, this.getAuthHeaders()).then(resp => this.onAuth(resp));
		},
		LoginWithEmailAddress: (data:{ email:string, password:string }) => {
			return this.apiCall<AuthResponse>('/auth/LoginWithEmailAddress', data, this.getAuthHeaders()).then(resp => this.onAuth(resp));
		},
		LoginWithCustomID: (data:{ CustomId:string }) => {
			return this.apiCall<AuthResponse>('/auth/LoginWithCustomID', data, this.getAuthHeaders()).then(resp => this.onAuth(resp));
		},
		LoginWithJWT: (data:{ JWT:string }, useClient = false) => {
			const headers = {
				...this.getAuthHeaders(),
				'auth-type': useClient ? 'client' : 'server',
			};
			return this.apiCall<AuthResponse>('/auth/LoginWithJWT', data, headers).then(resp => this.onAuth(resp));
		},
		LoginWithTwitchExtension: (data:{ JWT:string }) => {
			return this.apiCall<AuthResponse>('/auth/LoginWithTwitchExtension', data, this.getAuthHeaders()).then(resp => this.onAuth(resp));
		},
		LoginWithTwitch: (data:{ AccessToken:string }) => {
			return this.apiCall<AuthResponse>('/auth/LoginWithTwitch', { ...data, UseCustomId: 1 }, this.getAuthHeaders()).then(resp => this.onAuth(resp));
		},
		Logout: () => {
			setCookie(this.appId + SESSION_TICKET_COOKIE_KEY, '');
			setCookie(this.appId + ENTITY_TOKEN_COOKIE_KEY, '');
			setCookie(this.appId + PLAYFABID_COOKIE_KEY, '');

			return Promise.resolve({
				code: 200,
				message: 'Logout successful',
				data: {},
			} as GenericApiCallResponse<any>);
		},
	};

	private GetClientHeaders = () => ({
		'X-App-Id': this.appId,
		'X-Authentication': this.GetSessionTicket(),
		'X-EntityToken': this.GetEntityToken(),
	});

	public Client = {
		// App Data
		GetGlobalVariable: (data:{ key?:string } = {}) => {
			return this.apiCall<GetGlobalVariableResponse>('/client/GetGlobalVariable', data, this.GetClientHeaders());
		},

		// Advertising
		GetAdPlacements: ((data?:{ PlacementId:string }) => {
			if (!data) return this.apiCall<GetAdPlacementsMultipleResponse>('/client/GetAdPlacements', {}, this.GetClientHeaders());
			return this.apiCall<GetAdPlacementsSingleResponse>('/client/GetAdPlacements', data, this.GetClientHeaders());
		}) as {
			(data:{ PlacementId:string }):Promise<GenericApiCallResponse<GetAdPlacementsSingleResponse>>;
			():Promise<GenericApiCallResponse<GetAdPlacementsMultipleResponse>>;
		},
		ReportAdActivity: (data:{ Activity: XRAdActivityTypes, PlacementId:string }) => {
			return this.apiCall<ReportAdActivityResponse>('/client/ReportAdActivity', data, this.GetClientHeaders());
		},
		RewardAdActivity: (data:{ PlacementId:string }) => {
			return this.apiCall<RewardAdActivityResponse>('/client/RewardAdActivity', data, this.GetClientHeaders());
		},
		SkipAdPlacement: (data:{ CurrencyCode:string, PlacementId:string }) => {
			return this.apiCall<SkipAdPlacementResponse>('/client/SkipAdPlacement', data, this.GetClientHeaders());
		},

		//Player Profile
		GetUserData: () => {
			return this.apiCall<GetUserDataResponse>('/client/GetUserData', {}, this.GetClientHeaders());
		},
		GetPlayedTitleList: () => {
			return this.apiCall<GetPlayedTitleListResponse>('/client/GetPlayedTitleList', {}, this.GetClientHeaders());
		},
		GetPlayerStatistics: () => {
			return this.apiCall<GetPlayerStatisticsResponse>('/client/GetPlayerStatistics', {}, this.GetClientHeaders());
		},
		GetPlayerProfile: (data:{ PlayFabId?:string } = { PlayFabId: this.playfabId }) => {
			return this.apiCall<GetPlayerProfileResponse>('/client/GetPlayerProfile', data, this.GetClientHeaders());
		},

		//Giveaway
		ClaimGiveawayKey: (data:{ ItemInstanceId:string }) => {
			return this.apiCall<ClaimGiveawayKeyResponse>('/client/ClaimGiveawayKey', data, this.GetClientHeaders());
		},

		//Inventory
		UnlockContainerItem: (data:{ ContainerItemId:string }) => {
			return this.apiCall<UnlockContainerItemResponse>('/client/UnlockContainerItem', data, this.GetClientHeaders());
		},
		OpenDropChest: (data:{ ContainerItemId:string, Amount: number }) => {
			return this.apiCall<OpenDropChestResponse>('/client/OpenDropChest', data, this.GetClientHeaders());
		},
		GetVirtualCurrency: (data:{ CurrencyCode?:string } = {}) => {
			return this.apiCall<GetVirtualCurrencyResponse>('/client/GetVirtualCurrency', data, this.GetClientHeaders());
		},
		GetItemInventory: () => {
			return this.apiCall<GetItemInventoryResponse>('/client/GetItemInventory', {}, this.GetClientHeaders());
		},
		GetItemData: (data:{ ItemId:string }) => {
			return this.apiCall<GetItemDataResponse>('/client/GetItemData', data, this.GetClientHeaders());
		},
		GetItemCatalog: (data:{ ItemClass?:string } = {}) => {
			return this.apiCall<GetItemCatalogResponse>('/client/GetItemCatalog', data, this.GetClientHeaders());
		},
		AcquireCatalogItem: (data:{ ItemId:string }) => {
			return this.apiCall<AcquireCatalogItemResponse>('/client/AcquireCatalogItem', data, this.GetClientHeaders());
		},
		ConsumeItem: (data:{ ItemInstanceId:string, ConsumeCount:number }) => {
			return this.apiCall<ConsumeItemResponse>('/client/ConsumeItem', data, this.GetClientHeaders());
		},

		//Missions
		GetMissionInventory: () => {
			return this.apiCall<GetMissionInventoryResponse>('/client/GetMissionInventory', {}, this.GetClientHeaders());
		},
		GetMissionData: (data:{ ItemId:string }) => {
			return this.apiCall<GetMissionDataResponse>('/client/GetMissionData', data, this.GetClientHeaders());
		},
		SendMissionInput: (data:{ ItemId:string, Input:string, ObjectiveId?:string, Context?:string }) => {
			return this.apiCall<SendMissionInputResponse>('/client/SendMissionInput', data, this.GetClientHeaders());
		},
		ResetMission: (data:{ ItemId:string }) => {
			return this.apiCall<[]>('/client/ResetMission', data, this.GetClientHeaders());
		},

		//Custom Events
		WritePlayerEvent: (data:{ EventName:string, Body?:string, CustomTags?:string }) => {
			return this.apiCall<WritePlayerEventResponse>('/client/WritePlayerEvent', data, this.GetClientHeaders());
		},

		//Telemetry
		WriteTelemetryEvent: (data:{ Namespace:string, Name:string, Payload:string | { [key:string | number]: any } }) => {
			const formattedPayload = typeof data.Payload === 'string' ? data.Payload : JSON.stringify(data.Payload);
			return this.apiCall<WriteTelemetryEventResponse>('/client/WriteTelemetryEvent', { ...data, Payload: formattedPayload }, this.GetClientHeaders());
		},

		//Transition
		GetFormula: (data:{ FormulaId?: number } = {}) => {
			return this.apiCall<GetFormulaResponse>('/client/GetFormula', data, this.GetClientHeaders());
		},
		ExecuteFormula: (data:{ FormulaId?: number } = {}) => {
			return this.apiCall<ExecuteFormulaResponse>('/client/ExecuteFormula', data, this.GetClientHeaders());
		},

		//Store
		GetStoreLoadout: () => {
			return this.apiCall<GetStoreLoadoutResponse>('/client/GetStoreLoadout', {}, this.GetClientHeaders());
		},
		PurchaseStoreItem: (data:{ TileId:string, CurrencyCode:string }) => {
			return this.apiCall<PurchaseStoreItemResponse>('/client/PurchaseStoreItem', data, this.GetClientHeaders());
		},
		RefreshStoreTile: (data:{ TileId:string }) => {
			return this.apiCall<RefreshStoreTileResponse>('/client/RefreshStoreTile', data, this.GetClientHeaders());
		},
		PurchaseStoreReset: (data:{ SectionId:number }) => {
			return this.apiCall<PurchaseStoreResetResponse>('/client/PurchaseStoreReset', data, this.GetClientHeaders());
		},

		//Leaderboards and Stats
		GetLeaderboard: (data:{ StartPosition: number, StatisticName: string, MaxResultsCount?: number, ProfileConstraints?: Record<string, boolean>, Version?: number, }) => {
			let parsedConstraints = {};
			if (data.ProfileConstraints) {
				parsedConstraints = {
					ProfileConstraints: JSON.stringify(data.ProfileConstraints),
				};
			}
			return this.apiCall<GetLeaderboardResponse>('/client/GetLeaderboard', { ...data, ...parsedConstraints }, this.GetClientHeaders());
		},
		GetLeaderboardAroundPlayer: (data:{ StatisticName: string, MaxResultsCount?: number, ProfileConstraints?: Record<string, boolean>, Version?: number, }) => {
			let parsedConstraints = {};
			if (data.ProfileConstraints) {
				parsedConstraints = {
					ProfileConstraints: JSON.stringify(data.ProfileConstraints),
				};
			}
			return this.apiCall<GetLeaderboardAroundPlayerResponse>('/client/GetLeaderboardAroundPlayer', { ...data, ...parsedConstraints }, this.GetClientHeaders());
		},
		GetInstanceLeaderboard: (data:{ CustomInstanceId:string, StatName:string, Limit?:number, Offset?:number }) => {
			return this.apiCall<GetInstanceLeaderboardResponse>('/client/GetInstanceLeaderboard', data, this.GetClientHeaders());
		},
		GetInstanceLeaderboardRank: (data:{ CustomInstanceId:string, StatName:string }) => {
			return this.apiCall<GetInstanceLeaderboardRankResponse>('/client/GetInstanceLeaderboardRank', data, this.GetClientHeaders());
		},
		GetInstanceStat: (data:{ CustomInstanceId:string, StatName:string }) => {
			return this.apiCall<GetInstanceStatResponse>('/client/GetInstanceStat', data, this.GetClientHeaders());
		},
		GetAppStat: (data:{ StatName?:string }) => {
			return this.apiCall<GetAppStatResponse>('/client/GetAppStat', data, this.GetClientHeaders());
		},

		//Friend List
		GetFriendsList: () => {
			return this.apiCall<GetFriendsListResponse>('/client/GetFriendsList', {}, this.GetClientHeaders());
		},
		SendFriendRequest: (data:{ FriendId:string, TokenItemId: string }) => {
			return this.apiCall<SendFriendRequestResponse>('/client/SendFriendRequest', data, this.GetClientHeaders());
		},
		AcceptFriendRequest: (data:{ TokenId: string }) => {
			return this.apiCall<[]>('/client/AcceptFriendRequest', data, this.GetClientHeaders());
		},
		DeclineFriendRequest: (data:{ TokenId: string }) => {
			return this.apiCall<[]>('/client/DeclineFriendRequest', data, this.GetClientHeaders());
		},
		AddFriend: (data:{ FriedId: string }) => {
			return this.apiCall<AddFriendResponse>('/client/AddFriend', data, this.GetClientHeaders());
		},
		RemoveFriend: (data:{ FriedId: string }) => {
			return this.apiCall<[]>('/client/RemoveFriend', data, this.GetClientHeaders());
		},

		// Poll
		AnswerPoll: (data:{ InstanceId: string, AnswerId: string, MatchId?: string, TimeToAnswer?: number }) => {
			return this.apiCall<AnswerPollResponse>('/client/AnswerPoll', data, this.GetClientHeaders());
		},
		GetPoll: ((data?:{ InstanceId?: string, MatchId?:string }) => {
			if (data) return this.apiCall<GetPollSingleResponse>('/client/GetPoll', data, this.GetClientHeaders());
			return this.apiCall<GetPollMultipleResponse>('/client/GetPoll', {}, this.GetClientHeaders());
		}) as {
			(): Promise<GenericApiCallResponse<GetPollMultipleResponse>>;
			(data:{ InstanceId?: string, MatchId?:string }): Promise<GenericApiCallResponse<GetPollSingleResponse>>;
		},

		GetPollResults: (data:{ InstanceId: string }) => {
			return this.apiCall<GetPollResultsSingleResponse>('/client/GetPollResults', data, this.GetClientHeaders());
		},

		// Twitch
		GetTwitchChannelData: (data:{ PlayFabId?:string, TwitchId?:string } = {}) => {
			return this.apiCall<GetTwitchChannelDataResponse>('/client/twitch/GetTwitchChannelData', data, this.GetClientHeaders());
		},

		// Quiz (predictions, trivia, polls)
		AnswerPrediction: ((data) => {
			let { Answers } = data;
			if (typeof Answers !== 'string') {
				Answers = JSON.stringify(Answers);
			}
			return this.apiCall<AnswerQuizResponse>('/client/AnswerPrediction', { ...data, Answers }, this.GetClientHeaders());
		}) as XRAnswerQuiz,

		AnswerTrivia: ((data) => {
			let { Answers } = data;
			if (typeof Answers !== 'string') {
				Answers = JSON.stringify(Answers);
			}
			return this.apiCall<AnswerQuizResponse>('/client/AnswerTrivia', { ...data, Answers }, this.GetClientHeaders());
		}) as XRAnswerQuiz,

		AnswerSurvey: ((data) => {
			let { Answers } = data;
			if (typeof Answers !== 'string') {
				Answers = JSON.stringify(Answers);
			}
			return this.apiCall<AnswerQuizResponse>('/client/AnswerSurvey', { ...data, Answers }, this.GetClientHeaders());
		}) as XRAnswerQuiz,

		GetPredictionInstances: ((data) => {
			return this.apiCall<GetQuizInstancesResponse>('/client/GetPredictionInstances', data, this.GetClientHeaders());
		}) as XRGetQuizInstances,

		GetTriviaInstances: ((data) => {
			return this.apiCall<GetQuizInstancesResponse>('/client/GetTriviaInstances', data, this.GetClientHeaders());
		}) as XRGetQuizInstances,

		GetSurveyInstances: ((data) => {
			return this.apiCall<GetQuizInstancesResponse>('/client/GetSurveyInstances', data, this.GetClientHeaders());
		}) as XRGetQuizInstances,

		GetQuizInstances: ((data) => {
			return this.apiCall<GetQuizInstancesResponse>('/client/GetQuizInstances', data, this.GetClientHeaders());
		}) as XRGetQuizInstances,

		GetPredictionInstance: ((data) => {
			return this.apiCall<GetQuizInstanceResponse>('/client/GetPredictionInstance', data, this.GetClientHeaders());
		}) as XRGetQuizInstance,

		GetTriviaInstance: ((data) => {
			return this.apiCall<GetQuizInstanceResponse>('/client/GetTriviaInstance', data, this.GetClientHeaders());
		}) as XRGetQuizInstance,

		GetSurveyInstance: ((data) => {
			return this.apiCall<GetQuizInstanceResponse>('/client/GetSurveyInstance', data, this.GetClientHeaders());
		}) as XRGetQuizInstance,

		GetQuizInstance: ((data) => {
			return this.apiCall<GetQuizInstanceResponse>('/client/GetQuizInstance', data, this.GetClientHeaders());
		}) as XRGetQuizInstance,

		GetUnclaimedPredictions: () => {
			return this.apiCall<GetUnclaimedPredictionsResponse>('/client/GetUnclaimedPredictions', {}, this.GetClientHeaders());
		},

		ClaimPredictionRewards: (data:{ InstanceId?: string }) => {
			return this.apiCall<ClaimPredictionRewardsResponse>('/client/ClaimPredictionRewards', data, this.GetClientHeaders());
		},

		GetPredictionInstanceStatistics: ((data) => {
			return this.apiCall<GetQuizInstanceStatisticsClientResponse>('/client/GetPredictionInstanceStatistics', data, this.GetClientHeaders());
		}) as XRGetQuizInstanceStatistics,
		GetTriviaInstanceStatistics: ((data) => {
			return this.apiCall<GetQuizInstanceStatisticsClientResponse>('/client/GetTriviaInstanceStatistics', data, this.GetClientHeaders());
		}) as XRGetQuizInstanceStatistics,
		GetSurveyInstanceStatistics: ((data) => {
			return this.apiCall<GetQuizInstanceStatisticsClientResponse>('/client/GetSurveyInstanceStatistics', data, this.GetClientHeaders());
		}) as XRGetQuizInstanceStatistics,
		GetQuizInstanceStatistics: ((data) => {
			return this.apiCall<GetQuizInstanceStatisticsClientResponse>('/client/GetQuizInstanceStatistics', data, this.GetClientHeaders());
		}) as XRGetQuizInstanceStatistics,
	};

	public Service = {
		// Twitch
		GetLiveBroadcast: (data:{ TimeRange:number } = { TimeRange: 5 }) => {
			return this.apiCall<GetLiveBroadcastResponse>('/service/GetLiveBroadcast', data, this.GetClientHeaders());
		},
	};
}

export default PlayFabXR;
