import { useCallback, useEffect, useRef, useState, Fragment } from 'react';

import useGlobalVariables from './hooks/useGlobalVariables';
import { useAppSelector, useAppDispatch } from './redux/config/store';
import { writePlayerEvent } from './redux/playfab';
import useGroupedPolls from './components/profile/hooks/useGroupedPolls';

import { Modal } from 'react-bootstrap';
import AdPoll from './components/adPolls/adPoll';
import usePlayerInformation from './components/profile/hooks/usePlayerInformation';
import { removeLocalPoll } from './redux/polls';
import {
	getPlayerById,
	VIDEO_PLAYER_ID,
	getCurrentVideo,
	getImaFromPlayer,
	interactAd,
} from './utils/videos';
import { updateLocalStatistic } from './redux/statistics';

const WATCH_THRESHOLD = 0.8;

export default function VideosApp() {
	const { PlayFabId: playerId } = usePlayerInformation();
	const dispatch = useAppDispatch();
	const [showModal, setShowModal] = useState(false);
	const [showAdGam, setShowAdGam] = useState(false);
	const [showAdPoll, setShowAdPoll] = useState(false);
	const [hideBurn, setHideBurn] = useState(false);
	const [currentAd, setCurrentAd] = useState(null);
	const { groupedPolls } = useGroupedPolls('adPoll');
	const points = useAppSelector((state) => state.statistics.points);
	const hasSentWatchEvent = useRef(false);
	const hasSentInitPoll = useRef(false);
	const {
		AdPolls: { showAdPollsInTimes },
		EarnOrBurn: { requiredPointsToBurn, rewardsForWatching },
	} = useGlobalVariables();
	const [videoSource, setVideoSource] = useState('');

	const [player, setPlayer] = useState(null);
	const [ima, setIma] = useState(null);

	const skipAd = useCallback(() => {
		if (points >= requiredPointsToBurn) {
			dispatch(writePlayerEvent({ name: 'player_skipped_ad' }));
			interactAd('burn_points_for_skipping_ad', currentAd, dispatch);
			if (ima) {
				ima.discardAdBreak();
			}
			setShowAdGam(false);
			setShowAdPoll(false);
		}
	}, [points, requiredPointsToBurn, dispatch, currentAd, ima]);

	const clickAd = useCallback(() => {
		interactAd('clicked_ad', currentAd, dispatch);
	}, [currentAd, dispatch]);

	useEffect(() => {
		if (groupedPolls.length >= 2) {
			dispatch(removeLocalPoll(groupedPolls[0]));
		}
	}, [groupedPolls, dispatch]);

	const onContentChanged = useCallback(
		(e) => {
			if (player) {
				setVideoSource(player.currentSrc());
			}
		},
		[player]
	);

	let impression = useCallback(
		(ad) => {
			let adData = ad.getAd();
			setCurrentAd(adData);
			setShowAdGam(true);

			// if skippable hide burn button
			if (adData.isSkippable()) {
				setHideBurn(true);
			} else {
				setHideBurn(false);
			}

			// if previously hided continue hiding it
			//if (adData.getAdPodInfo().data.adPosition != 1 && showAdGam == false) {
			//	setShowAdGam(false);
			//	setShowAdPoll(false);
			//}

			// Show poll for ad more then 15 seconds
			if (
				showAdPollsInTimes <= adData.getDuration() &&
				!hasSentInitPoll.current
			) {
				dispatch(
					writePlayerEvent({
						name: 'init_ad_poll',
						body: {
							adId: adData.getAdId(),
							creativeAdId: adData.getCreativeId(),
							url: adData.data.clickThroughUrl,
						},
					})
				);
				hasSentInitPoll.current = true;
				setShowAdGam(true);
				setShowAdPoll(true);
			}
		},
		[dispatch, showAdPollsInTimes]
	);

	let complete = useCallback(
		(ad) => {
			let adData = ad.getAd();
			hasSentInitPoll.current = false;

			//if last ad give rewards
			if (
				adData.getAdPodInfo().data.adPosition ==
				adData.getAdPodInfo().data.totalAds
			) {
				// trigger rewards
				dispatch(
					updateLocalStatistic({
						name: 'points',
						value: points + rewardsForWatching,
					})
				);
				dispatch(writePlayerEvent({ name: 'player_watched_ad' }));
				interactAd('earn_points_for_watching_ad', adData, dispatch);

				setTimeout(() => {
					if (player) player.pause();
				}, 150);

				// hide ui
				setShowAdGam(false);
				setShowAdPoll(false);
				setHideBurn(false);

				// show pop up for points
				setShowModal(true);
			}
		},
		[player, points, dispatch, rewardsForWatching]
	);

	let skipped = (ad) => {
		setShowAdGam(false);
		setShowAdPoll(false);
		setHideBurn(false);
	};

	useEffect(() => {
		let cleanup = () => {};

		if (ima) {
			ima.addEventListener('click', clickAd);
			ima.addEventListener('impression', impression);
			ima.addEventListener('complete', complete);
			ima.addEventListener('skip', skipped);

			cleanup = () => {
				ima.removeEventListener('impression', impression);
				ima.removeEventListener('complete', complete);
			};
		}

		return () => {
			cleanup();
		};
	}, [ima, clickAd, complete, dispatch, impression]);

	useEffect(() => {
		let cleanup = () => {};
		let cleanupIma = () => {};

		if (player) {
			const { getIMA, cancelIMA } = getImaFromPlayer(player);

			if (!playerId) return;

			const onLoadedMetaData = () => {
				hasSentWatchEvent.current = false;
			};

			const onTimeUpdate = () => {
				const percent = player.currentTime() / player.duration();

				if (percent > WATCH_THRESHOLD) {
					if (!hasSentWatchEvent.current) {
						hasSentWatchEvent.current = true;
						const currentVideo = getCurrentVideo(player);
						dispatch(
							writePlayerEvent({
								name: 'player_watched_video',
								body: {
									videoId: currentVideo.single_video.post_key,
									channelUserName: currentVideo.single_video.user_uname,
								},
							})
						);
					}
				}
			};

			player.on('loadedmetadata', onLoadedMetaData);
			player.on('contentchanged', onContentChanged);
			player.on('timeupdate', onTimeUpdate);

			cleanup = () => {
				player.off('loadedmetadata', onLoadedMetaData);
				player.off('contentchanged', onContentChanged);
				player.off('timeupdate', onTimeUpdate);
			};

			getIMA.then((ima) => {
				setIma(ima);
				cleanupIma = cancelIMA;
			});
		}

		return () => {
			cleanup();
			cleanupIma();
		};
	}, [player, videoSource, dispatch, onContentChanged, playerId]);

	useEffect(() => {
		getPlayerById(VIDEO_PLAYER_ID).getPlayers.then((p) => {
			setPlayer(p);
		});
	}, []);

	const closeModal = () => {
		setShowModal(false);
		getPlayerById(VIDEO_PLAYER_ID).getPlayers.then((player) => {
			player.play();
		});
	};

	return (
		playerId && (
			<>
				{showAdGam && (
					<div className="gam-ad-earn-burn">
						<div className="gam-ad-earn-burn-points gam-profile-points">
							<span className="gam-profile-points-icon">
								<img
									src={`${window.location.origin}/repo/images/gamification/star_point.svg`}
									alt="point-icon"
								/>
							</span>
							<span className="gam-video-points-text">{points} PTS</span>
						</div>
						<div
							className="gam-ad-earn-burn-description"
							onClick={() => setShowAdGam(false)}
						>
							<span className="gam-eb-doller">
								<svg
									xmlnsXlink="http://www.w3.org/1999/xlink"
									width="17"
									height="17"
									viewBox="0 0 45.958 45.958"
								>
									<g>
										<path
											d="M22.979 0C10.287 0 0 10.288 0 22.979s10.287 22.979 22.979 22.979 22.979-10.289 22.979-22.979S35.67 0 22.979 0zm1.391 33.215v2.66c0 .415-.323.717-.739.717h-1.773c-.416 0-.751-.302-.751-.717v-2.426c-1.632-.074-3.278-.422-4.524-.896a1.42 1.42 0 0 1-.872-1.679L16 29.748a1.42 1.42 0 0 1 1.89-.972c1.187.459 2.589.793 4.086.793 1.906 0 3.211-.736 3.211-2.074 0-1.271-1.07-2.074-3.546-2.911-3.579-1.204-6.03-2.876-6.03-6.121 0-2.943 2.083-5.251 5.644-5.954v-2.426c0-.415.355-.787.771-.787h1.773c.416 0 .721.372.721.787v2.191c1.557.067 2.681.298 3.621.604.711.232 1.131.977.944 1.703l-.254 1.008a1.418 1.418 0 0 1-1.836.991 10.24 10.24 0 0 0-3.38-.559c-2.174 0-2.877.937-2.877 1.874 0 1.104 1.171 1.806 4.014 2.877 3.98 1.405 5.579 3.245 5.579 6.254-.001 2.977-2.104 5.521-5.961 6.189z"
											fill="#ffffff"
											data-original="#000000"
											opacity="1"
										></path>
									</g>
								</svg>
							</span>
							<p>Earn {rewardsForWatching} PTS For watching</p>
						</div>
						{!hideBurn && (
							<button
								className="gam-ad-earn-burn-skip"
								type="button"
								disabled={points < requiredPointsToBurn}
								onClick={() => skipAd()}
							>
								<span className="gam-eb-doller">
									<svg
										xmlns="http://www.w3.org/2000/svg"
										viewBox="0 0 13 11.92"
										width="14"
										height="13"
									>
										<path
											d="m0,1.39v9.13c0,.37.21.71.53.88.33.17.73.15,1.03-.06l6.76-4.68c.24-.17.38-.44.38-.73,0-.29-.15-.57-.39-.73L1.56.57c-.31-.21-.7-.23-1.03-.06C.2.68,0,1.02,0,1.39Zm10.65-.21v9.56c0,.65.53,1.18,1.18,1.18s1.18-.53,1.18-1.18V1.18c0-.65-.53-1.18-1.18-1.18s-1.18.53-1.18,1.18Z"
											fill="#ffffff"
											data-original="#000000"
										/>
									</svg>
								</span>
								skip ad for {requiredPointsToBurn} PTS
							</button>
						)}
					</div>
				)}

				{groupedPolls && showAdPoll && (
					<AdPoll
						customId="adPoll"
						groupedPolls={groupedPolls}
						currentAd={currentAd}
					/>
				)}

				<Modal
					show={showModal}
					className="gam-watch-completed gam-common-modal dis_center_modal gam-ad-modal"
				>
					<div className="modal-body">
						<button
							type="button"
							className="gam-modal-close"
							data-dismiss="modal"
							aria-label="Close"
							onClick={() => closeModal()}
						>
							<span aria-hidden="true">
								<svg
									width="9"
									height="9"
									viewBox="0 0 9 9"
									fill="none"
									xmlns="http://www.w3.org/2000/svg"
								>
									<path
										fillRule="evenodd"
										clipRule="evenodd"
										d="M1.79246 0.307538C1.38241 -0.102513 0.717588 -0.102513 0.307538 0.307538C-0.102513 0.717588 -0.102513 1.38241 0.307538 1.79246L2.76508 4.25L0.307538 6.70754C-0.102513 7.11759 -0.102513 7.78241 0.307538 8.19246C0.717588 8.60251 1.38241 8.60251 1.79246 8.19246L4.25 5.73492L6.70754 8.19246C7.11759 8.60251 7.78241 8.60251 8.19246 8.19246C8.60251 7.78241 8.60251 7.11759 8.19246 6.70754L5.73492 4.25L8.19246 1.79246C8.60251 1.38241 8.60251 0.717588 8.19246 0.307538C7.78241 -0.102513 7.11759 -0.102513 6.70754 0.307538L4.25 2.76508L1.79246 0.307538Z"
										fill="white"
									/>
								</svg>
							</span>
						</button>
						<div className="gam-tuto-complete-icon">
							<img
								src={`${window.location.origin}/repo/images/gamification/poll_completed.svg`}
								alt="Watch Completed!"
							/>
						</div>
						<h1 className="gam-tutorial-title gam-title mp_0">
							Watch Completed!
						</h1>
						<h2 className="gam-watch-point gam-title mp_0">
							Here's {rewardsForWatching} Points!{' '}
						</h2>
						<p className="gam-modal-des"> Your Total : {points} pts</p>
						<div className="gam-tutorial-btn">
							<button
								type="button"
								onClick={() => closeModal()}
								className="gam_btn mw btn_lr_30 btn"
							>
								Thank you!
							</button>
						</div>
					</div>
				</Modal>
			</>
		)
	);
}
