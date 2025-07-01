import { useState } from 'react';
import useGlobalVariables from './hooks/useGlobalVariables';
import { Modal, Button } from 'react-bootstrap';

function DiscoveredRulesModalApp() {
	const { FeaturedVideosHelper } = useGlobalVariables();
	const [showRulesModal, setShowRulesModal] = useState(false);
	const handleShowRulesModal = () => setShowRulesModal(false);

	return (
		<>
			<button
				className="gam-rules-btn"
				type="button"
				onClick={() => setShowRulesModal(true)}
			>
				<span>&#63;</span>
			</button>
			<Modal
				show={showRulesModal}
				onHide={handleShowRulesModal}
				className="gam-feature-video gam-common-modal dis_center_modal gam-profile-pop-up "
				aria-labelledby="gam-helper-modal"
			>
				<div className="gam-pop-content gam-helper">
					<Modal.Body className="">
						<Button
							className="gam-modal-close "
							onClick={handleShowRulesModal}
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

						<h3 className="gam-modal-title">{FeaturedVideosHelper.title}</h3>

						<div className="gam-modal-content">
							{FeaturedVideosHelper?.content?.paragraphs &&
								FeaturedVideosHelper?.content?.paragraphs.map(
									(paragraph, i) => {
										return (
											<p className="gam-modal-des" key={i}>
												{paragraph.paragraph}
											</p>
										);
									}
								)}
							<p>
								<strong>{FeaturedVideosHelper.tip}</strong>
							</p>
							<a
								className="primary_link"
								target="_blank"
								rel="noreferrer"
								href={`${window.location.origin}/terms-and-privacy`}
							>
								{FeaturedVideosHelper.termsLabel}
							</a>
						</div>

						<button
							className="gam_btn btn_lr_30 mw"
							type="button"
							onClick={handleShowRulesModal}
						>
							{FeaturedVideosHelper.closeButtonLabel}
						</button>
					</Modal.Body>
				</div>
			</Modal>
		</>
	);
}

export default DiscoveredRulesModalApp;
