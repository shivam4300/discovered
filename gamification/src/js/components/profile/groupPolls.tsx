import { useState, Fragment } from 'react';
import { Formik, Form, Field } from 'formik';
import useGroupedPolls from './hooks/useGroupedPolls';
import { useAppDispatch } from '../../redux/config/store';
import { setRewards } from '../../redux/polls';

function GroupPolls({ customId }) {
	const dispatch = useAppDispatch();
	const { groupedPolls, answerPolls } = useGroupedPolls(customId);

	const [step, setStep] = useState(0);
	const isLastStep = step === groupedPolls.length;

	const pollTitle = [
		...groupedPolls.map((p) => p.poll.customData.groupPollsTitle),
	].find((v) => v);

	const pollDescription = [
		...groupedPolls.map((p) => p.poll.customData.groupPollsDescription),
	].find((v) => v);

	const pollImage = [
		...groupedPolls.map((p) => p.poll.customData.groupPollsImage),
	].find((v) => v);

	const stepBack = (e: React.MouseEvent<HTMLButtonElement>) => {
		e.preventDefault();
		setStep((prevState) => prevState - 1);
	};

	const handleSubmit = (values, actions) => {
		if (isLastStep) {
			answerPolls(Object.entries(values.answers)).then((r) =>
				dispatch(
					setRewards(
						[...r.map((r) => r.payload.data?.Rewards?.Statistics?.points)].find(
							(v) => v
						)
					)
				)
			);
			actions.setSubmitting(false);
		} else {
			setStep((prevState) => prevState + 1);
			actions.setTouched({});
			actions.setSubmitting(false);
		}
	};

	return (
		<Formik initialValues={{ answers: '' }} onSubmit={handleSubmit}>
			{({ values, isSubmitting }) => (
				<Form>
					{step === 0 && pollTitle && pollDescription && pollImage && (
						<>
							<img className="gam-poll-img" src={pollImage.toString()} alt="poll" />
							<p className="gam-modal-title">{pollTitle}</p>
							<p className="gam-modal-des">{pollDescription}</p>
						</>
					)}

					{step !== 0 &&
						groupedPolls?.map((poll, i) => (
							<Fragment key={poll.instanceId}>
								{step - 1 === i && (
									<div className="polls" key={poll.instanceId}>
										<div className="gam-poll-question questions">
											<h1 className="gam-modal-title">This Or That?</h1>
											<p className='gam-modal-des'>{poll.poll.question}</p>
										</div>
										<div className="choices gam-poll-choice-wrap">
											{poll.poll.choices.map((c, j) => (
												<div key={c.id} > 
													<label className='gam-poll-choice-label'>
														<Field
															className="gam-poll-choice-input"
															type="radio"
															name={`answers[${poll.instanceId}]`}
															value={c.id}
															required
														/>
														<div className='gam-pcl-box'>
															<img
																src={
																	poll?.poll?.customData?.groupPollsChoicesImage?.[
																		j
																	]
																}
																alt="poll choice"
															/>
														</div>
													
														<p className='gam-poll-choice-name'>{c.label}</p>
													</label>
												</div>
											))}
										</div>
									</div>
								)}
							</Fragment>
						))}

					<div className="gam-buttons-wrapper">
						{step !== 0 && (
							<button
								className="gam_btn back"
								disabled={isSubmitting}
								onClick={stepBack}
							>
							<span className="gam-btn-icon gam-btn-back-icon"><img src={`${window.location.origin}/repo/images/gamification/back_arrow.svg`} alt="back arrow"/></span>
								Back
							</button>
						)}
						<button className="gam_btn" disabled={isSubmitting} type="submit">
							{isLastStep ? 'Submit' : step === 0 ? 'Lets do this' : 'Next'}
						</button>
					</div>
				</Form>
			)}
		</Formik>
	);
}

export default GroupPolls;
