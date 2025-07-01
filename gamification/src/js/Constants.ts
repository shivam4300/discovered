export const GLOBAL_NAMESPACE = 'discovered-gamification';

export const APP_VERSION = '2022-09-06.1';

declare global {
	interface Window {
		APP_CONFIG: {
			BASE_PATH: string;
			AS_STACK_APP_ID: string;
			PLAYFAB_APP_ID: string;
			REALTIME_API_KEY: string;
			XR_API: string;
			REALTIME_API: string;
		};
	}
}

export const XR_CONFIG = {
	apiUrl: window.APP_CONFIG.XR_API,
	appId: window.APP_CONFIG.AS_STACK_APP_ID,
} as IXRConfig;

export const PLAYFAB_CONFIG = {
	appId: window.APP_CONFIG.PLAYFAB_APP_ID,
} as IPlayFabConfig;

export const REALTIME_CONFIG = {
	apiUrl: window.APP_CONFIG.REALTIME_API,
} as IRealtimeConfig;

export const API_CONFIG = {
	xr: XR_CONFIG,
	playfab: PLAYFAB_CONFIG,
	realtime: REALTIME_CONFIG,
} as IApiConfig;

export const STORE_NAME = 'Discovered';

export const ENDPOINTS = {};

export const DEFAULT_LANG = 'en';

export const CDN_BASE = '/';

export const DEFAULT_ERROR_MESSAGE = 'An error has occurred';

export const BASE_PATH = window.APP_CONFIG.BASE_PATH;

export const ROUTES = {
	ROOT: '/',
	SEASON_PASS: 'season-pass',
	LOOTBOXES: 'lootboxes',
	PLAYER_PROFILE: 'player-profile',
	PLAYER_INVENTORY: 'player-inventory',
	LEADERBOARD: 'leaderboard',
};

export const PAGE_KEYS = {
	LOGIN: 'login',
	SEASON_PASS: 'season_pass',
	LOOTBOXES: 'lootboxes',
	PLAYER_PROFILE: 'profile',
	PLAYER_INVENTORY: 'inventory',
	LEADERBOARD: 'leaderboard',
};

export const EXCLUDED_LOGGER_ACTIONS = [] as string[];

export const POLL_RATES = {
	SEND_HEARTBEAT: 1000 * 60,
};

export const ITEM_CLASSES = {
	OVERRIDE: 'Override',
	BADGE: 'Badges',
	PINS: 'Pins',
	NOTIFICATIONS: 'Notifications',
	BUNDLE: 'Bundle',
};

export const USER_DEFAULT_IMAGE = '/repo/images/user/user.png';

export const MISSIONS_TYPES = {
	WEEKLY_DISCOVERY_CHALLENGE: 'Weekly Discovery Challenge',
};
