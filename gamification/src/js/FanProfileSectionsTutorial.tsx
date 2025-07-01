import { useEffect, useLayoutEffect, useRef, useState } from 'react';
import useGlobalVariables from './hooks/useGlobalVariables';
import { createPortal } from 'react-dom';
import { Modal, Button } from 'react-bootstrap';
import { useAppDispatch, useAppSelector } from './redux/config/store';
import useProfileTutorial from './hooks/useProfileTutorial';
import TutorialProgressBar from './components/profile/tutorialProgressBar';
import useWeeklyChallenges from './hooks/useWeeklyChallenges';
import useLeaderboard from './hooks/useLeaderboard';
import { writePlayerEvent } from './redux/playfab';
import { USER_DEFAULT_IMAGE } from './Constants';
import { getPlayerStatistics } from './redux/statistics';
import ProfileImgIconLite from './components/ProfileImgIconLite';

export default function FanProfileSectionsTutorial() {
	const [offsetFanNumber, setOffsetFanNumber] = useState(15);
	const dispatch = useAppDispatch();
	const { FanProfileTutorial, PreviousWeekLeaderboard } = useGlobalVariables();
	const { setChallengeState, toggleTutorialOverlayState } =
		useProfileTutorial();
	const tutorialOverlay = useAppSelector(
		(state) => state.profileTutorial.tutorialOverlay
	);

	const userStats = useAppSelector((state) => state.statistics);
	const creatorState = useAppSelector((state) => state.profileTutorial.creator);
	const { weeklyChallenges } = useWeeklyChallenges();
	const [showTutorial, setShowTutorial] = useState(false);
	const [fanTutorialStep, setFanTutorialStep] = useState(1);
	const [showModalOne, setShowModalOne] = useState(false);
	const [showModalTwo, setShowModalTwo] = useState(false);
	const [showCurrentFanStep, setShowCurrentFanStep] = useState(0);
	const { leaderboard } = useLeaderboard('stars', showTutorial);
	const topFourCreators = PreviousWeekLeaderboard.length && Array.isArray(PreviousWeekLeaderboard) ? PreviousWeekLeaderboard.slice(0, 4) : null;

	// console.log('weeklyChallenges', weeklyChallenges);

	const progressionSteps = 5;
	const focusOverlayRef = useRef(null);
	const focusDivRef = useRef(null);
	const focusElOneRef = useRef(null);
	const focusElTwoRef = useRef(null);
	const focusElThreeRef = useRef(null);
	const focusElFourRef = useRef(null);

	const weeklyChallengesLength = weeklyChallenges.length;

	// Calculate the position of the target div
	const calculateOffsetPosition = (target) => {
		const { width } = target.getBoundingClientRect();
		return width + offsetFanNumber;
	};

	function updateNumberBasedOnWindowSize() {
		if (window.innerWidth <= 930) {
			setOffsetFanNumber(6);
		} else {
			setOffsetFanNumber(15);
		}
	}

	function getDynamicChallengeElement() {
		// return document.querySelector<HTMLElement>('#gam-challenge-el-4');
		return document.querySelector<HTMLElement>('.gam-wc-listing-wrap');
	}

	useEffect(() => {
		dispatch(getPlayerStatistics());
		window.addEventListener('resize', updateNumberBasedOnWindowSize);
		updateNumberBasedOnWindowSize();

		return () => {
			window.removeEventListener('resize', updateNumberBasedOnWindowSize);
		};
	}, []);

	useLayoutEffect(() => {
		dispatch(getPlayerStatistics());
		const tutorialCookie = userStats.player_completed_fan_tutorial === 1;

		if (
			weeklyChallengesLength > 0 &&
			!tutorialCookie &&
			userStats.user_type === 0
		) {
			const focusOverlay =
				document.querySelector<HTMLElement>('.gam-focus-overlay');
			const divFocusIndex =
				document.querySelector<HTMLElement>('#gam-focus-index');
			const focusElOne = document.querySelector<HTMLElement>('#gam-focus-el-1');
			const focusElTwo = document.querySelector<HTMLElement>('#gam-focus-el-2');
			const focusElThree =
				document.querySelector<HTMLElement>('#gam-focus-el-3');
			const focusElFour =
				document.querySelector<HTMLElement>('#gam-focus-el-4');

			focusOverlayRef.current = focusOverlay;
			focusDivRef.current = divFocusIndex;
			focusElOneRef.current = focusElOne;
			focusElTwoRef.current = focusElTwo;
			focusElThreeRef.current = focusElThree;
			focusElFourRef.current = focusElFour;

			if (focusDivRef.current) {
				toggleTutorialOverlayState(true);
				setShowModalOne(true);
				setShowTutorial(true);
			}
		}

		return () => {};
	}, [userStats.player_completed_fan_tutorial, weeklyChallengesLength]);

	useEffect(() => {
		switch (fanTutorialStep) {
			case 1:
				toggleTutorialOverlayState(true);
				break;

			case 2:
				break;

			case 3:
				setShowModalOne(false);
				setShowCurrentFanStep(1);
				focusDivRef.current.classList.add('gam-show-focus');
				if (window.matchMedia('(max-width: 1024px)').matches) {
					const targetDivTop = focusDivRef.current.getBoundingClientRect().top;
					const scrollPosition = targetDivTop + window.scrollY - 100;
					window.scrollTo({
						top: scrollPosition,
						behavior: 'smooth',
					});
				}
				break;

			case 4:
				setShowCurrentFanStep(2);
				focusDivRef.current.classList.remove('gam-show-focus');
				document
					.querySelector<HTMLElement>('.gam-focus-element#gam-focus-el-2')
					.classList.add('gam-show-focus');

				if (window.matchMedia('(max-width: 991px)').matches) {
					const targetTwo = document.querySelector<HTMLElement>(
						'.gam-focus-element#gam-focus-el-2'
					);
					const targetDivTop = targetTwo.getBoundingClientRect().top;
					const scrollPosition = targetDivTop + window.scrollY - 350;
					window.scrollTo({
						top: scrollPosition,
						behavior: 'smooth',
					});
				}
				break;

			case 5:
				setShowCurrentFanStep(3);
				document
					.querySelector<HTMLElement>('.gam-focus-element#gam-focus-el-2')
					.classList.remove('gam-show-focus');
				document
					.querySelector<HTMLElement>('.gam-focus-element#gam-focus-el-3')
					.classList.add('gam-show-focus');

				if (window.matchMedia('(max-width: 1200px)').matches) {
					const targetThree = document.querySelector<HTMLElement>(
						'.gam-focus-element#gam-focus-el-3'
					);
					const targetDivTop = targetThree.getBoundingClientRect().top;
					const scrollPosition = targetDivTop + window.scrollY - 50;
					window.scrollTo({
						top: scrollPosition,
						behavior: 'smooth',
					});
				}

				if (window.matchMedia('(max-width: 991px)').matches) {
					const targetThree = document.querySelector<HTMLElement>(
						'.gam-focus-element#gam-focus-el-3'
					);
					const targetDivTop = targetThree.getBoundingClientRect().top;
					const scrollPosition = targetDivTop + window.scrollY - 500;
					window.scrollTo({
						top: scrollPosition,
						behavior: 'smooth',
					});
				}
				break;

			case 6:
				setShowCurrentFanStep(4);
				document
					.querySelector<HTMLElement>('.gam-focus-element#gam-focus-el-3')
					.classList.remove('gam-show-focus');
				document
					.querySelector<HTMLElement>('#gam-focus-el-4')
					.classList.add('gam-show-focus');
				document
					.querySelector<HTMLElement>('#gam-challenge-el-0')
					.classList.add('gam-show-focus');
				getDynamicChallengeElement().classList.add('gam-show-focus');
				break;

			case 7:
				setShowCurrentFanStep(5);
				setChallengeState(true);
				break;

			case 8:
				document
					.querySelector<HTMLElement>('#gam-challenge-el-0')
					.classList.remove('gam-show-focus');
				getDynamicChallengeElement().classList.remove('gam-show-focus');
				setShowCurrentFanStep(0);
				setChallengeState(false);
				setShowModalTwo(true);
				setShowTutorial(false);
				break;

			default:
				break;
		}

		return () => {};
	}, [fanTutorialStep]);

	useEffect(() => {
		if (focusOverlayRef.current) {
			tutorialOverlay
				? focusOverlayRef.current.classList.add('gam-show-overlay')
				: focusOverlayRef.current.classList.remove('gam-show-overlay');
		}
	}, [tutorialOverlay, focusOverlayRef]);

	function endTutorial() {
		setShowCurrentFanStep(0);
		setFanTutorialStep(0);
		setShowModalOne(false);
		setShowModalTwo(false);
		toggleTutorialOverlayState(false);
		dispatch(writePlayerEvent({ name: 'player_completed_fan_tutorial' }));
	}

	if (userStats.user_type === 1) {
		return null;
	}

	return (
		<>
			<Modal
				show={showModalOne}
				className='gam-common-modal gam-centered-modal gam-tutorial-modal'
				backdropClassName='gam-transparent-overlay'
				aria-labelledby='contained-modal-title'
			>
				<Modal.Body>
					<div className='gam-tuto-modal-content'>
						{fanTutorialStep === 1 && (
							<>
								<div className='tuto-1'>
									<div className='gam-tutorial-block-img'>
										{topFourCreators &&
											topFourCreators.map((creator, i) => {
												return (
													<div
														className='gam-tuto-img-wrapper'
														key={`top-four-creator-${i}`}
													>
														<img
															className='gam-img-white-border'
															src={
																creator.Profile.AvatarUrl || USER_DEFAULT_IMAGE
															}
															alt={creator.DisplayName}
														/>
													</div>
												);
											})}
									</div>
									<h3 className='gam-tutorial-title gam-title mp_0'>
										{FanProfileTutorial?.stepOneTitle}
									</h3>
									<p className='gam-modal-des'>
										{FanProfileTutorial?.stepOneText}
									</p>
									<div className='gam-tutorial-btn'>
										<Button
											onClick={() => setFanTutorialStep(2)}
											className='gam_btn mw btn_lr_30'
										>
											Let's do this
										</Button>
									</div>
								</div>
							</>
						)}

						{fanTutorialStep === 2 && (
							<>
								<div className='tuto-2'>
									<h3 className='gam-tutorial-title gam-title mp_0'>
										{FanProfileTutorial?.stepTwoTitle}
									</h3>
									<p className='gam-modal-des'>
										{FanProfileTutorial?.stepTwoText}
									</p>
									<ul className='gam-tutorial-creators'>
										{weeklyChallenges &&
											weeklyChallenges.map((user, i) => {
												return (
													<li
														className='gam-tuto-img-wrapper'
														key={`top-creator-${i}`}
													>
														<ProfileImgIconLite
															creatorChannel={user?.data?.channel}
															lrg={true}
														/>
														{/* <img
															src={user.Profile.AvatarUrl || USER_DEFAULT_IMAGE}
															alt={user.DisplayName}
														/> */}
													</li>
												);
											})}
									</ul>
									<div className='gam-tutorial-btn'>
										<Button
											onClick={() => setFanTutorialStep(3)}
											className='gam_btn mw btn_lr_30'
										>
											Start Playing
										</Button>
									</div>
								</div>
							</>
						)}
					</div>
				</Modal.Body>
			</Modal>

			{focusElOneRef.current &&
				createPortal(
					<>
						<div
							className={`gam-tutorial-container gam-fan-tuto gam-tutorial-box-one ${
								fanTutorialStep === 3 ? 'gam-custom-fade-in' : ''
							}`}
							style={{
								left: calculateOffsetPosition(focusElOneRef.current),
							}}
						>
							<div className='gam-tutorial-title gam-title mp_0'>
								{FanProfileTutorial?.stepThreeTitle}
							</div>
							<div className='gam-tutorial-content gam-modal-des'>
								{FanProfileTutorial?.stepThreeText}
							</div>

							<div className='gam-btn-progress-row'>
								<div className='gam-tutorial-btn'>
									<Button
										onClick={() => setFanTutorialStep(4)}
										className='gam_btn mw btn_lr_30'
									>
										<span>Next</span>
										<span className='gam-btn-icon gam-btn-next-icon'>
											<img
												src={`${window.location.origin}/repo/images/gamification/next_arrow.svg`}
												alt='next arrow'
											/>
										</span>
									</Button>
								</div>
								<TutorialProgressBar
									step={showCurrentFanStep}
									totalSteps={progressionSteps}
								/>
							</div>
						</div>
					</>,
					focusElOneRef.current
				)}

			{focusElTwoRef.current &&
				createPortal(
					<>
						<div
							className={`gam-tutorial-container gam-fan-tuto gam-tutorial-box-two ${
								fanTutorialStep === 4 ? 'gam-custom-fade-in' : ''
							}`}
						>
							<div className='gam-tutorial-title gam-title mp_0'>
								{FanProfileTutorial?.stepFourTitle}
							</div>
							<div className='gam-tutorial-content gam-modal-des'>
								{FanProfileTutorial?.stepFourText}
							</div>

							<div className='gam-btn-progress-row'>
								<div className='gam-tutorial-btn'>
									<Button
										onClick={() => setFanTutorialStep(5)}
										className='gam_btn mw btn_lr_30'
									>
										<span>Next</span>
										<span className='gam-btn-icon gam-btn-next-icon'>
											<img
												src={`${window.location.origin}/repo/images/gamification/next_arrow.svg`}
												alt='next arrow'
											/>
										</span>
									</Button>
								</div>
								<TutorialProgressBar
									step={showCurrentFanStep}
									totalSteps={progressionSteps}
								/>
							</div>
						</div>
					</>,
					focusElTwoRef.current
				)}

			{focusElThreeRef.current &&
				createPortal(
					<>
						<div
							className={`gam-tutorial-container gam-fan-tuto gam-tutorial-box-three gam-left-pos ${
								fanTutorialStep === 5 ? 'gam-custom-fade-in' : ''
							}`}
							style={{
								right: calculateOffsetPosition(focusElThreeRef.current),
							}}
						>
							<div className='gam-tutorial-title gam-title mp_0'>
								{FanProfileTutorial?.stepFiveTitle}
							</div>
							<div className='gam-tutorial-content gam-modal-des'>
								{FanProfileTutorial?.stepFiveText}
							</div>

							<div className='gam-btn-progress-row'>
								<div className='gam-tutorial-btn'>
									<Button
										onClick={() => setFanTutorialStep(6)}
										className='gam_btn mw btn_lr_30'
									>
										<span>Next</span>
										<span className='gam-btn-icon gam-btn-next-icon'>
											<img
												src={`${window.location.origin}/repo/images/gamification/next_arrow.svg`}
												alt='next arrow'
											/>
										</span>
									</Button>
								</div>
								<TutorialProgressBar
									step={showCurrentFanStep}
									totalSteps={progressionSteps}
								/>
							</div>
						</div>
					</>,
					focusElThreeRef.current
				)}

			{getDynamicChallengeElement() &&
				weeklyChallengesLength > 0 &&
				createPortal(
					<>
						<div
							className={`gam-tutorial-container gam-fan-tuto gam-tutorial-box-four gam-left-pos gam-pad-top ${
								fanTutorialStep === 6 || fanTutorialStep === 7
									? 'gam-custom-fade-in'
									: ''
							}`}
							style={{
								right: calculateOffsetPosition(getDynamicChallengeElement()),
							}}
						>
							<div className='gam-tutorial-title gam-title mp_0'>
								{FanProfileTutorial?.stepSixTitle}
							</div>

							{fanTutorialStep === 6 && (
								<>
									<div className='gam-tutorial-content gam-modal-des'>
										{FanProfileTutorial?.stepSixText}
									</div>

									<div className='gam-btn-progress-row'>
										<div className='gam-tutorial-btn'>
											<Button
												onClick={() => setFanTutorialStep(7)}
												className='gam_btn mw btn_lr_30'
											>
												<span>Next</span>
												<span className='gam-btn-icon gam-btn-next-icon'>
													<img
														src={`${window.location.origin}/repo/images/gamification/next_arrow.svg`}
														alt='next arrow'
													/>
												</span>
											</Button>
										</div>
										<TutorialProgressBar
											step={showCurrentFanStep}
											totalSteps={progressionSteps}
										/>
									</div>
								</>
							)}

							{fanTutorialStep === 7 && (
								<>
									<div className='gam-tutorial-content gam-modal-des'>
										{FanProfileTutorial?.stepSevenTitle}
									</div>

									<div className='gam-btn-progress-row'>
										<div className='gam-tutorial-btn'>
											<Button
												onClick={() => setFanTutorialStep(8)}
												className='gam_btn mw btn_lr_30'
											>
												<span>Next</span>
												<span className='gam-btn-icon gam-btn-next-icon'>
													<img
														src={`${window.location.origin}/repo/images/gamification/next_arrow.svg`}
														alt='next arrow'
													/>
												</span>
											</Button>
										</div>
										<TutorialProgressBar
											step={showCurrentFanStep}
											totalSteps={progressionSteps}
										/>
									</div>
								</>
							)}
						</div>
					</>,
					getDynamicChallengeElement()
				)}

			{showModalTwo && (
				<Modal
					show={showModalTwo}
					className='gam-common-modal gam-centered-modal gam-tutorial-modal gam-tut-all-set'
					backdropClassName='gam-transparent-overlay'
					aria-labelledby='contained-modal-title'
				>
					<Modal.Body className='gam-custom-pos'>
						<div className='gam-tuto-modal-content'>
							<div className='gam-tuto-complete-icon'>
								<img
									src={`${window.location.origin}/repo/images/gamification/poll_completed.svg`}
									alt='Tutorial Complete'
								/>
							</div>
							<h3 className='gam-tutorial-title gam-title mp_0'>
								{FanProfileTutorial?.titleComplete}
							</h3>
							<p className='gam-modal-des'>{FanProfileTutorial?.titleText}</p>

							<div className='gam-tutorial-btn'>
								<Button onClick={endTutorial} className='gam_btn mw btn_lr_30'>
									{FanProfileTutorial?.closeButtonLabel}
								</Button>
							</div>
						</div>
					</Modal.Body>
				</Modal>
			)}
		</>
	);
}
