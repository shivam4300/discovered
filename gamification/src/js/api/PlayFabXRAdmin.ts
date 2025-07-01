import { OpenAIImageSizes } from './enums';

class PlayFabXRAdmin {

	static instances:Record<string, PlayFabXRAdmin> = {};

	private defaultHeaders = {

	};

	public constructor(private apiUrl:string, private appId:string, private apiKey:string, private apiSecret:string) {
	}

	public GetAppId() {
		return this.appId;
	}

	public static GetInstance(apiUrl:string, appId:string, apiKey:string, apiSecret:string):PlayFabXRAdmin {
		if (!PlayFabXRAdmin.instances[appId]) {
			PlayFabXRAdmin.instances[appId] = new PlayFabXRAdmin(apiUrl, appId, apiKey, apiSecret);
		}

		return PlayFabXRAdmin.instances[appId];
	}

	private apiCallFormData<T>(endpoint:string, data:FormData, headers:Record<string, string>, method = 'POST'):Promise<GenericApiCallResponse<T>> {

		const credentialHeaders = this.getCredentialHeaders();

		return fetch(this.apiUrl + endpoint, {
			method,
			body: data,
			headers: { ...this.defaultHeaders, ...headers, ...credentialHeaders },
		}).then(response => response.json()).then((json:GenericApiCallResponse<T>) => json);
	}

	private apiCall<T>(endpoint:string, data:Record<string, string | number | object | boolean>, headers:Record<string, string>, method = 'POST'):Promise<GenericApiCallResponse<T>> {

		const credentialHeaders = this.getCredentialHeaders();

		return fetch(this.apiUrl + endpoint, {
			method,
			body: JSON.stringify(data),
			headers: { ...this.defaultHeaders, ...headers, ...credentialHeaders, 'Content-Type': 'application/json' },
		}).then(response => response.json()).then((json:GenericApiCallResponse<T>) => json);
	}

	private getCredentialHeaders = () => ({
		'X-Api-Key': this.apiKey,
		'X-Api-Secret': this.apiSecret,
		'X-App-Id': this.appId,
	});
	
