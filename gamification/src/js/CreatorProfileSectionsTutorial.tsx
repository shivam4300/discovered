import { useEffect, useLayoutEffect, useRef, useState } from 'react';
import useGlobalVariables from './hooks/useGlobalVariables';
import { createPortal } from 'react-dom';
import { Modal, Button } from 'react-bootstrap';
import { useAppDispatch, useAppSelector } from './redux/config/store';
import useProfileTutorial from './hooks/useProfileTutorial';
import TutorialProgressBar from './components/profile/tutorialProgressBar';
import { writePlayerEvent } from './redux/playfab';
import ModalLeaderBoardHelperContent from './components/leaderboard/ModalLeaderBoardHelperContent';
import { USER_DEFAULT_IMAGE } from './Constants';
import { getPlayerStatistics } from './redux/statistics';
import useLeaderboard from './hooks/useLeaderboard';
import useWeeklyChallenges from './hooks/useWeeklyChallenges';

export default function CreatorProfileSectionsTutorial() {
	const [offsetCreatorNumber, setOffsetCreatorNumber] = useState(30);
	const dispatch = useAppDispatch();
	const { CreatorProfileTutorial, PreviousWeekLeaderboard } =
		useGlobalVariables();
	const { setLeaderboardState, toggleTutorialOverlayState, setTutorialState } =
		useProfileTutorial();
	const tutorialOverlay = useAppSelector(
		(state) => state.profileTutorial.tutorialOverlay
	);

	const { weeklyChallenges } = useWeeklyChallenges(true);

	const userStats = useAppSelector((state) => state.statistics);
	const progressionSteps = 3;
	const focusOverlayRef = useRef(null);
	const anchorElementRef = useRef(null);
	const [creatorTutorialStep, setCreatorTutorialStep] = useState(1);
	const [showModalOne, setShowModalOne] = useState(false);
	const [showModalTwo, setShowModalTwo] = useState(false);
	const [showModalThree, setShowModalThree] = useState(false);
	const [showCurrentCreatorStep, setShowCurrentCreatorStep] = useState(0);
	const [showTutorial, setShowCreatorTutorial] = useState(false);
	const { leaderboard } = useLeaderboard('stars', showTutorial);
	const topFourCreators = PreviousWeekLeaderboard.length && Array.isArray(PreviousWeekLeaderboard) ? PreviousWeekLeaderboard.slice(0, 4) : null;

	// Calculate the position of the target div
	const calculateOffsetPosition = (target) => {
		const { width } = target.getBoundingClientRect();
		return width + offsetCreatorNumber;
	};

	function updateNumberBasedOnWindowSize() {
    if (window.innerWidth <= 930) {
      setOffsetCreatorNumber(0);
    } else if(window.innerWidth <= 1100) {
      setOffsetCreatorNumber(15);
    } else {
			setOffsetCreatorNumber(30);
		}
  };

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

		const tutorialCookie = userStats.player_completed_creator_tutorial === 1;

		if (!tutorialCookie && userStats.user_type === 1) {
			const focusOverlay =
				document.querySelector<HTMLElement>('.gam-focus-overlay');

			const anchorElement =
				document.querySelector<HTMLElement>('#gam-leaderboard');
			focusOverlayRef.current = focusOverlay;
			anchorElementRef.current = anchorElement;

			if (anchorElementRef.current) {
				toggleTutorialOverlayState(true);
				// setCreatorTutorialStep(1);
				setShowModalOne(true);
			}
		} else {
			setTutorialState(false);
			toggleTutorialOverlayState(false);
		}

		return () => {
			toggleTutorialOverlayState(false);
			setCreatorTutorialStep(0);
		};
	}, [userStats.player_completed_creator_tutorial]);

	useEffect(() => {
		switch (creatorTutorialStep) {
			case 1:
				setShowCreatorTutorial(true);
				toggleTutorialOverlayState(true);
				setTutorialState(true);
				break;

			case 2:
				setShowCurrentCreatorStep(1);
				setLeaderboardState(true);
				setShowModalOne(false);
				break;

			case 3:
				setShowCurrentCreatorStep(2);
				break;

			case 4:
				setShowCurrentCreatorStep(2);
				setShowModalTwo(true);
				break;

			case 5:
				setShowCurrentCreatorStep(3);
				setShowModalTwo(false);
				setShowModalThree(true);
				break;

			case 6:
				setShowCurrentCreatorStep(0);
				setShowModalTwo(false);
				toggleTutorialOverlayState(false);
				setShowCreatorTutorial(false);
				break;

			default:
				break;
		}

		return () => {};
	}, [creatorTutorialStep]);

	useEffect(() => {
		if (focusOverlayRef.current) {
			tutorialOverlay
				? focusOverlayRef.current.classList.add('gam-show-overlay')
				: focusOverlayRef.current.classList.remove('gam-show-overlay');
		}
	}, [tutorialOverlay, focusOverlayRef]);

	function endTutorial() {
		setShowCreatorTutorial(false);
		setLeaderboardState(false);
		setTutorialState(false);
		setShowCurrentCreatorStep(0);
		setCreatorTutorialStep(0);
		setShowModalOne(false);
		setShowModalTwo(false);
		setShowModalThree(false);
		toggleTutorialOverlayState(false);
		dispatch(writePlayerEvent({ name: 'player_completed_creator_tutorial' }));
	}

	if (userStats.user_type === 0) {
		return null;
	}

	return (
		<>
			<Modal
				show={showModalOne}
				className="gam-common-modal gam-centered-modal gam-tutorial-modal"
				backdropClassName="gam-transparent-overlay"
			>
				<Modal.Body>
					<div className="gam-tutorial-block-img">
						{topFourCreators &&
							topFourCreators.map((creator, i) => {
								return (
									<div
										className="gam-tuto-img-wrapper"
										key={`top-four-creator-${i}`}
									>
										<img
											className="gam-img-white-border"
											src={creator.Profile.AvatarUrl || USER_DEFAULT_IMAGE}
											alt={creator.DisplayName}
										/>
									</div>
								);
							})}
					</div>
					<h3 className='gam-tutorial-title gam-title mp_0'>
						{CreatorProfileTutorial?.stepOneTitle}
					</h3>
					<p className='gam-modal-des'>
						{CreatorProfileTutorial?.stepOneText}
					</p>

					<div className="gam-tutorial-btn">
						<Button
							onClick={() => setCreatorTutorialStep(2)}
							className="gam_btn mw btn_lr_30 "
						>
							<span>Next</span>
							<span className="gam-btn-icon gam-btn-next-icon">
								<img
									src={`${window.location.origin}/repo/images/gamification/next_arrow.svg`}
									alt="next arrow"
								/>
							</span>
						</Button>
					</div>
				</Modal.Body>
			</Modal>

			{anchorElementRef.current &&
				createPortal(
					<>
						<div
							className={`gam-tutorial-container gam-tutorial-box-one gam-left-pos gam-creator-tuto ${
								creatorTutorialStep === 2 || creatorTutorialStep === 3
									? 'gam-custom-fade-in'
									: ''
							}`}
							style={{
								right: calculateOffsetPosition(anchorElementRef.current),
							}}
						>
							{creatorTutorialStep === 2 && (
								<>
									<div className='gam-tutorial-title gam-title mp_0'>
										{CreatorProfileTutorial?.stepTwoTitle}
									</div>
									<div className='gam-tutorial-content gam-modal-des'>
										{CreatorProfileTutorial?.stepTwoText}
									</div>
									<div className="gam-btn-progress-row">
										<div className="gam-tutorial-btn">
											<Button
												onClick={() => setCreatorTutorialStep(3)}
												className="gam_btn mw btn_lr_30"
											>
												<span>Next</span>
												<span className="gam-btn-icon gam-btn-next-icon">
													<img
														src={`${window.location.origin}/repo/images/gamification/next_arrow.svg`}
														alt="next arrow"
													/>
												</span>
											</Button>
										</div>
										<TutorialProgressBar
											step={showCurrentCreatorStep}
											totalSteps={progressionSteps}
										/>
									</div>
								</>
							)}

							{creatorTutorialStep === 3 && (
								<>
									<div className='gam-tutorial-title gam-title mp_0'>
										{CreatorProfileTutorial?.stepThreeTitle}
									</div>
									<div className='gam-tutorial-content gam-modal-des'>
										{CreatorProfileTutorial?.stepThreeText}
									</div>

									<div className="gam-btn-progress-row">
										<div className="gam-tutorial-btn">
											<Button
												onClick={() => setCreatorTutorialStep(4)}
												className="gam_btn mw btn_lr_30"
											>
												<span>Next</span>
												<span className="gam-btn-icon gam-btn-next-icon">
													<img
														src={`${window.location.origin}/repo/images/gamification/next_arrow.svg`}
														alt="next arrow"
													/>
												</span>
											</Button>
										</div>
										<TutorialProgressBar
											step={showCurrentCreatorStep}
											totalSteps={progressionSteps}
										/>
									</div>
								</>
							)}
						</div>
					</>,
					anchorElementRef.current
				)}

			<Modal
				show={showModalTwo}
				// onHide={() => setShowModalTwo(false)}
				className="gam-climb-modal gam-common-modal dis_center_modal gam-profile-pop-up"
				backdropClassName="gam-transparent-overlay"
				aria-labelledby="gam-helper-modal"
			>
				<div className="gam-pop-content gam-helper">
					<Modal.Body className="">
						<ModalLeaderBoardHelperContent />
						<button
							className="gam_btn btn_lr_30 mw"
							type="button"
							onClick={() => setCreatorTutorialStep(5)}
						>
							Got it
						</button>
					</Modal.Body>
				</div>
			</Modal>

			{showModalThree && (
				<Modal
					show={showModalThree}
					className="gam-common-modal gam-centered-modal gam-tutorial-modal"
					backdropClassName="gam-transparent-overlay"
					aria-labelledby="contained-modal-title"
				>
					<Modal.Body className="gam-custom-pos">
						<div className="gam-tutorial-block-img gam-complete">
							<img
								src={`${window.location.origin}/repo/images/gamification/poll_completed.svg`}
								alt="Tutorial Complete"
							/>
						</div>

						<h3 className='gam-tutorial-title gam-title mp_0'>
							{CreatorProfileTutorial?.titleComplete}
						</h3>
						<p className='gam-modal-des'>
							{CreatorProfileTutorial?.titleText}
						</p>

						<div className="gam-tutorial-btn">
							<Button onClick={endTutorial} className="gam_btn mw btn_lr_30">
								{CreatorProfileTutorial?.closeButtonLabel}
							</Button>
						</div>
					</Modal.Body>
				</Modal>
			)}
		</>
	);
}
