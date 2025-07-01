type XRService = {
	id: number,
	serviceName: string,
	defaultConfig: any[],
	properties: {
		name: string,
		type: string,
	}[],
	isActive: boolean,
	isNode: boolean,
}

type XRConfig = {
	config: Record<string, any>,
	id: number,
	service: XRService,
	isActive: boolean,
}

interface GetXRAdminAuthenticationResponse extends Partial<IXRApiErrorResponse> {
	DisplayName: string,
	Role: string,
	Key: string,
	Secret: string,
}
interface GetConfigSingleResponse extends Partial<IXRApiErrorResponse> {
	Config: XRConfig,
}

interface GetConfigMultipleResponse extends Partial<IXRApiErrorResponse> {
	Configs: XRConfig[],
}

type XRCustomEvent = {
	id: number,
	eventName: string,
	dataModel: Record<string, { type: 'string' | 'date' | 'int' }>,
	app?: any,
};
interface GetCustomEventsResponse extends Partial<IXRApiErrorResponse> {
	CustomEvents: XRCustomEvent[],
	CustomEvent?: XRCustomEvent,
}

interface UpdateQuizDataResponse extends Partial<IXRApiErrorResponse> {
	itemType: string,
	itemId: string,
	updatedOn: number,
	quizdata: object,
}
interface PublishQuizDefinitionResponse extends Partial<IXRApiErrorResponse> {
	challengeId: string,
	jsonUrl: string,
}

interface UpdateQuizResultResponse extends Partial<IXRApiErrorResponse> {
	challengeId: string,
}

interface GetQuizDataResponse<DataType> extends Partial<IXRApiErrorResponse> {
	itemType: string,
	items: {
		itemId: string,
		updatedOn: number,
		deletedOn: number,
		data: DataType,
	}[],
}

interface DataListItem {
	itemId: string,
	updatedOn: number,
	deletedOn: number,
	displayName: string,
	description?: string,
}

interface GetQuizDataListResponse extends Partial<IXRApiErrorResponse> {
	itemType: string,
	count: number,
	items: DataListItem[],
}

interface XRServerQuizInstanceItem extends XRQuizInstanceItem {
	displayName: string,
	timestamp: number,
	apiLog: {
		action: string,
		timestamp: string,
		matchId?: string,
		context?: Record<string, any>,
		result?: {
			isResolved: boolean,
			result: XRQuizAnswers,
		},
	}[],
	result: XRQuizAnswers,
	resolveRules: XRQuizResolveRules,
}

interface GetServerQuizInstancesResponse extends Partial<IXRApiErrorResponse> {
	QuizInstancesCount: number,
	QuizInstances: XRServerQuizInstanceItem[]
}
interface GetServerQuizInstanceResponse extends Partial<IXRApiErrorResponse> {
	QuizInstance: XRServerQuizInstanceItem
}

interface XRQuizItemStatistics  {
	total: number,
	region: Record<XRRegionId, number>,
}
interface XRQuizInstanceWinnersStatistics {
	challenge: XRQuizItemStatistics,
	questions: Record<XRQuestionId, XRQuizItemStatistics>,
}
interface XRQuizInstanceParticipantsStatistics {
	challenge: XRQuizItemStatistics,
	questions: Record<XRQuestionId, Record<XRInputId, XRQuizItemStatistics>>,
}

interface GetQuizInstanceStatisticsResponse extends Partial<IXRApiErrorResponse> {
	Participants: XRQuizInstanceParticipantsStatistics,
	Winners: XRQuizInstanceWinnersStatistics,
	Result: XRQuizAnswers
}

interface QuizInputResult {
	id: string,
	value?: string,
}

interface XRQuizDefinitionItem {
	itemId: string,
	displayName: string,
	publishedOn: string,
	updatedOn: string,
	type: string,
	voteExpiration: number,
	totalExpiration?: number,
	resolveRules: XRQuizResolveRules,
	data: XRQuizDefinitionData,
}

interface XRReward {
	id: string,
	dataType?: string,
	dataKey?: string,
	dataVal?: string | number,
	typeLabel?: string,
	displayName?: string,
	color?:string,
	icon?:string,	
	algorithm?:string,
	minVal?:number,
}

interface XRQuizRewards {
	participation?: XRReward[],
	challenge?: XRReward[],
	questions: Record<XRQuestionId, {
		question: XRReward[]
		choices?: Record<XRInputId, XRReward[]>,
	}>,
}
interface XRQuizRulesQuestion {
	questionId: string,
	event?: string,
	choices: {
		id: string,
		code: string,
		label: string,
		rules?: {
			comparandSource: string,
			comparator: string,
			comparandTarget: string,
		}[],
		isValid?: boolean,
	}[],
	inputs: {
		id: string,
		label: string,
		comparandSource: string,
	}[],
	hasUserInputs: boolean,
}