	public Admin = {
		Authenticate: () => {
			return this.apiCall<GetXRAdminAuthenticationResponse>('/auth/SignIn', {}, {});
		},
		GetConfig: ((data?:{ ServiceName:string }) => {
			if (!data) {
				return this.apiCall<GetConfigMultipleResponse>('/admin/GetConfig', { AppId: this.appId }, {});
			}
			
			return this.apiCall<GetConfigSingleResponse>('/admin/GetConfig', { ...data, AppId: this.appId }, {});
		}) as {
			(data:{ ServiceName:string }):Promise<GenericApiCallResponse<GetConfigSingleResponse>>,
			():Promise<GenericApiCallResponse<GetConfigMultipleResponse>>,
		},

		UpdateGlobalVariable: (data: { DataKey: string; DataVal: string, DataType?: number | string }) => {
			return this.apiCall<GetGlobalVariableResponse>(
				'/admin/UpdateGlobalVariable',
				data,
				{},
			);
		},

		GetCustomEvents: (data?:{ EventName?:string }) => {
			return this.apiCall<GetCustomEventsResponse>(
				'/admin/GetCustomEvents',
				data,
				{},
			).then(response => {
				return {
					...response,
					data: {
						...response.data,
						CustomEvents: (response.data?.CustomEvents || []).map(event => {
							if (event.app) delete event.app;
							return event;
						}),
					} as GetCustomEventsResponse,
				};
			});
		},

		PublishQuizDefinition: (data: { itemId:string, type: string, data:XRQuizDefinitionData, resolveRules:XRQuizResolveRules, rewards: XRQuizRewards }) => {
			return this.apiCall<PublishQuizDefinitionResponse>(
				'/admin/PublishQuizDefinition',
				data,
				{},
			);
		},
		
		DeleteQuizDefinition: (data: { itemId:string }) => {
			return this.apiCall<IXRApiErrorResponse | null>(
				'/admin/DeleteQuizDefinition',
				data,
				{},
			);
		},

		UpdateQuizData: (data: { itemId:string, itemType:string, displayName:string, description:string, jsonData:object }) => {
			return this.apiCall<UpdateQuizDataResponse>(
				'/admin/UpdateQuizData',
				{
					...data,
					displayName: data.displayName || data.itemId,
				},
				{},
			);
		},
		
		DeleteQuizData: (data: { itemId:string, itemType:string }) => {
			return this.apiCall<UpdateQuizDataResponse>(
				'/admin/DeleteQuizData',
				data,
				{},
			);
		},

		GetQuizData: <DataType>(data: { itemType:string, itemId?:string }) => {
			return this.apiCall<GetQuizDataResponse<DataType>>(
				'/admin/GetQuizData',
				data,
				{},
			);
		},

		GetQuizDataList: (data: { itemType:string, search?:string, sort?:string, dir?: number, offset?:number, limit?:number }) => {
			return this.apiCall<GetQuizDataListResponse>(
				'/admin/GetQuizDataList',
				data,
				{},
			);
		},

		GetCombinedContents: ({
			ShowItems = null as boolean,
			ShowMissions = null as boolean,
			ShowCurrencies = null as boolean,
			ShowDropTables = null as boolean,
		}) => {
			const data = new FormData();
			if (ShowItems !== null) data.append('ShowItems', ShowItems.toString());
			if (ShowMissions !== null) data.append('ShowMissions', ShowMissions.toString());
			if (ShowCurrencies !== null) data.append('ShowCurrencies', ShowCurrencies.toString());
			if (ShowDropTables !== null) data.append('ShowDropTables', ShowDropTables.toString());

			return this.apiCallFormData<GetCombinedContentsResponse>(
				'/admin/GetCombinedContents',
				data,
				{},
			);
		},

		UploadMediaLibraryChunk: (chunk:Blob, fileName:string, tmpName:string, fileSize:number, rangeStart:number, rangeEnd:number) => {

			const data = new FormData();
			data.append('chunk', chunk);
			data.append('name', fileName);
			data.append('totalSize', `${fileSize}`);
			data.append('currentSize', `${rangeEnd}`);
			data.append('rangeStart', `${rangeStart}`);
			data.append('tmpName', tmpName);

			return this.apiCallFormData<UploadMediaLibraryChunkResponse>(
				'/admin/medialibrary/chunk',
				data,
				{},
			);
		},

		GetMediaLibraryList: (page = 1, pageSize?:number) => {

			const data = new FormData();
			data.append('page', `${page}`);
			if (pageSize) data.append('pageSize', `${pageSize}`);

			return this.apiCallFormData<GetMediaLibraryListResponse>(
				'/admin/medialibrary/list',
				data,
				{},
			);
		},

		UpdateMission: (props:UpdateMissionProps = {}) => {
			const data = new FormData();

			for (const key in props) {
				if (props[key] === undefined) continue;
				data.append(key, props[key]);
			}

			return this.apiCallFormData<UpdateMissionResponse>(
				'/admin/UpdateMission',
				data,
				{},
			);
		},

		CreatePoll: (props: UpdatePollDataProps) => {

			const data = new FormData();

			for (const key in props) {
				if (props[key] === undefined) continue;
				const val = props[key];
				if (typeof val !== 'string') {
					data.append(key, JSON.stringify(val));
					continue;
				}
				data.append(key, val);
			}

			return this.apiCallFormData<UpdatePollResponse>(
				'/admin/CreatePoll',
				data,
				{},
			);
		},

		UpdatePoll: (props: UpdatePollDataProps) => {

			const data = new FormData();

			for (const key in props) {
				if (props[key] === undefined) continue;
				const val = props[key];
				if (typeof val !== 'string') {
					data.append(key, JSON.stringify(val));
					continue;
				}
				data.append(key, val);
			}

			return this.apiCallFormData<UpdatePollResponse>(
				'/admin/UpdatePoll',
				data,
				{},
			);
		},


		DeletePoll: (data: { ItemId:string }) => {
			return this.apiCall<IXRApiErrorResponse | null>(
				'/admin/DeletePoll',
				data,
				{},
			);
		},


		UpdateMissionData: (props:UpdateMissionDataProps = {}) => {
			const data = new FormData();

			for (const key in props) {
				if (props[key] === undefined) continue;
				data.append(key, props[key]);
			}

			return this.apiCallFormData<UpdateMissionResponse>(
				'/admin/UpdateMissionData',
				data,
				{},
			);
		},

		GetMission: ((props:GetMissionProps = null) => {
			const data = new FormData();

			if (props) {
				data.append('ItemId', props.ItemId);
		
				return this.apiCallFormData<GetMissionResponseSingle>(
					'/admin/GetMission',
					data,
					{},
				);
			}
			
			return this.apiCallFormData<GetMissionResponseMultiple>(
				'/admin/GetMission',
				data,
				{},
			);
		}) as {
			():Promise<GenericApiCallResponse<GetMissionResponseMultiple>>;
			(props:GetMissionProps): Promise<GenericApiCallResponse<GetMissionResponseSingle>>;
		},

		UpdateMissionObjective: (props:UpdateMissionObjectiveProps) => {
			const data = new FormData();

			for (const key in props) {
				if (props[key] === undefined) continue;

				if (typeof props[key] === 'object') {
					data.append(key, JSON.stringify(props[key]));
				} else {
					data.append(key, props[key]);
				}
			}

			return this.apiCallFormData<UpdateMissionObjectiveResponse>(
				'/admin/UpdateMissionObjective',
				data,
				{},
			);
		},

		UpdateMissionObjectiveData: (props:UpdateMissionObjectiveDataProps) => {
			const data = new FormData();
			data.append('ObjectiveId', props.ObjectiveId.toString());
			data.append('DataKey', props.DataKey);
			data.append('DataVal', props.DataVal.toString());

			return this.apiCallFormData<UpdateMissionObjectiveDataResponse>(
				'/admin/UpdateMissionObjectiveData',
				data,
				{},
			);
		},

		DeleteMission: ({ MissionId }:{ MissionId: string }) => {
			const data = new FormData();
			data.append('MissionId', MissionId.toString());

			return this.apiCallFormData<Partial<IXRApiErrorResponse>>(
				'/admin/DeleteMission',
				data,
				{},
			);
		},

		DeleteMissionObjective: ({ ObjectiveId }:{ ObjectiveId: number }) => {
			const data = new FormData();
			data.append('ObjectiveId', ObjectiveId.toString());

			return this.apiCallFormData<Partial<IXRApiErrorResponse>>(
				'/admin/DeleteMissionObjective',
				data,
				{},
			);
		},

		UpdateMissionObjectiveReward: ({
			ItemId = '',
			RewardId = null,
			ObjectiveId = 0,
			DataType = '',
			DataKey = '',
			DataVal = '',
		}:UpdateMissionObjectiveRewardResponse) => {
			const data = new FormData();
			data.append('ItemId', ItemId.toString());
			if (RewardId) data.append('RewardId', RewardId.toString());
			data.append('ObjectiveId', ObjectiveId.toString());
			data.append('DataType', DataType.toString());
			data.append('DataKey', DataKey.toString());
			data.append('DataVal', DataVal.toString());

			return this.apiCallFormData<UpdateMissionObjectiveRewardResponse>(
				'/admin/UpdateMissionObjectiveReward',
				data,
				{},
			);
		},

		DeleteMissionObjectiveReward: ({
			ItemId = '',
			RewardId = 0,
			ObjectiveId = 0,
			DataType = '',
			DataKey = '',
			DataVal = '',
		}:DeleteMissionObjectiveRewardResponse) => {
			const data = new FormData();
			data.append('ItemId', ItemId.toString());
			data.append('RewardId', RewardId.toString());
			data.append('ObjectiveId', ObjectiveId.toString());
			data.append('DataType', DataType.toString());
			data.append('DataKey', DataKey.toString());
			data.append('DataVal', DataVal.toString());

			return this.apiCallFormData(
				'/admin/DeleteMissionObjectiveReward',
				data,
				{},
			);
		},

		GetStatsAndLeaderboards: () => {
			const data = new FormData();

			return this.apiCallFormData<GetStatsAndLeaderboardsResponse>(
				'/admin/GetStatsAndLeaderboards',
				data,
				{},
			);
		},

		GetMissionType: ((props: { MissionTypeId:string } = null) => {
			const data = new FormData();
			if (props?.MissionTypeId) {
				data.append('MissionTypeId', props.MissionTypeId.toString());

				return this.apiCallFormData<GetMissionTypeSingleResponse>(
					'/admin/GetMissionType',
					data,
					{},
				);
			}

			return this.apiCallFormData<GetMissionTypeMultipleResponse>(
				'/admin/GetMissionType',
				data,
				{},
			);
		}) as {
			():Promise<GenericApiCallResponse<GetMissionTypeMultipleResponse>>;
			(props:{ MissionTypeId:string }): Promise<GenericApiCallResponse<GetMissionTypeSingleResponse>>;
		},

		UpdateMissionType: (props:UpdateMissionTypeProps) => {
			const data = new FormData();
			data.append('Title', props.Title || '');
			data.append('IsCommunity', props.IsCommunity ? '1' : '0');
			if (props.LayoutColor) data.append('LayoutColor', props.LayoutColor);
			if (props.LayoutIcon) data.append('LayoutIcon', props.LayoutIcon);

			return this.apiCallFormData<UpdateMissionTypeResponse>(
				'/admin/UpdateMissionType',
				data,
				{},
			);
		},

		UpdateMissionTypeProperty: (props:UpdateMissionTypePropertyProps) => {
			const data = new FormData();
			data.append('Title', props.Title || '');

			if (props.PropertyId) data.append('PropertyId', props.PropertyId.toString());
			if (props.TypeId) data.append('TypeId', props.TypeId.toString());
			if (props.DataTypeId) data.append('DataTypeId', props.DataTypeId.toString());
			if (props.Scope) data.append('Scope', props.Scope);
			if (props.Title) data.append('Title', props.Title);
			if (props.Options) data.append('Options', typeof props.Options === 'object' ? JSON.stringify(props.Options) : props.Options.toString());
			if (props.GridPos) data.append('GridPos', props.GridPos.toString());

			return this.apiCallFormData<UpdateMissionTypePropertyResponse>(
				'/admin/UpdateMissionTypeProperty',
				data,
				{},
			);
		},

		GetMissionObjectiveType: ((props: { ObjectiveTypeId:string } = null) => {
			const data = new FormData();
			if (props?.ObjectiveTypeId) {
				data.append('ObjectiveTypeId', props.ObjectiveTypeId.toString());

				return this.apiCallFormData<GetMissionObjectiveTypeSingleResponse>(
					'/admin/GetMissionObjectiveType',
					data,
					{},
				);
			}

			return this.apiCallFormData<GetMissionObjectiveTypeMultipleResponse>(
				'/admin/GetMissionObjectiveType',
				data,
				{},
			);
		}) as {
			():Promise<GenericApiCallResponse<GetMissionObjectiveTypeMultipleResponse>>;
			(props:{ ObjectiveTypeId:string }): Promise<GenericApiCallResponse<GetMissionObjectiveTypeSingleResponse>>;
		},

		UpdateMissionObjectiveType: (props:UpdateMissionObjectiveTypeProps) => {
			const data = new FormData();
			data.append('Title', props.Title || '');
			data.append('LayoutColor', props.LayoutColor || '');
			data.append('LayoutIcon', props.LayoutIcon || '');

			return this.apiCallFormData<UpdateMissionObjectiveTypeResponse>(
				'/admin/UpdateMissionObjectiveType',
				data,
				{},
			);
		},

		UpdateMissionObjectiveTypeProperty: (props:UpdateMissionObjectiveTypePropertyProps) => {
			const data = new FormData();
			data.append('Title', props.Title || '');

			if (props.PropertyId) data.append('PropertyId', props.PropertyId.toString());
			if (props.TypeId) data.append('TypeId', props.TypeId.toString());
			if (props.DataTypeId) data.append('DataTypeId', props.DataTypeId.toString());
			if (props.Scope) data.append('Scope', props.Scope);
			if (props.Title) data.append('Title', props.Title);
			if (props.Options) data.append('Options', typeof props.Options === 'object' ? JSON.stringify(props.Options) : props.Options.toString());
			if (props.GridPos) data.append('GridPos', props.GridPos.toString());

			return this.apiCallFormData<UpdateMissionObjectiveTypePropertyResponse>(
				'/admin/UpdateMissionObjectiveTypeProperty',
				data,
				{},
			);
		},

		GetItem: (({ ItemId, ItemClass }:{ ItemId?:string, ItemClass?:string } = {}) => {
			const data = new FormData();
			if (ItemId) data.append('ItemId', ItemId.toString());
			if (ItemClass) data.append('ItemClass', ItemClass.toString());

			if (!ItemId) {
				return this.apiCallFormData<GetItemMultipleResponse>(
					'/admin/GetItem',
					data,
					{},
				);
			}

			return this.apiCallFormData<GetItemSingleResponse>(
				'/admin/GetItem',
				data,
				{},
			);
		}) as {
			():Promise<GenericApiCallResponse<GetItemMultipleResponse>>;
			(props:GetMissionProps): Promise<GenericApiCallResponse<GetItemSingleResponse>>;
		},

		GetPoll: (data:{ ItemId:string }) => {
			return this.apiCall<AdminGetPollResponse>(
				'/admin/GetPoll',
				data,
				{},
			);
		},

		GetPolls: () => {
			return this.apiCall<AdminGetPollsResponse>(
				'/admin/GetPolls',
				{},
				{},
			);
		},
	};

