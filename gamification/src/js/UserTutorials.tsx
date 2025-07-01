import CreatorProfileSectionsTutorial from './CreatorProfileSectionsTutorial';
import FanProfileSectionsTutorial from './FanProfileSectionsTutorial';
import { useAppSelector } from './redux/config/store';
import useBadgeNotifications from './hooks/useBadgeNotifications';
import { getCookie } from './utils/cookies';

function UserTutorials() {
	const userStats = useAppSelector((state) => state.statistics);
	const { notifications: badges } = useBadgeNotifications();
	const cookiehdydu = getCookie('hdydu_closed');

	function showUserAccount() {
		if (userStats.user_type === 0) {
			return (
				<>
					<FanProfileSectionsTutorial />
				</>
			);
		} else {
			return (
				<>
					<CreatorProfileSectionsTutorial />
				</>
			);
		}
	}

	return badges?.length === 0 && cookiehdydu && <>{showUserAccount()}</>;
}

export default UserTutorials;