interface XRQuizResolveRules {
	questions: XRQuizRulesQuestion[],
	events?: {
		instantiate: string,
		resolve: string,
	},
}

interface XRQuizDefinitionData {
	id: string,
	displayName: string,
	voteExpiration: number,
	totalExpiration?: number,
	customData: Record<string, any>,
	questions: XRQuizInstanceQuestion[],
}


interface GetQuizDefinitionsResponse {
	QuizDefinitions: XRQuizDefinitionItem[],
}

interface QuizResultResponse {
	Result: XRQuizAnswers,
	StatsAwardedCount: number,
	IsResolved: boolean,
}
interface ResolvePredictionInstanceResponse extends Partial<IXRApiErrorResponse> {
	PredictionInstanceResult: Record<string, QuizResultResponse>,
}


interface InstantiateQuizResponse extends Partial<IXRApiErrorResponse> {
	QuizInstance: XRQuizInstanceItem,
	QuizData: XRQuizInstanceData,
}
interface GetCombinedContentsResponse extends Partial<IXRApiErrorResponse> {
	CombinedContents: {
		currencies: { CatalogVersion:string, DisplayName:string, ItemId:string }[],
		drop_tables: { CatalogVersion:string, DisplayName:string, ItemId:string }[],
		itemClass: { title:string, layoutIcon: string, layoutColor: string }[],
		items: { CatalogVersion:string, Class:string, Color:string, DisplayName:string, Icon:string, ItemId:string }[],
		missions: { CatalogVersion:string, Color:string, DisplayName:string, Icon:string, ItemId:string }[],
	}
}

interface XRMediaLibraryItem {
	id: number,
	fileName: string,
	fileExtension: string,
	url: string,
	fileSize: number,
}

interface UploadMediaLibraryChunkResponse extends Partial<IXRApiErrorResponse> {
	progress: number,
	media: XRMediaLibraryItem,
}

interface GetMediaLibraryListResponse extends Partial<IXRApiErrorResponse> {
	currentPage: number,
	nPages: number,
	nMedias: number,
	medias: XRMediaLibraryItem[],
}


type UpdateMissionProps = Partial<{
	ItemId: string,
	MissionType: number,
	MissionState: string,
	DisplayName: string,
	Tags: string,
	IsStackable: boolean,
	Listed: boolean,
	Replayable: boolean,
	Repeatable: boolean,
	ConsumableCount: number,
	ConsumableTime: number,
}>;

type UpdateMissionDataProps = Partial<{
	ItemId: string,
	DataKey: string,
	DataVal: string,
}>;

interface IXRAdminMissionObjective {
	data: any[],
	id: number,
	dataTrigger: IXRAdminMissionObjectiveTrigger[],
	rewards: IXRMissionReward[],
	title: string,
	type: {
		id: number,
		title?: string,
		layoutColor?: string,
		layoutIcon?: string,
	}
}

interface IXRAdminMission {
	data: any[],
	id: number,
	itemId: string,
	listed: boolean,
	objectives: IXRAdminMissionObjective[],
	playfab: IPlayFabCatalogItem,
	repeatable: boolean,
	replayable: boolean,
	rewards: IXRMissionReward[],
	state: 'draft' | 'staging' | 'released';
	type: {
		title: string,
		id: number,
		isCommunity: boolean,
		layoutColor: string,
		layoutIcon: string,
	}
}

interface UpdateMissionResponse extends Partial<IXRApiErrorResponse> {
	mission: IXRAdminMission,
}

interface GetMissionProps {
	ItemId: string,
}

interface GetMissionResponseSingle extends Partial<IXRApiErrorResponse> {
	mission: IXRAdminMission,
}
interface GetMissionResponseMultiple extends Partial<IXRApiErrorResponse> {
	missions: IXRAdminMission[],
}

interface IXRAdminMissionObjectiveTrigger {
	id: string,
	type: string,
	rewards: any[],
	eventName: string,
	eventThreshold: string,
	eventConditions: {
		eventKey: string,
		eventOperator: string,
		eventValue: string,
	}[],
}

interface UpdateMissionObjectiveProps {
	ItemId: string,
	ObjectiveType: number,
	ObjectiveId?: number,
	Title?: string,
	DataTrigger?: IXRAdminMissionObjectiveTrigger[],
}

interface UpdateMissionObjectiveResponse extends Partial<IXRApiErrorResponse> {
	MissionObjective: {
		data: any[],
		id: number,
		rewards: any[],
		title: string,
		type: {
			id: number,
			title: string,
			layoutColor: string,
			layoutIcon: string,
		}
	}
}