	public Server = {

		GetAppStat: (data:{ StatName?:string }) => {
			return this.apiCall<GetAppStatResponse>('/client/GetAppStat', data, {});
		},

		CancelPredictionInstance: ((data) => {
			return this.apiCall(
				'/server/CancelPredictionInstance',
				data,
				{},
			);
		}) as XRServerCancelQuizInstance,

		GetGlobalVariable: (data:{ DataKey?:string } = {}) => {
			return this.apiCall<GetGlobalVariableResponse>('/server/GetGlobalVariable', data, {});
		},

		CancelTriviaInstance: ((data) => {
			return this.apiCall(
				'/server/CancelTriviaInstance',
				data,
				{},
			);
		}) as XRServerCancelQuizInstance,
		
		CancelSurveyInstance: ((data) => {
			return this.apiCall(
				'/server/CancelSurveyInstance',
				data,
				{},
			);
		}) as XRServerCancelQuizInstance,

		GetPredictionInstanceList: ((data) => {
			return this.apiCall<GetServerQuizInstancesResponse>(
				'/server/GetPredictionInstances',
				data,
				{},
			);
		}) as XRServerGetQuizInstanceList,

		GetTriviaInstanceList: ((data) => {
			return this.apiCall<GetServerQuizInstancesResponse>(
				'/server/GetTriviaInstances',
				data,
				{},
			);
		}) as XRServerGetQuizInstanceList,

		GetSurveyInstanceList: ((data) => {
			return this.apiCall<GetServerQuizInstancesResponse>(
				'/server/GetSurveyInstances',
				data,
				{},
			);
		}) as XRServerGetQuizInstanceList,

		GetPredictionInstance: ((data) => {
			return this.apiCall<GetServerQuizInstanceResponse>(
				'/server/GetPredictionInstance',
				{
					InstanceId: data.instanceId,	
				},
				{},
			);
		}) as XRServerGetQuizInstance,

		GetTriviaInstance: ((data) => {
			return this.apiCall<GetServerQuizInstanceResponse>(
				'/server/GetTriviaInstance',
				{
					InstanceId: data.instanceId,
				},
				{},
			);
		}) as XRServerGetQuizInstance,

		GetSurveyInstance: ((data) => {
			return this.apiCall<GetServerQuizInstanceResponse>(
				'/server/GetSurveyInstance',
				{
					InstanceId: data.instanceId,
				},
				{},
			);
		}) as XRServerGetQuizInstance,

		GetQuizInstance: ((data) => {
			return this.apiCall<GetServerQuizInstanceResponse>(
				'/server/GetQuizInstance',
				{
					InstanceId: data.instanceId,
				},
				{},
			);
		}) as XRServerGetQuizInstance,

		GetPredictionInstanceStatistics: ((data) => {
			return this.apiCall<GetQuizInstanceStatisticsResponse>(
				'/server/GetPredictionInstanceStatistics',
				{
					InstanceId: data.instanceId,	
				},
				{},
			);
		}) as XRServerGetQuizInstanceStatistics,

		GetTriviaInstanceStatistics: ((data) => {
			return this.apiCall<GetQuizInstanceStatisticsResponse>(
				'/server/GetTriviaInstanceStatistics',
				{
					InstanceId: data.instanceId,
				},
				{},
			);
		}) as XRServerGetQuizInstanceStatistics,

		GetSurveyInstanceStatistics: ((data) => {
			return this.apiCall<GetQuizInstanceStatisticsResponse>(
				'/server/GetSurveyInstanceStatistics',
				{
					InstanceId: data.instanceId,
				},
				{},
			);
		}) as XRServerGetQuizInstanceStatistics,

		GetPredictionDefinitions: ((data) => {
			return this.apiCall<GetQuizDefinitionsResponse>(
				'/server/GetPredictionDefinitions',
				{
					DefinitionId: data?.definitionId,
				},
				{},
			);
		}) as XRServerGetQuizDefinitions,

		GetTriviaDefinitions: ((data) => {
			return this.apiCall<GetQuizDefinitionsResponse>(
				'/server/GetTriviaDefinitions',
				{
					DefinitionId: data?.definitionId,
				},
				{},
			);
		}) as XRServerGetQuizDefinitions,

		GetSurveyDefinitions: ((data) => {
			return this.apiCall<GetQuizDefinitionsResponse>(
				'/server/GetSurveyDefinitions',
				{
					DefinitionId: data?.definitionId,
				},
				{},
			);
		}) as XRServerGetQuizDefinitions,

		GetQuizDefinitions: ((data) => {
			return this.apiCall<GetQuizDefinitionsResponse>(
				'/server/GetQuizDefinitions',
				{
					DefinitionId: data?.definitionId,
				},
				{},
			);
		}) as XRServerGetQuizDefinitions,

		ResolvePredictionInstance: (data: { instanceId:string, result?:XRQuizAnswers, matchId?: string, context?: Record<string, any> }) => {
			return this.apiCall<ResolvePredictionInstanceResponse>(
				'/server/ResolvePredictionInstance',
				{
					InstanceId: data.instanceId,
					Result: data.result,
					MatchId: data.matchId,
					Context: data.context,
				},
				{},
			);
		},
		
		InstantiatePrediction: ((data) => {
			return this.apiCall<InstantiateQuizResponse>(
				'/server/InstantiatePrediction',
				{
					DefinitionId: data.definitionId,	
					MatchId: data.matchId,
					Context: data.context,
				},
				{},
			);
		}) as XRServerInstantiateQuiz,

		InstantiateTrivia: ((data) => {
			return this.apiCall<InstantiateQuizResponse>(
				'/server/InstantiateTrivia',
				{
					DefinitionId: data.definitionId,
					MatchId: data.matchId,
					Context: data.context,
				},
				{},
			);
		}) as XRServerInstantiateQuiz,

		InstantiateSurvey: ((data) => {
			return this.apiCall<InstantiateQuizResponse>(
				'/server/InstantiateSurvey',
				{
					DefinitionId: data.definitionId,
					MatchId: data.matchId,
					Context: data.context,
				},
				{},
			);
		}) as XRServerInstantiateQuiz,

		InstantiatePoll: (data: { itemId:string, matchId:string, segmentId?:string, playerId?:string }) => {
			return this.apiCall<InstantiatePollResponse>(
				'/server/InstantiatePoll',
				{
					ItemId: data.itemId,	
					MatchId: data.matchId,
					SegmentId: data.segmentId,
					PlayerId: data.playerId,
				},
				{},
			);
		},
		
		LoginWithServerCustomID: (data:{ ServerCustomId:string }) => {
			return this.apiCall<AuthResponse>('/auth/LoginWithServerCustomId', data, {});
		},

		SetPlayerDisplayName: (data:{ DisplayName:string, PlayFabId: string }) => {
			return this.apiCall<SetPlayerDisplayNameResponse>(
				'/server/SetPlayerDisplayName',
				data,
				{},
			);
		},

		GetPoll: ((data?:{ InstanceId?: string }) => {
			if (data) return this.apiCall<GetPollSingleResponse>('/server/GetPoll', data, {});
			return this.apiCall<GetPollMultipleResponse>('/server/GetPoll', {}, {});
		}) as {
			(): Promise<GenericApiCallResponse<GetPollMultipleResponse>>;
			(data:{ InstanceId?: string }): Promise<GenericApiCallResponse<GetPollSingleResponse>>;
		},


		WriteTitleEvent: (data: { EventName:string, Body?:Record<string, any>, CustomTags?:Record<string, string | number> }) => {
			return this.apiCall<WriteEventResponse>(
				'/server/WriteTitleEvent',
				data,
				{},
			);
		},

		WritePlayerEvent: (data: { PlayFabId: string, EventName:string, Body?:Record<string, any>, CustomTags?:Record<string, string | number> }) => {
			return this.apiCall<WriteEventResponse>(
				'/server/WritePlayerEvent',
				data,
				{},
			);
		},

		RealtimePerformanceHeartbeat: (data: { EmitTs: string | number, PlayfabEventTs:string | number, RealtimeIngestTs: string | number, ReceiveTs: string | number }) => {
			return this.apiCall<RealtimePerformanceHeartbeatResponse>(
				'/server/RealtimePerformanceHeartbeat',
				data,
				{},
			);
		},

		GetRealtimePerformance: (data: { Limit?: number }) => {
			return this.apiCall<GetRealtimePerformanceResponse>(
				'/server/GetRealtimePerformance',
				data,
				{},
			);
		},

		SetPlayerStatistics: (data: { PlayFabId: string, Statistics: IPlayFabStatistic[] }) => {
			return this.apiCall<SetPlayerStatisticsResponse>(
				'/server/SetPlayerStatistics',
				data,
				{},
			);
		},
		UpdatePlayerStatistics: (data: { PlayFabId: string, Statistics: IPlayFabStatistic[] }) => {
			return this.apiCall<SetPlayerStatisticsResponse>(
				'/server/UpdatePlayerStatistics',
				data,
				{},
			);
		},


		GrantCurrenciesToPlayer: (data: { PlayFabId: string, Currencies: Record<string, number> }) => {
			return this.apiCall<GrantCurrenciesResponse>(
				'/server/GrantCurrenciesToPlayer',
				data,
				{},
			);
		},
	};

	public Service = {
		OpenAI: {
			ChatCompletion: (data:{ Message:string, PlayFabId?:string, SessionId?: string }) => {
				return this.apiCall<OpenAIChatCompletionResponse>(
					'/service/openai/ChatCompletion',
					data,
					{},
				);
			},
			GenerateImages: (data:{ Prompt:string, Number?:number, Size?: OpenAIImageSizes }) => {
				return this.apiCall<OpenAIGenerateImagesResponse>(
					'/service/openai/GenerateImages',
					data,
					{},
				);
			},
		},
		Realtime: {
			GetRealtimeResources: () => {
				return this.apiCall<GetRealtimeResourcesResponse>(
					'/service/realtime/GetRealtimeResources',
					{},
					{},
				);
			},
		},
	};
}

export default PlayFabXRAdmin;