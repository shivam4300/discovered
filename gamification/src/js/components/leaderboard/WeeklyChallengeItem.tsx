import { useState, useRef } from 'react';
import ProfileImgIcon from '../ProfileImgIcon';
import { useOtherPlayerProfile } from '../../hooks/useOtherPlayer';
import { log } from 'console';
import { Link } from 'react-router-dom';

function WeeklyChallengeItem({
	id,
	objectives,
	progress,
	channel,
	dynamicChallenge,
}) {
	const [isChallengeOpen, setIsChallengeOpen] = useState(false);
	const challengeContentRef = useRef(null);

	// Sort objectives alphabetically
	const sortedObjectives = objectives.slice().sort((a, b) => {
		const titleA = a.title.toLowerCase();
		const titleB = b.title.toLowerCase();
		if (titleA < titleB) return -1;
		if (titleA > titleB) return 1;
		return 0;
	});


	function getChallengeContentHeight() {
		if (challengeContentRef.current) {
			return challengeContentRef.current.scrollHeight;
		} else {
			return 0;
		}
	}

	const creator = useOtherPlayerProfile(channel);

	return creator && (
		<li
			id={`gam-challenge-el-${id}`}
			className={`gam-list-item ${
				id === 0 ? 'gam-focus-element gam-dyn-challenge' : ''
			}`}
		>
			<div
				onClick={() => setIsChallengeOpen(!isChallengeOpen)}
				className={`gam-wc-preview gam-toggle-btn ${
					isChallengeOpen || dynamicChallenge ? 'open-challenge' : ''
				}`}
			>
				<div className='gam-wc-info-wrapper'>
					<div className='gam-wc-info'>
						<ProfileImgIcon progress={progress} creator={{ DisplayName: creator.DisplayName, Profile: { AvatarUrl: creator.AvatarUrl } }} />
						<a href={`${window.location.origin}/api/v4/Channel/redirect/${creator.PlayerId}`} className='gam-wc-name' onClick={(e) => e.stopPropagation()}>
							<span> {creator?.DisplayName}</span>
						</a>
					</div>
				</div>

				<div className='gam-wc-progress-bar'>
					<div
						className='gam-wc-progress'
						style={{ width: `${progress ? progress.Percent : 0}%` }}
					/>
				</div>
			</div>
			<div
				className={`gam-list-challenges ${
					isChallengeOpen || dynamicChallenge ? 'open-challenge' : ''
				}`}
			>
				<ul
					className='gam-toggle-content'
					ref={challengeContentRef}
					style={{
						maxHeight:
							isChallengeOpen || dynamicChallenge
								? `${getChallengeContentHeight()}px`
								: '0',
					}}
				>
					{sortedObjectives &&
						sortedObjectives.map((objective, i) => {
							return (
								<li className='gam-single-challenge' key={i}>
									<span
										className={`gam-challenge-icon ${ objective?.PlayerStatus?.IsComplete ? 'completed' : ''}`}
									>
										{ objective?.PlayerStatus?.IsComplete ? 
										<img src={`${window.location.origin}/repo/images/gamification/chlng_right.svg`} alt="right" />
										: <img src={`${window.location.origin}/repo/images/gamification/chlng_star.svg`} alt="star" /> }
											
									</span>
									<span>{objective.title}</span>
								</li>
							);
						})}
				</ul>
			</div>
		</li>
	);
}

export default WeeklyChallengeItem;
