import { useEffect, useState } from 'react';
import { Modal, Button } from 'react-bootstrap';
import useBadgeNotifications from './hooks/useBadgeNotifications';
import getUserId from './utils/getUserId';
import Confetti from 'react-confetti';

export default function BadgesApp() {
	const { notifications: badges, removeNotification } = useBadgeNotifications();

	const [recycle, setRecycle] = useState(true);
	const [forceHide, setForceHide] = useState(false);
	const [showSecondScreen, setShowSecondScreen] = useState(false);

	const handleClose = () => {
		badges && badges?.map((b) => removeNotification(b.itemId));
		setForceHide(true);
	};

	const redirectToCollection = (badgeId) => {
		removeNotification(badgeId);

		setTimeout(() => {
			window.location.href = '/collection?user=' + getUserId();
		}, 500);
	};

	useEffect(() => {
		if (badges?.length) {
			setForceHide(false);
		}
	}, [badges]);

	useEffect(() => {
		const timer = setTimeout(() => setRecycle(false), 1000);

		return () => {
			clearTimeout(timer);
		};
	}, []);

	return (
		<Modal
			show={badges?.length > 0 && !forceHide}
			className="gam-badge-unlock gam-common-modal dis_center_modal text-center"
			aria-labelledby="gam-poll-trivia"
		>
			<div className="gam-pop-content">
				<Modal.Body className="">
					{/* <Button
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
					</Button> */}
					<div className="gam-group-polls">
						{badges &&
							badges?.map((badge) => {
								return (
									<div key={badge.itemId}>
										{showSecondScreen ? (
											<>
												{badge?.data?.Image && (
													<div className="gam-badge-ani-wrap">
														<img src={badge?.data?.Image} alt="badge image" />
													</div>
												)}
												<p className="gam-title">Badge Unlocked!</p>
												<p className="gam-description gam-modal-des">
													{badge?.data?.Description}
												</p>
												<button
													type="button"
													className="gam_btn mw btn_lr_30"
													onClick={() => redirectToCollection(badge.itemId)}
												>
													See Badges Collection
												</button>
											</>
										) : (
											<>
												{badge?.data?.Image && (
													<img src={badge?.data?.Image} alt="badge image" />
												)}
												<p className="gam-title">Badge Unlocked!</p>
												<button
													type="button"
													className="gam_btn mw btn_lr_30"
													onClick={() => setShowSecondScreen(true)}
												>
													See Badge
												</button>
												<div>
													<Confetti
														className="gam-confetti-animation"
														width={600}
														height={448}
														numberOfPieces={200}
														tweenDuration={5000}
														recycle={recycle}
														confettiSource={{ x: 250, y: 100, w: 100, h: 500 }}
														initialVelocityX={5}
														initialVelocityY={10}
													/>
												</div>
											</>
										)}
									</div>
								);
							})}
					</div>
				</Modal.Body>
			</div>
		</Modal>
	);
}
