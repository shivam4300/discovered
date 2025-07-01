import { useState, useEffect, useRef } from 'react';
import useGlobalVariables from './hooks/useGlobalVariables';
import CountdownTimer from './components/CountdownTimer';
import useLeaderboard from './hooks/useLeaderboard';
import useLeaderboardAroundPlayer from './hooks/useLeaderboardAroundPlayer';
import { useAppSelector } from './redux/config/store';
import ModalLeaderBoardHelper from './components/leaderboard/ModalLeaderBoardHelper';
import useWeeklyChallenges from './hooks/useWeeklyChallenges';
import useProfileTutorial from './hooks/useProfileTutorial';
import getAccountType from './utils/getAccountType';
import { USER_DEFAULT_IMAGE } from './Constants';
import { useAppDispatch } from './redux/config/store';
import { notificationGenerator, addNotification } from './redux/notifications';
import useNotifications from './hooks/useNotifications';

function LeaderBoardModalApp() {
	const dispatch = useAppDispatch();
	const standardAccountType = getAccountType() === 'standard';
	const {
		WeeklyLeaderboardModal,
		UncertifiedAccountContent,
		PreviousWeekLeaderboard,
	} = useGlobalVariables();
	const { setLeaderboardState } = useProfileTutorial();
	const [showLeaderBoard, setShowLeaderBoard] = useState(false);
	const leaderBoardRef = useRef(null);
	const leaderBoardToggleRef = useRef(null);
	const userStats = useAppSelector((state) => state.statistics);
	const { leaderboard } = useLeaderboard('stars', showLeaderBoard);
	const topLeaderboard = leaderboard?.slice(0, 3);
	const remainingLeaderboard = leaderboard?.slice(3);
	const { leaderBoardAroundPlayer } = useLeaderboardAroundPlayer(
		'stars',
		showLeaderBoard
	);
	const leaderboardState = useAppSelector(
		(state) => state.profileTutorial.leaderboard
	);

	const tutorialState = useAppSelector(
		(state) => state.profileTutorial.tutorial
	);
	const { challengesEndDate } = useWeeklyChallenges(true);
	const notification = useNotifications();

	useEffect(() => {
		setLeaderboardState(false);
	}, []);

	useEffect(() => {
		function handleClickOutside(e) {
			if (
				leaderBoardRef.current &&
				!leaderBoardRef.current.contains(e.target) &&
				leaderBoardToggleRef.current &&
				!leaderBoardToggleRef.current.contains(e.target) &&
				!tutorialState
			) {
				setShowLeaderBoard(false);
			}
		}
		document.addEventListener('mousedown', handleClickOutside);

		return () => {
			document.removeEventListener('mousedown', handleClickOutside);
		};
	}, [leaderBoardRef, tutorialState]);

	useEffect(() => {
		if (leaderboardState && tutorialState) {
			setShowLeaderBoard(true);
		} else if (!leaderboardState || !tutorialState) {
			setShowLeaderBoard(false);
		}

		return () => {};
	}, [leaderboardState, tutorialState]);

	// Current leaderboard notif
	useEffect(() => {
		let existInLeaderboard = leaderboard?.filter(
			(l) => l.PlayFabId == leaderBoardAroundPlayer[0].PlayFabId
		);

		console.log('all', existInLeaderboard);

		if (
			existInLeaderboard &&
			leaderboard?.filter(
				(l) => l.PlayFabId == leaderBoardAroundPlayer[0].PlayFabId
			)[0] &&
			JSON.parse(localStorage.getItem('player_received_leaderbord_notif'))
				?.received != 1 &&
			!notification.filter((n) => n.Title == 'Welcome to the top!').length
		) {
			dispatch(
				addNotification(
					notificationGenerator({
						title: 'Welcome to the top!',
						message:
							'You’re now in the top 10 of the weekly leaderboard, but the week’s not over yet. Keep earning new fans, loves, and shares for a chance to become a Featured Creator!',
					})
				)
			);

			localStorage.setItem(
				`player_received_leaderbord_notif`,
				JSON.stringify({ received: 1, until: challengesEndDate })
			);
		}

		if (
			new Date() >
			new Date(
				JSON.parse(
					localStorage.getItem('player_received_leaderbord_notif')
				)?.until
			)
		) {
			localStorage.setItem(
				`player_received_leaderbord_notif`,
				JSON.stringify({ received: 0 })
			);
		}
	}, []);

	// Previous leaderboard notif
	useEffect(() => {
		if (PreviousWeekLeaderboard.length === 0 || !Array.isArray(PreviousWeekLeaderboard)) {
			return;
		}

		if (
			PreviousWeekLeaderboard?.filter(
				(l) => l.PlayFabId == leaderBoardAroundPlayer[0].PlayFabId
			)?.length &&
			JSON.parse(
				localStorage.getItem('player_received_weekafter_leaderbord_notif')
			)?.received != 1 &&
			!notification.filter((n) => n.Title == 'Hello featured creator!').length
		) {
			dispatch(
				addNotification(
					notificationGenerator({
						title: 'Hello featured creator!',
						message:
							"Congrats! The spotlight is yours. For finishing the week in the top 10 of the leaderboard, you're one of our Featured Creators for next week! We’ll contact you with more details.",
					})
				)
			);

			localStorage.setItem(
				`player_received_weekafter_leaderbord_notif`,
				JSON.stringify({ received: 1, until: challengesEndDate })
			);
		}
	}, []);

	return (
		<>
			<div
				className={`gam-leaderboard-notification ${
					showLeaderBoard ? 'open' : ''
				}`}
				data-target="#gam-leaderboard"
			>
				<div aria-expanded={showLeaderBoard}>
					<button
						ref={leaderBoardToggleRef}
						type="button"
						onClick={() => setShowLeaderBoard(!showLeaderBoard)}
						className="dis_header_round leaderboard_btn"
						title="Leaderboard"
						//disabled={tutorialState}
					>
						<img
							src={`${window.location.origin}/repo/images/gamification/crown.svg`}
							alt="Leaderboard"
							title="Leaderboard"
						/>
					</button>
				</div>
				<div
					ref={leaderBoardRef}
					id="gam-creator-focus"
					className={`gam-leaderboard-modal ${showLeaderBoard ? 'open' : ''} ${
						tutorialState ? '' : ''
					}`}
				>
					<div id="gam-leaderboard">
						<div className="gam-leaderboard-modal-content">
							{!standardAccountType ? (
								<>
									<div className="gam-non-certified-content">
										<h5>{UncertifiedAccountContent.uncertifiedAccountText}</h5>
										<a
											href={`${window.location.origin}/settings`}
											className="gam-certify-link"
										>
											{UncertifiedAccountContent.uncertifiedLinkText}
										</a>
									</div>
								</>
							) : (
								<>
									<div className="gam-leaderboard-top-section gam-compact-time">
										<ModalLeaderBoardHelper
											label={WeeklyLeaderboardModal?.topTitle}
											compact={true}
										/>
										<div className="timer-wrapper">
											<CountdownTimer countdownDate={challengesEndDate} />
										</div>
									</div>
									<div className="gam-leaderboard-bottom-section">
										<div className="gam-title-time">
											<div className="gam-title">
												{WeeklyLeaderboardModal?.sideTitle}
											</div>
										</div>
										<div className="gam-leaderboard-listing">
											<div className="gam-top-rank-list">
												{topLeaderboard &&
													topLeaderboard.map((creator, i) => {
														return (
															<div
																className="gam-top-rank-item"
																key={`creator-${i}`}
															>
																<a
																	href={`${window.location.origin}/api/v4/Channel/redirect/${creator.PlayFabId}`}
																	className="gam-rank-details"
																>
																	<div className="gam-trl-box">
																		<span className="gam-trl-img">
																			<img
																				src={
																					creator.Profile?.AvatarUrl ||
																					USER_DEFAULT_IMAGE
																				}
																				alt={creator.Profile?.DisplayName}
																			/>
																			<div className="gam-trl-rank-box">
																				<span className="gam-trl-rank-icon">
																					<img
																						src={`${window.location.origin}/repo/images/gamification/mini_crown.svg`}
																						alt="rank icon"
																					/>
																				</span>
																				<span className="gam-trl-rank">
																					{creator.Position + 1}
																				</span>
																			</div>
																		</span>
																		<p className="gam-trl-point">
																			<span className="gam-trl-point-icon">
																				<img
																					src={`${window.location.origin}/repo/images/gamification/t3_fire.svg`}
																					alt="logo"
																				/>
																			</span>
																			<span className="gam-trl-point-number">
																				{creator.StatValue}
																			</span>
																		</p>
																		<h2 className="gam-trl-name mp_0">
																			{creator.Profile?.DisplayName}
																		</h2>
																	</div>
																</a>
															</div>
														);
													})}
											</div>

											{remainingLeaderboard &&
												remainingLeaderboard.map((creator, i) => {
													return (
														<div className="gam-list-item" key={`creator-${i}`}>
															<div className="gam-rank">
																<span>{creator.Position + 1}</span>
															</div>
															<a
																href={`${window.location.origin}/api/v4/Channel/redirect/${creator.PlayFabId}`}
																className="gam-rank-details"
															>
																<div className="gam-user-info">
																	<div className="gam-user-img">
																		<img
																			src={
																				creator.Profile?.AvatarUrl ||
																				USER_DEFAULT_IMAGE
																			}
																			alt={creator.Profile?.DisplayName}
																		/>
																	</div>
																	<div className="gam-user-name">
																		<span>{creator.Profile?.DisplayName}</span>
																	</div>
																</div>
																<div className="gam-user-points">
																	<span className="gam-lb-points-icon">
																		<img
																			src={`${window.location.origin}/repo/images/gamification/fire.svg`}
																			alt="point-icon"
																		/>
																	</span>
																	{creator.StatValue}
																</div>
															</a>
														</div>
													);
												})}

											{userStats?.user_type === 1 && (
												<div className="gam-list-item gam-user-score">
													<div className="gam-rank">
														<span>
															{leaderBoardAroundPlayer &&
																leaderBoardAroundPlayer[0]?.Position + 1}
														</span>
													</div>
													<a
														href={
															leaderBoardAroundPlayer &&
															`${window.location.origin}/api/v4/Channel/redirect/${leaderBoardAroundPlayer[0].PlayFabId}`
														}
														className="gam-rank-details"
													>
														<div className="gam-user-info">
															<div className="gam-user-img">
																<img
																	src={
																		(leaderBoardAroundPlayer &&
																			leaderBoardAroundPlayer[0]?.Profile
																				?.AvatarUrl) ||
																		USER_DEFAULT_IMAGE
																	}
																	alt={
																		leaderBoardAroundPlayer &&
																		leaderBoardAroundPlayer[0]?.Profile
																			?.DisplayName
																	}
																/>
															</div>
															<div className="gam-user-name">
																<span>You</span>
															</div>
														</div>
														<div className="gam-user-points">
															<span className="gam-lb-points-icon">
																<img
																	src={`${window.location.origin}/repo/images/gamification/fire.svg`}
																	alt="point-icon"
																/>
															</span>
															{leaderBoardAroundPlayer &&
																leaderBoardAroundPlayer[0]?.StatValue}
														</div>
													</a>
												</div>
											)}
										</div>
									</div>
								</>
							)}
						</div>
					</div>
				</div>
			</div>
		</>
	);
}

export default LeaderBoardModalApp;