interface UpdateMissionObjectiveDataProps {
	DataKey: string,
	DataVal: number | string,
	ObjectiveId: number,
}

interface UpdateMissionObjectiveDataResponse extends Partial<IXRApiErrorResponse> {
	MissionObjectiveData: {
		dataKey: string;
		dataVal: number;
		id: number;
		dataProp: {
			options: {
				maxlength: string;
			};
			dataType: {
				type: string;
				id: number;
			};
			gridPos: string;
		}
	}
}

interface UpdateMissionObjectiveRewardResponse {
	ItemId: string,
	RewardId?: number,
	ObjectiveId: number,
	DataType: string,
	DataKey: string,
	DataVal: string | number,
}

interface DeleteMissionObjectiveRewardResponse {
	ItemId: string,
	RewardId: number,
	ObjectiveId: number,
	DataType: string,
	DataKey: string,
	DataVal: string | number,
}

interface GetStatsAndLeaderboardsResponse extends Partial<IXRApiErrorResponse> {
	AppStatistics: {
		id: number,
		app: { appId: string, title: string, id: number },
		statName: string,
		statValue: number,
	}[],
	PlayerStatistics: {
		AggregationMethod: string,
		CurrentVersion: number,
		DeletionInProgress: boolean,
		StatisticName: string,
		VersionChangeInterval: string,
	}[]
}

interface GetItemSingleResponse extends Partial<IXRApiErrorResponse> {
	item: IXRInventoryItem,
}

interface GetItemMultipleResponse extends Partial<IXRApiErrorResponse> {
	items: IXRInventoryItem[],
}

interface IXRMissionType {
	title: string,
	id: number,
	isCommunity: boolean,
	layoutColor: string,
	layoutIcon: string,
	props: IXRProperty[],
	app: IXRAppDefinition,
}

interface IXRMissionObjectiveType {
	title: string,
	id: number,
	layoutColor: string,
	layoutIcon: string,
	props: IXRProperty[],
	app: IXRAppDefinition,
}

interface GetMissionTypeMultipleResponse extends Partial<IXRApiErrorResponse> {
	MissionTypes: IXRMissionType[]
}
interface GetMissionTypeSingleResponse extends Partial<IXRApiErrorResponse> {
	MissionType: IXRMissionType
}

interface GetMissionObjectiveTypeMultipleResponse extends Partial<IXRApiErrorResponse> {
	ObjectiveTypes: IXRMissionObjectiveType[]
}
interface GetMissionObjectiveTypeSingleResponse extends Partial<IXRApiErrorResponse> {
	ObjectiveType: IXRMissionObjectiveType
}

interface UpdateMissionTypeProps {
	Title?: string,
	LayoutIcon?: string,
	LayoutColor?: string,
	IsCommunity?: boolean,
	MissionTypeId?: number,
}

interface UpdateMissionTypeResponse extends Partial<IXRApiErrorResponse> {
	MissionType: IXRMissionType,
}

interface IXRMissionTypePropertyDataType {
	type: string,
	id: number,
}

type IXRMissionTypePropertyScope = 'public' | 'private' | 'internal';

type IXRProperty = {
	title: string,
	id: number,
	type: IXRMissionType,
	dataType: IXRMissionTypePropertyDataType,
	options: any,
	gridPos: number,
	scope: IXRMissionTypePropertyScope,
};

interface UpdateMissionTypePropertyProps {
	PropertyId?: number,
	TypeId: number,
	DataTypeId: number,
	Scope: string,
	Title: string,
	Options?: any,
	GridPos: number,
}
interface UpdateMissionTypePropertyResponse extends Partial<IXRApiErrorResponse> {
	Property: IXRProperty,
}

interface UpdateMissionObjectiveTypePropertyProps extends Partial<IXRApiErrorResponse> {
	PropertyId?: number,
	TypeId: number,
	DataTypeId: number,
	Scope: string,
	Title: string,
	Options?: any,
	GridPos: number,
}
interface UpdateMissionObjectiveTypePropertyResponse extends Partial<IXRApiErrorResponse> {
	Property: IXRProperty,
}

interface UpdateMissionObjectiveTypeProps {
	Title?: string,
	LayoutIcon?: string,
	LayoutColor?: string,
}

interface UpdateMissionObjectiveTypeResponse extends Partial<IXRApiErrorResponse> {
	MissionObjectiveType: IXRMissionObjectiveType
}

interface IXRAppDefinition {
	appId: string,
	title: string,
	id: number,
	studio: {
		studioId: string,
		title: string,
		id: number,
		apps: number[],
		isAdmin: false
	}
}

interface SetPlayerDisplayNameResponse extends Partial<IXRApiErrorResponse> {
	DisplayName: string
}

