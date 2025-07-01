import { useState, useEffect } from 'react';
import { MISSIONS_TYPES } from './Constants';
import useMissions from './hooks/useMissions';
import usePins from './hooks/usePins';
import useBadges from './hooks/useBadges';
import PinIcon from './components/collection/PinIcon';
import BadgeIcon from './components/collection/BadgeIcon';
import { useAppSelector, useAppDispatch } from './redux/config/store';
import { Modal, Button } from 'react-bootstrap';
import useGlobalVariables from './hooks/useGlobalVariables';
import { getPlayerStatistics } from './redux/statistics';
import getAccountType from './utils/getAccountType';
import FeaturedCreatorItem from './components/collection/FeaturedCreatorItem';

function CollectionApp() {
	const dispatch = useAppDispatch();
	const [showPins, setShowPins] = useState(true);
	const [showBadges, setShowBadges] = useState(false);
	const missions:IXRMissionItem[] = useMissions(
		MISSIONS_TYPES.WEEKLY_DISCOVERY_CHALLENGE
	).filter((m) => m.PlayerStatus);
	const pins = usePins();
	const allBadges = useBadges();
	const userStats = useAppSelector((state) => state.statistics);
	const [badgeModalContent, setBadgeModalContent] = useState([]);
	const [showBadgesModal, setShowBadgesModal] = useState(false);
	const {
		isLoaded,
		CreatorPinsSection,
		BadgesSection,
		UncertifiedAccountContent,
	} = useGlobalVariables();
	const standardAccountType = getAccountType() === 'standard';

	function handleShowPins() {
		setShowPins(true);
		setShowBadges(false);
	}

	function handleShowBadges() {
		setShowPins(false);
		setShowBadges(true);
	}

	const badgesCollections = allBadges.reduce((list, badge) => {
		const singleCollectionKey = badge.data.StatName;

		if (singleCollectionKey === null) {
			return list;
		}

		if (!list[singleCollectionKey]) {
			list[singleCollectionKey] = [];
		}

		list[singleCollectionKey].push(badge);

		return list;
	}, {});

	const badgesCollectionsNames = Object.keys(badgesCollections);


	const oppositeUserType = userStats.user_type === 1 ? 'fan' : 'creator';

	// Array of badges
	const badgeGroups = allBadges.reduce((list, badge) => {
		if (!list[badge.data.StatName]) {
			list[badge.data.StatName] = [badge];
		} else {
			list[badge.data.StatName].push(badge);
		}

		list[badge.data.StatName].sort(
			(a, b) => a.data.Threshold - b.data.Threshold
		);

		return list;
	}, {});

	const allBadgeGroups = badgesCollectionsNames.map((badge) => {
		return badgeGroups[badge];
	});

	const completedBadgeGroups = allBadgeGroups.flatMap((badgeGroup) =>
		badgeGroup.filter((badge) => badge.isInInventory)
	);

	// Filter other badges by tags (fan/creator)
	const completedBadgesByType = completedBadgeGroups.filter(
		(badge) => !badge.playfab.Tags.includes(oppositeUserType)
	);

	const badgesInProgressGroups = allBadgeGroups
		.map((badgeGroup) =>
			badgeGroup.find((badge) => badge.isInInventory === false)
		)
		.filter((badge) => badge !== undefined);

	// Filter other badges by tags (fan/creator)
	const badgesInProgressByType = badgesInProgressGroups.filter(
		(badge) => !badge.playfab.Tags.includes(oppositeUserType)
	);


	// Get total badges

	// Badges modal
	function handleShowBadgesModal(badgeGroupArray, completed = false) {
		setBadgeModalContent(badgeGroupArray);
		setShowBadgesModal(true);
	}

	// Date Converter
	function convertDateFormat(timestamp) {
		const date = new Date(timestamp);

		// Get day, month, and year
		const day = date.getDate();
		const month = date.getMonth() + 1; // Months are zero-based
		const year = date.getFullYear();

		// Pad single digits with leading zero if necessary
		const formattedDay = day < 10 ? `0${day}` : day;
		const formattedMonth = month < 10 ? `0${month}` : month;

		// Return formatted date string
		return `${formattedDay}/${formattedMonth}/${year}`;
	}

	useEffect(() => {
		dispatch(getPlayerStatistics());

		return () => {
			setShowPins(true);
			setShowBadges(false);
		};
	}, [dispatch]);

	return (
		<>
			<div className="tab-content">
				<div
					role="tabpanel"
					className="tab-pane active gam-collection"
					id="collection"
				>
					<div className="user_tab_wrapper">
						<div className="artist_profile_collection dis_profile_data">
							<div className="row">
								<div className="col-lg-12 col-md-12 gam-collection-content">
									<div className="user_post_area_main">
										{!standardAccountType ? (
											<>
												<div className="gam-non-certified">
													<h4>
														{UncertifiedAccountContent?.uncertifiedAccountText}
													</h4>
													<a
														href={`${window.location.origin}/settings`}
														className="gam-certify-link"
													>
														{UncertifiedAccountContent?.uncertifiedLinkText}
													</a>
												</div>
											</>
										) : (
											<>
												<div className="user-collection-area">
													<div className="gam-area-header">
														<ul className="gam-tab-buttons" role="tablist">
															<li
																className={`initial_step keep-shown ${
																	showPins ? 'active' : ''
																}`}
																title="Creator Pins"
															>
																<button
																	className="gam-pin-btn"
																	type="button"
																	onClick={handleShowPins}
																>
																	<svg
																		id="Layer_2"
																		xmlns="http://www.w3.org/2000/svg"
																		viewBox="0 0 11.38 11.36"
																	>
																		<g id="Layer_1-2">
																			<path d="m11.09,5.1c-.18.24-.43.4-.72.46-.29.06-.59,0-.84-.15-.14-.09-.14-.09-.29.07-.75.76-.95.96-1.57,1.57l-.28.28s-.05.04-.02.09c.79,1.19.29,2.53-.45,3.38-.37.4-.76.41-1.16.05-.68-.69-1.19-1.18-1.93-1.94-.07-.06-.04-.07-.14,0-.58.48-1.17.96-1.76,1.43-.35.29-.71.57-1.06.86-.11.11-.25.17-.4.18-.06,0-.12-.01-.17-.04-.05-.02-.1-.06-.14-.1-.28-.28-.07-.57.02-.69.73-1,1.44-2,2.19-3,.05-.08.05-.06-.02-.13-.67-.67-1.14-1.14-1.75-1.74-.43-.42-.4-.85.05-1.25.86-.75,2.33-1.14,3.35-.39,0,0,.01.01.02.01.68-.68,1.35-1.36,2.04-2.04-.19-.26-.27-.57-.23-.88.04-.31.19-.6.43-.81C6.48.1,6.79,0,7.11,0c.32.01.62.14.84.36,1.27,1.26,1.82,1.81,3.07,3.07.22.22.34.51.35.82.01.31-.09.61-.29.85Z" />
																		</g>
																	</svg>
																	<span>Creator Pins</span>
																</button>
															</li>
															<li
																className={`${showBadges ? 'active' : ''}`}
																title="Badges"
															>
																<button
																	className="gam-badge-btn"
																	type="button"
																	onClick={handleShowBadges}
																>
																	<svg
																		id="Layer_2"
																		xmlns="http://www.w3.org/2000/svg"
																		viewBox="0 0 14.06 14.06"
																	>
																		<g id="Layer_1-2">
																			<path d="m7.03,0c-1.39,0-2.75.41-3.91,1.18-1.16.77-2.06,1.87-2.59,3.16C0,5.63-.14,7.04.14,8.4c.27,1.36.94,2.62,1.92,3.6.98.98,2.24,1.65,3.6,1.92,1.36.27,2.78.13,4.06-.4,1.28-.53,2.38-1.43,3.16-2.59.77-1.16,1.18-2.52,1.18-3.91,0-1.86-.74-3.65-2.06-4.97S8.9,0,7.03,0Zm3.89,5.84l-1.64,1.31c-.07.05-.12.13-.14.21-.03.08-.03.17,0,.25l.75,2.5c.03.09.02.18,0,.27-.03.09-.09.16-.16.21-.07.05-.16.08-.26.08-.09,0-.18-.03-.25-.09l-1.92-1.44c-.07-.06-.17-.09-.26-.09s-.18.03-.26.09l-1.92,1.44c-.07.05-.16.09-.25.09-.09,0-.18-.03-.26-.08-.07-.05-.13-.13-.16-.21-.03-.09-.03-.18,0-.27l.75-2.5c.02-.08.02-.17,0-.25-.03-.08-.08-.16-.14-.21l-1.64-1.31c-.07-.06-.12-.13-.15-.22-.02-.09-.02-.18,0-.26.03-.08.09-.16.16-.21.07-.05.16-.08.25-.08h1.87c.08,0,.17-.02.24-.07.07-.05.13-.11.16-.19l.94-2.2c.03-.08.09-.14.16-.19.07-.05.15-.07.24-.07s.17.02.24.07c.07.05.13.11.16.19l.94,2.2c.03.08.09.14.16.19.07.05.15.07.24.07h1.88c.09,0,.18.03.25.08.07.05.13.13.16.21.03.08.03.18,0,.26s-.08.16-.15.22h0Z" />
																		</g>
																	</svg>
																	<span>Badges</span>
																</button>
															</li>
														</ul>
													</div>
												</div>

												{showPins && (
													<div className="creator_tab_content">
														<div id="creator-pins">
															<div
																id="gam-focus-el-2"
																className="gam-listing-content gam-focus-element gam-el-2"
															>
																<div className="gam-listing-header">
																	<h5 className="gam-listing-title">
																		{CreatorPinsSection?.featuredCreatorsTitle}
																	</h5>
																</div>

																<div className="gam-listing-wrapper">
																	<ul className="gam-pin-listing gam-featured-list">
																		{missions &&
																			isLoaded &&
																			missions?.length > 0 &&
																			missions.map((mission, i) => <FeaturedCreatorItem mission={mission} key={mission.itemId} />)}
																	</ul>
																</div>
															</div>

															<div className="gam-listing-content">
																<div className="gam-listing-header">
																	<h5 className="gam-listing-title">
																		{CreatorPinsSection?.creatorPinTitle}
																	</h5>
																</div>

																<div className="gam-listing-wrapper">
																	<ul className="gam-pin-listing gam-collector-list">
																		{pins &&
																			pins.map((pin, i) => {
																				return (
																					<li
																						className="gam-collector-item"
																						key={i}
																					>
																						<PinIcon pin={pin} />
																					</li>
																				);
																			})}
																	</ul>
																</div>
															</div>
														</div>
													</div>
												)}

												{showBadges && (
													<div className="badges_tab_content">
														<div id="badges-pins">
															<div className="gam-listing-content">
																<div className="gam-listing-header">
																	<h5 className="gam-listing-title">
																		{BadgesSection?.badgesInProgressTitle}
																	</h5>
																</div>

																<div className="gam-listing-wrapper">
																	<ul className="gam-badges-listing gam-featured-list">
																		{badgesInProgressByType &&
																			badgesInProgressByType.map(
																				(badgeGroup, i) => {
																					// const totalNum = findNextBadge(
																					// 	badgeGroup.data.StatName
																					// );
																					const badgesDone =
																						completedBadgeGroups.filter(
																							(completedBadgeGroup) =>
																								completedBadgeGroup.isInInventory &&
																								completedBadgeGroup.data
																									.StatName ===
																									badgeGroup.data.StatName
																						);

																					return (
																						<li
																							className="gam-featured-item gam-badge-in-progress gam-bg-badge-box"
																							key={i}
																						>
																							<button
																								className="gam-badge-in-progress-btn"
																								type="button"
																								onClick={() =>
																									handleShowBadgesModal(
																										[
																											badgeGroup,
																											badgesDone.reverse(),
																											{
																												currentScore:
																													userStats[
																														badgeGroup.data
																															.StatName
																													],
																											},
																											{
																												total:
																													badgeGroup.data
																														.Threshold,
																											},
																										],
																										true
																									)
																								}
																							>
																								<BadgeIcon
																									image={badgeGroup.data.Image}
																									badge={badgeGroup}
																									score={Math.min(
																										userStats[
																											badgeGroup.data.StatName
																										],
																										badgeGroup.data.Threshold
																									)}
																									total={
																										badgeGroup.data.Threshold
																									}
																								/>
																							</button>
																						</li>
																					);
																				}
																			)}
																	</ul>
																</div>
															</div>

															<div className="gam-listing-content">
																<div className="gam-listing-header">
																	<h5 className="gam-listing-title">
																		{BadgesSection?.completeBadgesTitle}
																	</h5>
																</div>
																<div className="gam-listing-wrapper">
																	<ul className="gam-badges-listing gam-collector-list">
																		{completedBadgesByType &&
																			completedBadgesByType.map((badge, i) => {
																				const badgeCategoryDone =
																					completedBadgesByType.filter(
																						(completedBadgeGroup) =>
																							completedBadgeGroup.isInInventory &&
																							completedBadgeGroup.data
																								.StatName ===
																								badge.data.StatName
																					);
																				return (
																					<li
																						className="gam-collector-item gam-bg-badge-box"
																						key={i}
																					>
																						<button
																							className="gam-badge-in-progress-btn"
																							type="button"
																							onClick={() =>
																								handleShowBadgesModal([
																									null,
																									badgeCategoryDone.reverse(),
																									{
																										currentScore:
																											userStats[
																												badge.data.StatName
																											],
																									},
																									{
																										total: badge.data.Threshold,
																									},
																								])
																							}
																						>
																							<BadgeIcon
																								image={badge.data.Image}
																								badge={badge}
																								completed={true}
																							/>
																						</button>
																					</li>
																				);
																			})}
																	</ul>
																</div>
															</div>
														</div>
													</div>
												)}
											</>
										)}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<Modal
				show={showBadgesModal}
				onHide={() => setShowBadgesModal(false)}
				className="gam-video-watcher gam-common-modal dis_center_modal gam-profile-pop-up"
				aria-labelledby="gam-poll-trivia"
			>
				<div className="gam-pop-content">
					<Modal.Body className="">
						<Button
							className="gam-modal-close"
							aria-label="Close"
							variant="secondary"
							onClick={() => setShowBadgesModal(false)}
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
									></path>
								</svg>
							</span>
						</Button>
						{badgeModalContent && (
							<div className="gam-pop-content-inner">
								{badgeModalContent[0] ? (
									<>
										<h3 className="gam-badge-modal-title gam-modal-title">
											{badgeModalContent[0]?.playfab?.DisplayName}
										</h3>
										<p className="gam-badge-modal-description gam-modal-des">
											{badgeModalContent[0]?.data?.Description}
										</p>
									</>
								) : (
									<>
										<h3 className="gam-badge-modal-title gam-modal-title">
											{BadgesSection?.completeBadgesTitle}
										</h3>
										<p className="gam-badge-modal-description">
											{BadgesSection?.completeBadgesDescription}
										</p>
									</>
								)}
								<div className="gam-badge-modal-content">
									{badgeModalContent[0] && (
										<div className="gam-modal-single-badge gam-badge-modal-current">
											<div className="gam-wc-img gam-badge-img">
												<img
													src={badgeModalContent[0]?.data?.Image}
													alt={badgeModalContent[0]?.playfab?.DisplayName}
												/>
											</div>
											<div className="gam-badge-stats">
												<div className="gam-badge-stats-text">
													<span className="gam-badge-score">
														{badgeModalContent[2]?.currentScore}
													</span>
													/
													<span className="gam-badge-total">
														{badgeModalContent[3]?.total}
													</span>
												</div>
												<div className="gam-badge-text">Next Badge</div>
											</div>
										</div>
									)}
									{badgeModalContent[1]?.length > 0 &&
										badgeModalContent[1].map((badge, i) => {
											return (
												<div
													className="gam-modal-single-badge gam-badge-modal-previous"
													key={i}
												>
													<div className="gam-wc-img gam-badge-img">
														<img
															src={badge?.data?.Image}
															alt={badge.playfab.DisplayName}
														/>
													</div>
													<div className="gam-badge-stats">
														<div className="gam-badge-stats-text">
															<span className="gam-badge-score">
																{badge.data.Threshold}
															</span>
															/
															<span className="gam-badge-total">
																{badge.data.Threshold}
															</span>
														</div>
														<div className="gam-badge-text">
															{convertDateFormat(badge?.playfab?.PurchaseDate)}
														</div>
													</div>
													<div className="gam-badge-cb">
														<img
															src={`${window.location.origin}/repo/images/gamification/checkbox.svg`}
															alt="check-icon"
														/>
													</div>
												</div>
											);
										})}
								</div>
							</div>
						)}
					</Modal.Body>
				</div>
			</Modal>
		</>
	);
}

export default CollectionApp;
