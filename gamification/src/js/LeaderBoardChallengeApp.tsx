import useGlobalVariables from './hooks/useGlobalVariables';
import CountdownTimer from './components/CountdownTimer';
import WeeklyChallengeItem from './components/leaderboard/WeeklyChallengeItem';
import ModalLeaderBoardHelper from './components/leaderboard/ModalLeaderBoardHelper';
import useProfileTutorial from './hooks/useProfileTutorial';
import { useAppSelector } from './redux/config/store';
import useWeeklyChallenges from './hooks/useWeeklyChallenges';
import getAccountType from './utils/getAccountType';
import { useEffect } from 'react';

export default function LeaderBoardChallengeApp() {
	const { UncertifiedAccountContent, WeeklyLeaderboardChallenge } =
		useGlobalVariables();
	const { toggleCreatorState } = useProfileTutorial();
	const challengeState = useAppSelector(
		(state) => state.profileTutorial.challenge
	);
	const { weeklyChallenges, challengesEndDate } = useWeeklyChallenges(true);
	const standardAccountType = getAccountType() === 'standard';

	// useEffect(() => {
	// 	if (weeklyChallenges.length > 0) {
	// 		toggleCreatorState();
	// 	}

	// 	return () => {};
	// }, [weeklyChallenges]);

	if (!standardAccountType) {
		return (
			<>
				<div className='gam-weekly-challenge-content gam-non-certified'>
					<div className='gam-wc-header'>
						<div className='gam-title gam-sides-pad'>
							{WeeklyLeaderboardChallenge.title}
						</div>
						<div className='gam-sides-pad'>
							<CountdownTimer countdownDate={challengesEndDate} />
						</div>
					</div>

					<div className='gam-non-certified-content'>
						<h5>{UncertifiedAccountContent.uncertifiedAccountText}</h5>
						<a
							href={`${window.location.origin}/settings`}
							className='gam-certify-link'
						>
							{UncertifiedAccountContent.uncertifiedLinkText}
						</a>
					</div>
				</div>
			</>
		);
	}

	return (
		<>
			<div
				id='gam-focus-el-3'
				className='gam-weekly-challenge-content gam-focus-element gam-el-3'
			>
				<div className='gam-wc-header'>
					<div className='gam-wl-title'>{WeeklyLeaderboardChallenge.title}</div>
					<div className=''>
						<CountdownTimer countdownDate={challengesEndDate} />
					</div>
				</div>
				<div id='gam-focus-el-4' className='gam-wc-listing-wrap'>
					<ul className='gam-wc-listing'>
						{weeklyChallenges.map((mission, i) => {
							return (
								mission.PlayerStatus && (
									<WeeklyChallengeItem
										id={i}
										key={`creator-challenge-${i}`}
										channel={mission?.data?.channel}
										objectives={mission?.objectives}
										progress={
											mission?.PlayerStatus ? mission?.PlayerStatus : null
										}
										dynamicChallenge={i === 0 ? challengeState : null}
									/>
								)
							);
						})}
					</ul>
				</div>
				{/*<ModalLeaderBoardHelper label="More info" />*/}
			</div>
		</>
	);
}
