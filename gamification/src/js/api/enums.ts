export enum XRPropertyTypesId {
	Text = 1,
	Textarea = 2,
	Select = 3,
	Checkbox = 4,
	JSON = 5,
	Custom = 6,
	Item = 7,
	Table = 8,
	List = 9,
	DateTime = 10,
	FlowEditor = 11,
	Color = 12,
	Media = 13,
	Slider = 14,
}

export const XRPropertyTypes = {
	text: { id: XRPropertyTypesId.Text, name: 'Text' },
	textarea: { id: XRPropertyTypesId.Textarea, name: 'Textarea' },
	select: { id: XRPropertyTypesId.Select, name: 'Select' },
	checkbox: { id: XRPropertyTypesId.Checkbox, name: 'Checkbox' },
	json: { id: XRPropertyTypesId.JSON, name: 'JSON' },
	custom: { id: XRPropertyTypesId.Custom, name: 'Custom' },
	item: { id: XRPropertyTypesId.Item, name: 'Item' },
	table: { id: XRPropertyTypesId.Table, name: 'Table' },
	list: { id: XRPropertyTypesId.List, name: 'List' },
	datetime: { id: XRPropertyTypesId.DateTime, name: 'Datetime' },
	flowEditor: { id: XRPropertyTypesId.FlowEditor, name: 'Flow editor' },
	color: { id: XRPropertyTypesId.Color, name: 'Color' },
	media: { id: XRPropertyTypesId.Media, name: 'Media' },
	slider: { id: XRPropertyTypesId.Slider, name: 'Slider' },
};

export enum RewardTypes {
	MissionGrant = 'mission_grant',
	ItemGrant = 'item_grant',
	CurrencyGrant = 'currency_grant',
	PlayerStatistic = 'stat_change',
	AppStatistic = 'app_stat',
	InstanceStatistic = 'instance_stat',
	CustomEvent = 'custom_payload',
}

export const QUIZ_TYPES = {
	PREDICTION: 'prediction',
	SURVEY: 'survey',
	TRIVIA: 'trivia',
} as const;
export type QuizType = typeof QUIZ_TYPES[keyof typeof QUIZ_TYPES];

export type Reward = {
	id: string,
	dataType?: RewardTypes,
	dataKey?: string,
	dataVal?: string | number,
	typeLabel?: string,
	displayName?: string,
	color?:string,
	icon?:string,	
	algorithm?:string,
	minVal?:number,
};

export enum OpenAIImageSizes {
	SQUARE_256 = '256x256',
	SQUARE_512 = '512x512',
	SQUARE_1024 = '1024x1024',
}

export enum XRAdActivityTypes {
	Opened = 'Opened',
	Closed = 'Closed',
	Start = 'Start',
	End = 'End',
}