interface OpenAIChatCompletionResponse extends Partial<IXRApiErrorResponse> {
	ChatResponse: {
		id: string,
		object: string,
		created: number,
		model: string,
		choices: {
			index: number,
			message: {
				role: string,
				content: string,
			},
			finish_reason: string,
		}[],
		usage: {
			prompt_tokens: number,
			completion_tokens: number,
			total_tokens: number,
		}
	}
}

interface OpenAIGenerateImagesResponse extends Partial<IXRApiErrorResponse> {
	Images: {
		created: number,
		data: {
			url: string,
		}[],
	},
}


interface XRPollDefinitionItem {
	itemId: string,
	displayName: string,
	question: string,
	customData: Record<string, any>,
	voteExpiration: number,
	totalExpiration: number,
	answers: {
		id: string,
		label: string,
		isValid: boolean,
		rewards: XRReward[],
	}[],
	rewards: XRReward[],
}

interface UpdatePollDataProps {
	ItemId?: string,
	DisplayName: string,
	Question: string,
	CustomData?: Record<string, any>,
	VoteExpiration?: number,
	TotalExpiration?: number,
	Answers?: {
		id: string,
		label: string,
		isValid: boolean,
		rewards: XRReward[],
	}[],
	Rewards?: XRReward[],
}

interface UpdatePollResponse {
	Poll: XRPollDefinitionItem,
}
interface AdminGetPollsResponse {
	Polls: XRPollDefinitionItem[],
}

interface AdminGetPollResponse {
	Poll: XRPollDefinitionItem,
}

interface InstantiatePollResponse extends Partial<IXRApiErrorResponse> {
	PollInstance: XRPoll,
}

interface WriteEventResponse extends Partial<IXRApiErrorResponse> {
	EventResponse: {
		EventId: string,
	}
}

interface RealtimePerformanceHeartbeatResponse extends Partial<IXRApiErrorResponse> {
	PlayfabEvent: number,
	RealtimeIngest: number,
	Receive: number,
	Total: number,
}

interface XRRealtimePerformanceItem {
	min: number,
	max: number,
	avg: number,
	median: number,
	stddev: number,
	percentile90: number,
	percentile95: number,
	percentile99: number,
	n: number,
}
interface XRRealtimePerformanceMetrics {
	Metrics: {
		PlayfabEvent: XRRealtimePerformanceItem,
		RealtimeIngest: XRRealtimePerformanceItem,
		Receive: XRRealtimePerformanceItem,
		Total: XRRealtimePerformanceItem,
	},
	LatestMetrics?: {
		PlayfabEvent: XRRealtimePerformanceItem,
		RealtimeIngest: XRRealtimePerformanceItem,
		Receive: XRRealtimePerformanceItem,
		Total: XRRealtimePerformanceItem,
	},
	OldestCallTime: number,
	LatestCallTime: number,
}
interface GetRealtimePerformanceResponse extends Partial<IXRApiErrorResponse> {
	Result: XRRealtimePerformanceMetrics,
}
interface GetRealtimeResourcesResponse extends Partial<IXRApiErrorResponse> {
	Webhook: {
		url: string,
		key: string,
	},
	Hub: {
		type: string,
		url: string,
		publicUrl: string,
		titleUrl:string,
		topics: string[],
	},
	Metrics: {
		rulesExecuted: number,
		notifications: number,
	},
}
interface SetPlayerStatisticsResponse extends Partial<IXRApiErrorResponse> {
	Statistics: IPlayFabStatistic[],
}
interface GrantCurrenciesResponse extends Partial<IXRApiErrorResponse> {
	GrantedCurrencies: {
		VirtualCurrency: string,
		BalanceChange: number,
		Balance: number,
	}[],
}

interface XRServerGetQuizInstanceList {
	 (data: { search?:string, sort?:string, dir?: number, offset?:number, limit?:number }) : Promise<GenericApiCallResponse<GetServerQuizInstancesResponse>>
}
interface XRServerGetQuizInstance {
	 (data: { instanceId:string }) : Promise<GenericApiCallResponse<GetServerQuizInstanceResponse>>
}

interface XRServerCancelQuizInstance {
	 (data: { instanceId:string }) : Promise<GenericApiCallResponse<null>>
}
interface XRServerGetQuizInstanceStatistics {
	 (data: { instanceId:string }) : Promise<GenericApiCallResponse<GetQuizInstanceStatisticsResponse>>
}

interface XRServerGetQuizDefinitions {
	 (data?: { definitionId?:string }) : Promise<GenericApiCallResponse<GetQuizDefinitionsResponse>>
}
interface XRServerInstantiateQuiz {
	 (data: { definitionId:string, matchId:string, context?:Record<string, any> }) : Promise<GenericApiCallResponse<InstantiateQuizResponse>>
}