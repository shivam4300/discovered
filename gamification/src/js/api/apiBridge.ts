import { API_CONFIG } from '../Constants';

import PlayFabXRAdmin from './PlayFabXRAdmin';
import PlayFabXR from './PlayFabXR';
import { getJSON, METHOD_JSON, METHOD_POST } from './api';

/**
Gets the PlayFabXRAdmin and PlayFabXR instances with the default configuration 
*/

const getConfig = ():IApiConfig => {
	if (API_CONFIG.getConfig) return API_CONFIG.getConfig();
	return API_CONFIG;
};

export function getXrAdminApi() {
	const { xr:XR_CONFIG } = getConfig();
	if (!XR_CONFIG?.apiUrl || !XR_CONFIG?.appId || !XR_CONFIG?.apiKey || !XR_CONFIG?.apiSecret) {
		throw new Error('PlayFabXRAdmin is not configured. Please set the API_CONFIG object in Constants.ts');
	}
	return PlayFabXRAdmin.GetInstance(XR_CONFIG.apiUrl, XR_CONFIG.appId, XR_CONFIG.apiKey, XR_CONFIG.apiSecret);
}

export function getXrApi() {
	const { xr:XR_CONFIG } = getConfig();
	if (!XR_CONFIG?.apiUrl || !XR_CONFIG?.appId) {
		throw new Error('PlayFabXR is not configured. Please set the API_CONFIG object in Constants.ts');
	}
	return PlayFabXR.GetInstance(XR_CONFIG.apiUrl, XR_CONFIG.appId);
}

export function realtimeApi<T>(endpoint:string, data:Record<string, any> | null = null):Promise<T> {
	const { realtime:REALTIME_CONFIG, playfab:PLAYFAB_CONFIG } = getConfig();
	if (!REALTIME_CONFIG?.apiUrl || !REALTIME_CONFIG?.apiKey || !PLAYFAB_CONFIG?.appId) {
		throw new Error('Realtime or Playfab is not configured. Please set the API_CONFIG object in Constants.ts');
	}
	const url = `${REALTIME_CONFIG.apiUrl}/${endpoint}`;
	return getJSON(url, {
		'AppId': PLAYFAB_CONFIG.appId,
		...data,
	}, METHOD_POST, [['X-Api-Key', REALTIME_CONFIG.apiKey]]) as Promise<T>;
}

export function playfabClientApi(endpoint:string, data:Record<string, any>, method = METHOD_JSON):Promise<any> {
	const { playfab:PLAYFAB_CONFIG } = getConfig();
	if (!PLAYFAB_CONFIG?.appId) {
		throw new Error('Playfab is not configured. Please set the API_CONFIG object in Constants.ts');
	}
	const url = `https://${PLAYFAB_CONFIG.appId}.playfabapi.com/Client/${endpoint}`;
	return getJSON(url, data, method, [['X-Authorization', getXrApi().GetSessionTicket()]]);
}


export function playfabCloudScriptApi(endpoint:string, data:Record<string, any>, method = METHOD_JSON):Promise<any> {
	const { playfab:PLAYFAB_CONFIG } = getConfig();
	if (!PLAYFAB_CONFIG?.appId) {
		throw new Error('Playfab is not configured. Please set the API_CONFIG object in Constants.ts');
	}
	const url = `https://${PLAYFAB_CONFIG.appId}.playfabapi.com/CloudScript/${endpoint}`;
	return getJSON(url, data, method, [['X-EntityToken', getXrApi().GetEntityToken()]]);
}

export function playfabEventApi(endpoint:string, data:Record<string, any>, method = METHOD_JSON):Promise<any> {
	const { playfab:PLAYFAB_CONFIG } = getConfig();
	if (!PLAYFAB_CONFIG?.appId) {
		throw new Error('Playfab is not configured. Please set the API_CONFIG object in Constants.ts');
	}
	const url = `https://${PLAYFAB_CONFIG.appId}.playfabapi.com/Event/${endpoint}`;
	return getJSON(url, data, method, [['X-EntityToken', getXrApi().GetEntityToken()]]);
}
