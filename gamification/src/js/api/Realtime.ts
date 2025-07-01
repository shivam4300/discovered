export enum RealtimeServiceTypes {
	SSE = 'MercureHub',
	AzureBlobHub = 'AzureBlobHub',
}

export const ANY_EVENT = 'ANY_EVENT';

const AzureBlobHubInterval = 1000;

type Connection = {
	close: () => void,
};

type Listener = (data) => void;

type RealtimeConnection = {
	id: string,
	connectionType: RealtimeServiceTypes,
	connectionUrl: string,
	sessionTicket: string,
	apiUrl: string,
	connection?: Connection,
	playFabTitleId: string,
	playFabId: string,
	topics: string[],
	addListener: Listener,
	removeListener: Listener,
};

const startTime = Date.now();
const evaluatedEventsIds = [];

function createSSEConnection(hubUrl, topics, onMessage) {
	const url = new URL(hubUrl);

	for (const t in topics) {
		url.searchParams.append('topic', topics[t]);
	}

	const eventSource = new EventSource(url);
	eventSource.onerror = (e) => {
		console.error(e);
	};

	eventSource.onmessage = (e) => {
		const data = JSON.parse(e.data);
		if (onMessage) onMessage(data);
	};

	return eventSource;
}

const connections:Record<string, Connection> = {};
function getConnection(realtimeConnection:RealtimeConnection, playfabAppId:string, playfabId:string, topics:string[] = null, listeners = [] as ((data)=>void)[]) {
	if (connections[realtimeConnection.connectionUrl]) return connections[realtimeConnection.connectionUrl];

	const finalTopics = topics || [
		`playstream/${playfabAppId}`,
	];

	if (playfabId) {
		finalTopics.push(`playstream/${playfabAppId}/${playfabId}`);
	}

	const onMessage = (data) => {
		listeners.forEach((listener) => {
			listener(data);
		});
	};

	if (realtimeConnection.connectionType === RealtimeServiceTypes.SSE) {
		let connection = createSSEConnection(realtimeConnection.connectionUrl, finalTopics, onMessage);

		const interval = window.setInterval(() => {
			if (connection.readyState === connection.CLOSED) {
				connection.close();
				connection = createSSEConnection(realtimeConnection.connectionUrl, finalTopics, onMessage);
			}
		}, 10000);

		const close = () => {
			connection.close();
			delete connections[playfabAppId];
			window.clearInterval(interval);
		};

		connections[playfabAppId] = {
			close,
		};
		return connections[playfabAppId];
	}

	if (realtimeConnection.connectionType === RealtimeServiceTypes.AzureBlobHub) {
		let timeout = 0;

		const close = () => {
			clearTimeout(timeout);
			// delete connections[playfabAppId];
			timeout = null;
		};

		const loop = () => {
			
			
			fetch(realtimeConnection.connectionUrl, {
				method: 'POST',
				body: JSON.stringify({
					titleId: realtimeConnection.playFabTitleId,
				}),
				headers: {
					'Content-Type': 'application/json',
					'X-Authentication': realtimeConnection.sessionTicket,
				},
			}).then(r => r.json()).then((json:Record<string, any[]>) => {
				Object.values(json)
					.flat()
					.forEach(e => {
						
						if (!evaluatedEventsIds.includes(e.EventId) && new Date(e.Timestamp).valueOf() > startTime) {
							evaluatedEventsIds.push(e.EventId);
							onMessage(e);
						}
					});
			});

			timeout = window.setTimeout(loop, AzureBlobHubInterval);
		};

		timeout = window.setTimeout(loop, AzureBlobHubInterval);

		connections[playfabAppId] = {
			close,
		};

		return connections[playfabAppId];
	}

	return null;
}

const realtimeConnections = [] as RealtimeConnection[];

type GetRealtimeConnectionPropsDefault = {
	playFabTitleId:string,
	playFabId?:string,
	topics?: string[],
};
type GetRealtimeConnectionPropsWithTicket = GetRealtimeConnectionPropsDefault & {
	apiUrl:string,
	sessionTicket:string,
};
type GetRealtimeConnectionPropsWithConnection = GetRealtimeConnectionPropsDefault & {
	connectionUrl:string,
	connectionType: RealtimeServiceTypes,
};

export function getDirectRealtimeConnection({
	playFabTitleId,
	playFabId,
	topics,
	connectionUrl,
	connectionType,
} : GetRealtimeConnectionPropsWithConnection):RealtimeConnection {
	let rtConnection = realtimeConnections.find(c => c.playFabTitleId === c.playFabTitleId && c.playFabId === playFabId && c.topics === topics);
	if (!rtConnection) {
		const listeners = [] as (() => void)[];

		rtConnection = {
			id: Date.now().toString(),
			apiUrl: null,
			playFabTitleId,
			playFabId,
			sessionTicket: null,
			connectionType,
			connectionUrl,
			topics,
			addListener: (l) => {
				listeners.push(l);
			},
			removeListener: (l) => {
				listeners.splice(listeners.indexOf(l), 1);
				setTimeout(() => {
					if (listeners.length === 0) {
						rtConnection?.connection?.close?.();
					}
				}, 50);
			},
		};
		rtConnection.connection = getConnection(rtConnection, playFabTitleId, playFabId, topics, listeners);
		realtimeConnections.push(rtConnection);
	}

	return rtConnection;

}

export function getRealtimeConnection({
	playFabTitleId,
	playFabId,
	topics,
	apiUrl,
	sessionTicket,
} : GetRealtimeConnectionPropsWithTicket):RealtimeConnection {

	let rtConnection = realtimeConnections.find(c => c.playFabTitleId === c.playFabTitleId && c.sessionTicket === sessionTicket);

	if (!rtConnection) {
		const listeners = [] as (() => void)[];

		rtConnection = {
			id: Date.now().toString(),
			apiUrl,
			playFabTitleId,
			sessionTicket,
			playFabId,
			connectionType: null,
			connectionUrl: null,
			topics: null,
			addListener: (l) => {
				listeners.push(l);
			},
			removeListener: (l) => {
				listeners.splice(listeners.indexOf(l), 1);
				setTimeout(() => {
					if (listeners.length === 0) {
						rtConnection?.connection?.close?.();
					}
				}, 50);
			},
		};

		realtimeConnections.push(rtConnection);

		fetch(apiUrl + '/Client/GetRealtimeConnection', {
			method:'POST',
			body: JSON.stringify({ titleId: playFabTitleId }),
			headers: {
				'X-Authentication': sessionTicket,
				'Content-Type': 'application/json',
			},
		}).then(j => j.json()).then(json => {
			const connection = json.connectionType ? json : json.connections[0];
			rtConnection.connectionType = connection.connectionType;
			rtConnection.connectionUrl = connection.connectionUrl;
			
			rtConnection.connection = getConnection(rtConnection, playFabTitleId, playFabId, topics || rtConnection.topics, listeners);
		});
	}

	return rtConnection;
}
