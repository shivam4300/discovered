import Player from "video.js/dist/types/player";
import { IAppDispatch } from "../redux/config/store";
import { writePlayerEvent } from "../redux/playfab";

export const VIDEO_PLAYER_ID = 'my_video';

export type PlayerWithAds = Player & {
	ima: any;
	playlist: any;
};

export function getImaFromPlayer(player: PlayerWithAds) {
	let interval = null;

	const getIMA = new Promise<any>(async (resolve) => {
		player.on('ads-manager', (resp) => resolve(resp.adsManager));
	});

	return {
		getIMA,
		cancelIMA: () => clearInterval(interval),
	};
}

export function getPlayerById(id: string) {
	let interval = null;
	const getPlayers = new Promise<PlayerWithAds>(async (resolve, reject) => {
		const expired = 30 * 1000;
		const start = Date.now();
		interval = setInterval(() => {
			const player = globalThis.videojs
				.getAllPlayers()
				.find((p) => p.id() === id);
			if (player) {
				resolve(player as PlayerWithAds);
				clearInterval(interval);
			}

			if (Date.now() - start > expired) {
				reject('Player not found');
				clearInterval(interval);
			}
		}, 100);
	});

	return {
		getPlayers,
		cancelGetPlayers: () => clearInterval(interval),
	};
}

export function getCurrentVideo(player) {
	// const currentItem = player.playlist.currentItem();
	const currentItem = globalThis.currentVideoIndex;  //This is coming from the player_live_initialize.js
	const currentVideo = globalThis.mainPlaylist[currentItem];
	return currentVideo;
}



export function interactAd(action: string, adData: any, dispatch: IAppDispatch) {
	getPlayerById(VIDEO_PLAYER_ID).getPlayers.then((player) => {
		const currentVideo = getCurrentVideo(player);
		dispatch(
			writePlayerEvent({
				name: 'player_interacted_ad',
				body: {
					adId: adData?.getAdId?.(),
					adClickThroughUrl: adData?.data?.clickThroughUrl,
					action,
					videoId: currentVideo?.single_video?.post_key,
				},
			})
		);
	});
}