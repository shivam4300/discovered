import usePlayerInformation from './components/profile/hooks/usePlayerInformation';
import { useAppDispatch, useAppSelector } from './redux/config/store';
import { writePlayerEvent } from './redux/playfab';
import getAccountType from './utils/getAccountType';

export default function ProfileAccountPointsApp() {
	const { PlayFabId } = usePlayerInformation();
	const standardAccountType = getAccountType() === 'standard';
	const userStats = useAppSelector((state) => state.statistics);
	const dispatch = useAppDispatch();

	function onDebugPlayerAccount() {
		dispatch(
			writePlayerEvent({
				name: 'player_reset',
			})
		);
	}

	function onDebugTriggerPoll() {
		dispatch(
			writePlayerEvent({
				name: 'init_fan_poll',
			})
		);
	}

	function onDebugTriggerWeekly() {
		dispatch(
			writePlayerEvent({
				name: 'test_notification',
				MissionId: 'm-1688060471608-000',
			})
		);
	}

	return (
		PlayFabId && (
			<>
				<div className="gam-profile-fans-points">
					{standardAccountType &&
						userStats?.user_type === 1 &&
						userStats?.fans > 0 && (
							<div className="gam-profile-fans">
								{userStats?.fans} {userStats?.fans > 1 ? 'Fans' : 'Fan'}
							</div>
						)}

					{standardAccountType && (
						<div className="gam-profile-points">
							<span className="gam-profile-points-icon">
								<img
									src={`${window.location.origin}/repo/images/gamification/star_point.svg`}
									alt="point-icon"
								/>
							</span>
							<span className="gam-profile-points-text">
								{userStats?.points}
							</span>
						</div>
					)}
				</div>
			</>
		)
	);
}
