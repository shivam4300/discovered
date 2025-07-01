import { useEffect, useState } from 'react';

import { Modal, Button } from 'react-bootstrap';
import GroupPolls from './components/profile/groupPolls';
import useGroupedPolls from './components/profile/hooks/useGroupedPolls';
import { useAppSelector, useAppDispatch } from './redux/config/store';
import { setRewards } from './redux/polls';

export default function ProfilePollTriviaApp() {
	const customId = 'preference_poll';
	const { groupedPolls } = useGroupedPolls(customId);
	const dispatch = useAppDispatch();

	const rewards = useAppSelector((state) => state.polls.rewards);

	const [forceHide, setForceHide] = useState(false);
	const handleClose = () => setForceHide(true);

	const handleCloseRewards = () => {
		setForceHide(true);
		dispatch(setRewards(0));
	};

	useEffect(() => {
		if (groupedPolls?.length > 0) {
			setForceHide(false);
		} else if (+rewards > 0) {
			setForceHide(false);
		}
	}, [groupedPolls, setForceHide, rewards]);

	return (
		<>
			<Modal
				show={(groupedPolls?.length > 0 || +rewards > 0) && !forceHide}
				onHide={handleClose}
				className="gam-poll-modal gam-common-modal dis_center_modal gam-profile-pop-up 04"
				aria-labelledby="gam-poll-trivia"
			>
				<div className="gam-pop-content">
					<Modal.Body className="">
						<Button
							className="gam-modal-close"
							aria-label="Close"
							variant="secondary"
							onClick={handleClose}
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
						</Button>
						<div className="gam-group-polls">
							{!rewards ? (
								<GroupPolls customId={customId} />
							) : (
								<div>
									<span className="gam-poll-complete-img">
										<img
											src={`${window.location.origin}/repo/images/gamification/poll_completed.svg`}
											alt="Poll Completed"
										/>
									</span>
									<h3 className="gam-modal-title">Poll Completed!</h3>
									<div className="gam-profile-points">
										<span className="gam-profile-points-icon">
											<img
												src={`${window.location.origin}/repo/images/gamification/star_point.svg`}
												alt="point-icon"
											/>
										</span>
										<span className="gam-profile-points-text">
											Here's {+rewards} Points!
										</span>
									</div>
									<button
										className="gam_btn"
										onClick={() => handleCloseRewards()}
										type="button"
									>
										THANK YOU!
									</button>
								</div>
							)}
						</div>
					</Modal.Body>
				</div>
			</Modal>
		</>
	);
}
