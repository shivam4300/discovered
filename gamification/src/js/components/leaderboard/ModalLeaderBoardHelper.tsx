import { useState } from 'react';
import { Modal, Button } from 'react-bootstrap';
import ModalLeaderBoardHelperContent from './ModalLeaderBoardHelperContent';

function ModalLeaderBoardHelper({ label, compact = false }) {
	const [showHelper, setShowHelper] = useState(false);
	const handleShowHelper = () => setShowHelper(false);

	return (
		<>
			<div
				className={`gam-lb-helper gam-sid es-pad ${compact ? 'compact' : ''}`}
			>
				<button onClick={() => setShowHelper(true)}>
					{label}{' '}
					<span className="gam-sb-t-icon">
						<svg
							width="13"
							height="13"
							viewBox="0 0 13 13"
							fill="none"
							xmlns="http://www.w3.org/2000/svg"
						>
							<path
								d="M6.5 0C2.9079 0 0 2.9079 0 6.5C0 10.0921 2.9079 13 6.5 13C10.0921 13 13 10.0921 13 6.5C13 2.9079 10.0779 0 6.5 0ZM7.08443 9.86404C6.91338 10.0208 6.69956 10.0921 6.44298 10.0921C6.1864 10.0921 5.97259 10.0208 5.80154 9.86404C5.63048 9.70724 5.54496 9.50768 5.54496 9.27961C5.54496 9.03728 5.63048 8.83772 5.80154 8.69518C5.97259 8.53838 6.1864 8.46711 6.44298 8.46711C6.69956 8.46711 6.91338 8.53838 7.08443 8.69518C7.25548 8.85197 7.34101 9.05154 7.34101 9.27961C7.34101 9.52193 7.25548 9.72149 7.08443 9.86404ZM8.60965 5.54496C8.50987 5.75877 8.32456 5.98684 8.09649 6.21491L7.54057 6.72807C7.38377 6.88487 7.26974 7.04167 7.21272 7.19847C7.1557 7.35526 7.11294 7.55483 7.11294 7.8114H5.73026C5.73026 7.34101 5.78728 6.9704 5.88706 6.69956C5.98684 6.42873 6.17215 6.1864 6.40022 5.98684C6.64254 5.78728 6.82785 5.60197 6.94189 5.43092C7.07018 5.25987 7.12719 5.07456 7.12719 4.875C7.12719 4.39035 6.91338 4.14803 6.5 4.14803C6.30044 4.14803 6.14364 4.2193 6.02961 4.36184C5.91557 4.50439 5.8443 4.68969 5.8443 4.93202H4.20504C4.20504 4.29057 4.41886 3.77741 4.81798 3.42105C5.21711 3.06469 5.78728 2.87939 6.5 2.87939C7.22697 2.87939 7.7829 3.05044 8.18202 3.37829C8.58114 3.70614 8.7807 4.19079 8.7807 4.80373C8.7807 5.07456 8.72369 5.31689 8.60965 5.54496Z"
								fill="white"
							/>
						</svg>
					</span>
				</button>
			</div>
			<Modal
				show={showHelper}
				onHide={handleShowHelper}
				className="gam-climb-modal gam-common-modal dis_center_modal gam-profile-pop-up"
				aria-labelledby="gam-helper-modal"
			>
				<div className="gam-pop-content gam-helper">
					<Modal.Body className="">
						<Button
							className="gam-modal-close"
							onClick={handleShowHelper}
							aria-label="Close"
							variant="secondary"
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
						<ModalLeaderBoardHelperContent />

						<button
							className="gam_btn btn_lr_30 mw"
							type="button"
							onClick={handleShowHelper}
						>
							Got it
						</button>
					</Modal.Body>
				</div>
			</Modal>
		</>
	);
}

export default ModalLeaderBoardHelper;
