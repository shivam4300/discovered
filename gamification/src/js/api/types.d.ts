
interface IXRConfig {
	apiUrl: string;
	appId: string;
	apiKey?: string;
	apiSecret?: string;
}

interface IPlayFabConfig {
	appId: string;
}
interface IRealtimeConfig {
	apiKey: string;
	apiUrl: string;
	eventSourceUrl: string;
}

interface IApiConfig {
	xr?: IXRConfig;
	playfab?: IPlayFabConfig;
	realtime?: IRealtimeConfig;
	getConfig?: () => IApiConfig;
}


type XRQuestionId = string;
type XRInputId = string;
type XRRegionId = string;

interface XRQuizAnswers {
	[questionId: XRQuestionId]: string | { [inputId: XRInputId]: string };
}

interface IXRApiErrorResponse {
	error: string,
	errorInfo: any,
}

