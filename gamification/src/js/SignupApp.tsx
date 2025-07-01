import { useAppSelector, useAppDispatch } from './redux/config/store';
import useGlobalVariables from './hooks/useGlobalVariables';
import { setStep, setAccountType } from './redux/signupForm';
import { useEffect, useState } from 'react';
import validationSchema from './components/signupForm/validationSchema';
import formInitialValues from './components/signupForm/formInitialValues';
import { writePlayerEvent } from './redux/playfab';
import useBadgeNotifications from './hooks/useBadgeNotifications';

import { Formik, Form } from 'formik';
import Select from 'react-select';
import { getCookie } from './utils/cookies';
import { Modal } from 'react-bootstrap';

import FormStepContent from './components/signupForm/formstepContent';
import ProgressBar from './components/ui/ProgressBar';
import LoadingScreen from './components/signupForm/loadingScreen';

function SignUpApp() {
	// launch stage build v1
	const { notifications: badges } = useBadgeNotifications();
	const [showLoader, setShowLoader] = useState(false);
	const [showSignUpModal, setShowSignUpModal] = useState(false);
	const [showHelperModal, setShowHelperModal] = useState(false);
	const dispatch = useAppDispatch();
	const [accountTypeCompleted, setAccountTypeCompleted] = useState(false);
	const { step, accountType } = useAppSelector((state) => state.signupForm);
	const steps = validationSchema[accountType];
	const currentValidationSchema = validationSchema[accountType][step];
	const isLastStep = step === Object.keys(steps).length - 1;
	const { SignUpContentCommon } = useGlobalVariables();

	const options = [
		{ value: 'icon', label: 'Icon' },
		{ value: 'emerging', label: 'Emerging' },
		{ value: 'brand', label: 'Brand' },
		{ value: 'fan', label: 'Fan' },
	];

	// Disable enter key for the sign up form
	function handleKeyDown(event) {
		if (event.key === 'Enter') {
			event.preventDefault();
			return false;
		}
	}

	function handleCloseSignUpModal() {
		setShowSignUpModal(false);
		window.location.href = '/';
	}

	const stepBack = (e: React.MouseEvent<HTMLButtonElement>) => {
		e.preventDefault();
		dispatch(setStep(step - 1));
	};

	const handleSubmit = async (values, actions) => {
		if (isLastStep) {
			values.account_type = accountType;

			const myHeaders = new Headers();
			myHeaders.append('Authorization', `Bearer ${getCookie('AuthTkn')}`);

			var requestOptions: RequestInit = {
				method: 'POST',
				redirect: 'follow',
				headers: myHeaders,
				body: JSON.stringify(values),
			};

			actions.setSubmitting(true);
			setShowLoader(true);

			const response = await fetch(`/api/v4/account/upgrade`, requestOptions);

			const json = await response.json();

			if (json) {
				if (
					accountType == 'emerging' ||
					accountType == 'brand' ||
					accountType == 'icon'
				) {
					dispatch(writePlayerEvent({ name: 'grant_creator_badge' }));
					dispatch(writePlayerEvent({ name: 'init_creator_poll' }));
				}

				if (accountType == 'fan') {
					dispatch(writePlayerEvent({ name: 'grant_fan_badge' }));
					dispatch(writePlayerEvent({ name: 'init_fan_poll' }));
				}

				//setShowSignUpModal(false);
				//dispatch(setStep(0));
				// window.location.href = `/profile?user=${values.profile_url}`;
				//dispatch(setStep(step + 1));
			}
		} else {
			dispatch(setStep(step + 1));
			actions.setTouched({});
			actions.setSubmitting(false);

			if (values.icon_status_4 === true) {
				dispatch(setAccountType('emerging'));
				dispatch(setStep(0));
				values.icon_status_4 = false;
			}
		}
	};

	const labelButton = isLastStep
		? 'Finish'
		: Object.keys(currentValidationSchema?.fields || {})?.length === 0
		? 'Skip'
		: 'Next';

	useEffect(() => {
		const cookiehdydu = getCookie('hdydu_closed');
		// reset step in case of refresh during the sign up process
		dispatch(setStep(0));

		if (cookiehdydu === 'yes') {
			setShowSignUpModal(true);
			return;
		}

		let mcTimer = setInterval(() => {
			const cookiehdyduInterval = getCookie('hdydu_closed');

			if (cookiehdyduInterval) {
				setShowSignUpModal(true);
				clearInterval(mcTimer);
			}
		}, 1000);

		return () => {
			clearInterval(mcTimer);
		};
	}, []);

	useEffect(() => {
		console.log(badges,'badges');
		
		if (badges?.length !== 0) {
			setShowSignUpModal(false);
			setShowLoader(false);
			dispatch(setStep(0));
		}
		return () => {};
	}, [badges]);

	return (
		<>
			<Modal
				show={showSignUpModal}
				className="gam-sign-up-modal fade dis_center_modal"
			>
				<button
					type="button"
					className="close"
					data-dismiss="modal"
					aria-label="Close"
					onClick={() => handleCloseSignUpModal()}
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
				<div className="modal-body">
					{!accountTypeCompleted ? (
						<div className="gam-account-type-app">
							<div className="gam-header-logo">
								<img src={SignUpContentCommon?.formDiscoveredLogo} alt="logo" />
							</div>
							<div className="gam-title">
								{SignUpContentCommon?.accountTypeTitle}
							</div>
							<p className="gam-description">
								{SignUpContentCommon?.accountTypeText}
							</p>
							<form onSubmit={() => setAccountTypeCompleted(true)}>
								<Select
									name="gam-account-type"
									id="gam-account-select"
									className="gam-custom-select text-left"
									classNamePrefix="gam-select"
									defaultValue={{ value: 'icon', label: 'Icon' }}
									value={options.find((option) => option.value === accountType)}
									onChange={(option) => dispatch(setAccountType(option.value))}
									options={options}
								/>
								<div className="gam-form-buttons gam-buttons-wrapper single">
									<button
										className="gam_btn next"
										style={{ display: 'block' }}
										type="submit"
									>
										Continue
									</button>
								</div>
							</form>
						</div>
					) : (
						<Formik
							initialValues={formInitialValues}
							validationSchema={currentValidationSchema}
							onSubmit={handleSubmit}
						>
							{({ values, isSubmitting }) => (
								<Form onKeyDown={handleKeyDown}>
									{(step !== 0 || (accountType === 'fan' && step === 0)) && (
										<>
											<div className="gam-form-header">
												<div className="gam-form-title">Profile Creation</div>
												<div className="gam-form-steps">
													{' '}
													({accountType === 'fan' ? step + 1 : step}/
													{accountType === 'fan'
														? Object.keys(steps).length + 1
														: Object.keys(steps).length}
													)
												</div>
											</div>
											<ProgressBar
												step={accountType === 'fan' ? step + 1 : step}
												totalSteps={
													accountType === 'fan'
														? Object.keys(steps).length + 1
														: Object.keys(steps).length
												}
											/>
										</>
									)}

									<FormStepContent step={step} type={accountType} />
									<LoadingScreen isSubmitting={showLoader} />

									<div className="gam-buttons-wrapper">
										{step == 0 && (
											<button
												className="gam_btn back"
												onClick={() => setAccountTypeCompleted(false)}
											>
												<span className="gam-btn-icon gam-btn-back-icon">
													<img
														src={`${window.location.origin}/repo/images/gamification/back_arrow.svg`}
														alt="back arrow"
													/>
												</span>
												Back
											</button>
										)}
										{step !== 0 && (
											<button className="gam_btn back" onClick={stepBack}>
												<span className="gam-btn-icon gam-btn-back-icon">
													<img
														src={`${window.location.origin}/repo/images/gamification/back_arrow.svg`}
														alt="back arrow"
													/>
												</span>
												Back
											</button>
										)}
										<button
											className="gam_btn next"
											disabled={isSubmitting}
											type="submit"
										>
											{values.profile_picture ||
											values.reference_name ||
											values.reference_phone_number ||
											values.reference_email
												? 'Next'
												: labelButton}

											<span className="gam-btn-icon gam-btn-next-icon">
												<img
													src={`${window.location.origin}/repo/images/gamification/next_arrow.svg`}
													alt="next arrow"
												/>
											</span>
										</button>
									</div>
								</Form>
							)}
						</Formik>
					)}
				</div>
				{!accountTypeCompleted ? (
					<div className="modal-footer gam-modal-footer">
						<p
							className="gam-help-note"
							data-dismiss="modal"
							aria-label="Close"
							onClick={() => setShowHelperModal(true)}
						>
							Any help needed?{' '}
							<span className="gam-help-click primary_link">Click here</span>{' '}
							for help
						</p>
					</div>
				) : null}
			</Modal>

			<Modal
				show={showHelperModal}
				onHide={() => setShowHelperModal(false)}
				className="dis_center_modal gam-signup-helper-modal"
				aria-labelledby="gam-poll-trivia"
			>
				<div className="modal-dialog modal-lg" role="document">
					<div className="dis_signup_grid_inner">
						<div className="au_heading">
							<h2>Sign-up types</h2>
							<p>Choose any type to continue with sign-up process</p>
						</div>
						<div className="dis_signup_grid_box">
							<table className="dis_signup_grid_tble text-center">
								<thead>
									<tr>
										<th></th>
										<th className="text-center">
											<h2 className="dis_signup_grid_ttl">Visitor</h2>
										</th>
										<th className="text-center">
											<h2 className="dis_signup_grid_ttl">Standard</h2>
										</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>
											&nbsp;
											{/* <p className="dis_sg_bodyttl popupBMSG">
												*Typical for Icons/Creators/Brands
											</p> */}
										</td>
									</tr>
									<tr>
										<td>
											<p className="dis_sg_bodyttl">Watch Video, Stream’s</p>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
									</tr>
									<tr>
										<td>
											<p className="dis_sg_bodyttl">Share</p>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
									</tr>
									<tr>
										<td>
											<p className="dis_sg_bodyttl">Search, HELP</p>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
									</tr>
									<tr>
										<td>
											<p className="dis_sg_bodyttl">Pairing/ Casting on TV</p>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
									</tr>
									<tr>
										<td>
											<p className="dis_sg_bodyttl">Vote</p>
										</td>
										<td>
											<span className="dis_cross_sign"></span>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
									</tr>
									<tr>
										<td>
											<p className="dis_sg_bodyttl">Invite Only shows</p>
										</td>
										<td>
											<span className="dis_cross_sign"></span>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
									</tr>
									<tr>
										<td>
											<p className="dis_sg_bodyttl">Ticketed/ VOD Events</p>
										</td>
										<td>
											<span className="dis_cross_sign"></span>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
									</tr>
									<tr>
										<td>
											<p className="dis_sg_bodyttl">Social, Chat, News Feed</p>
										</td>
										<td>
											<span className="dis_cross_sign"></span>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
									</tr>
									<tr>
										<td>
											<p className="dis_sg_bodyttl">Playlists</p>
										</td>
										<td>
											<span className="dis_cross_sign"></span>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
									</tr>

									<tr>
										<td>
											<p className="dis_sg_bodyttl">Branded Channel page</p>
										</td>
										<td>
											<span className="dis_cross_sign"></span>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
									</tr>
									<tr>
										<td>
											<p className="dis_sg_bodyttl">Monetize Videos</p>
										</td>
										<td>
											<span className="dis_cross_sign"></span>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
									</tr>
									<tr>
										<td>
											<p className="dis_sg_bodyttl">Store</p>
										</td>
										<td>
											<span className="dis_cross_sign"></span>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
									</tr>
									<tr>
										<td>
											<p className="dis_sg_bodyttl">Gaming, Streaming</p>
										</td>
										<td>
											<span className="dis_cross_sign"></span>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
									</tr>
									<tr>
										<td>
											<p className="dis_sg_bodyttl">Get PAID</p>
										</td>
										<td>
											<span className="dis_cross_sign"></span>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
									</tr>
									<tr>
										<td>
											<p className="dis_sg_bodyttl">Dashboards, Analytics</p>
										</td>
										<td>
											<span className="dis_cross_sign"></span>
										</td>
										<td>
											<span className="dis_right_sign"></span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<button
						type="button"
						className="dis_cmn_close"
						data-dismiss="modal"
						onClick={() => setShowHelperModal(false)}
					>
						×
					</button>
				</div>
			</Modal>
		</>
	);
}

export default SignUpApp;
