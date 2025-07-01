import { Fragment, useCallback } from 'react';
import { Formik, Form, Field } from 'formik';
import { useAppDispatch, useAppSelector } from '../../redux/config/store';
import useGroupedPolls from '../profile/hooks/useGroupedPolls';
import {
	addNotification,
	notificationGenerator,
} from '../../redux/notifications';
import { interactAd } from '../../utils/videos';
import { updateLocalStatistic } from '../../redux/statistics';

function AdPoll({ customId, groupedPolls, currentAd }) {
	const dispatch = useAppDispatch();
	const points = useAppSelector((state) => state.statistics.points);

	let { answerPolls } = useGroupedPolls(customId);

	groupedPolls = groupedPolls.filter((p) => p.hasAnswered === false);

	const handleSubmit = useCallback(
		(values, actions) => {
			answerPolls(Object.entries(values.answers)).then((r) => {
				let rewards = [
					...r.map((r) => r.payload.data?.Rewards?.Statistics?.points),
				].find((v) => v);

				dispatch(
					updateLocalStatistic({
						name: 'points',
						value: points + rewards,
					})
				);

				interactAd('answered_ad_poll', currentAd, dispatch);

				dispatch(
					addNotification(
						notificationGenerator({
							title: 'Poll completed!',
							message: rewards + ' pts',
						})
					)
				);
			});

			actions.setSubmitting(false);
		},
		[currentAd]
	);

	if (!groupedPolls.length) return null;

	return (
		groupedPolls && (
			<div className="gam-ad-poll">
				<Formik initialValues={{ answers: '' }} onSubmit={handleSubmit}>
					{({ values, isSubmitting }) => (
						<Form>
							{groupedPolls?.map((poll, i) => (
								<Fragment key={poll.instanceId}>
									<div className="polls" key={poll.instanceId}>
										<div className="questions">
											<p className="gam-modal-title">{poll.poll.question}</p>
										</div>
										<div className="choices">
											{poll.poll.choices.map((c, j) => (
												<label key={c.id} className="gam-radio-round-box">
													<Field
														type="radio"
														className="d-none gam-radio-round-input"
														name={`answers[${poll.instanceId}]`}
														value={c.id}
														required
													/>
													<span className="gam-radio-round">{c.label}</span>
												</label>
											))}
										</div>
									</div>
								</Fragment>
							))}

							{groupedPolls.length && (
								<div className="gam-buttons-wrapper">
									<button
										className="gam_btn next"
										disabled={isSubmitting}
										type="submit"
									>
										submit
									</button>
								</div>
							)}
						</Form>
					)}
				</Formik>
			</div>
		)
	);
}

export default AdPoll;
