import { useState, useCallback, useEffect } from 'react';
import { ANY_EVENT, RealtimeServiceTypes, getDirectRealtimeConnection, getRealtimeConnection } from '../../Realtime';
import { getXrApi } from '../../apiBridge';



function useRealtimeConnectionListeners() {
	
	const [listeners, setListeners] = useState<Record<string, ((any) => void)[]>>({});

	const addListener = useCallback((eventName, listener) => {
		setListeners((prev) => {
			const newListeners = { ...prev };
			if (!newListeners[eventName]) newListeners[eventName] = [];
			newListeners[eventName].push(listener);
			return newListeners;
		});
	}, []);

	const removeListener = useCallback((eventName, listener) => {
		setListeners((prev) => {
			if (!prev[eventName]) return prev;
			const newListeners = { ...prev };
			newListeners[eventName].splice(newListeners[eventName].indexOf(listener), 1);
			return newListeners;
		});
	}, []);


	const onMessage = useCallback((events) => {
		// console.log('onMessage', events);
		let eventsList = events;
		if (events?.EventName) {
			// V1
			eventsList = [events];
		}
		if (typeof eventsList[Symbol.iterator] === 'function') {
			// make sure event list is iterable before attempting to iterate
			for (const data of eventsList) {
				const eventName = data.EventName;
				if (listeners[eventName]) {
					for (const listener of listeners[eventName]) {
						listener(data);
					}
				}
				if (listeners[ANY_EVENT]) {
					for (const listener of listeners[ANY_EVENT]) {
						listener(data);
					}
				}
			}
		}
	}, [listeners]);

	return {
		onMessage,
		addListener,
		removeListener,
	};
}

// used when we have an XR Api URL to fetch the connectionUrl and connectionType for the Realtime services. This is the most common case.
export default function useRealtimeConnection(xrApiUrl:string, playFabAppId: string = null, playfabId:string = null, sessionTicket:string = null, topics:string[] = null) {
	
	const {
		onMessage,
		addListener,
		removeListener,
	} = useRealtimeConnectionListeners();

	useEffect(() => {
		console.log('useRealtimeConnection', xrApiUrl, playFabAppId, playfabId, sessionTicket, topics);
		if (!xrApiUrl || (!playFabAppId && !topics) || !sessionTicket) {
			return () => {};
		}
		const connection = getRealtimeConnection({
			apiUrl: xrApiUrl,
			playFabTitleId: playFabAppId,
			playFabId: playfabId,
			sessionTicket,
			topics,
		});

		connection.addListener(onMessage);

		return () => {
			connection.removeListener(onMessage);
		};
	}, [xrApiUrl, playFabAppId, playfabId, topics, onMessage, sessionTicket]);

	return {
		addListener,
		removeListener,
	};
}

// used when we already know the connectionUrl and connectionType for the Realtime services. This is used for the cases where we are connecting to a custom Realtime service.
export function useDirectRealtimeConnection(connectionUrl:string, connectionType: RealtimeServiceTypes, playFabAppId: string = null, playfabId:string = null, topics:string[] = null) {
	
	const {
		onMessage,
		addListener,
		removeListener,
	} = useRealtimeConnectionListeners();

	useEffect(() => {
		if (!connectionUrl || (!playFabAppId && !topics)) {
			return () => {};
		}
		const connection = getDirectRealtimeConnection({
			connectionUrl,
			connectionType,
			playFabTitleId: playFabAppId,
			playFabId: playfabId,
			topics,
		});

		connection.addListener(onMessage);

		return () => {
			connection.removeListener(onMessage);
		};
	}, [connectionUrl, connectionType, topics, playFabAppId, playfabId, onMessage]);

	return {
		addListener,
		removeListener,
	};
}