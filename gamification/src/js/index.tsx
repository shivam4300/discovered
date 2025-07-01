import ReactDOM from 'react-dom/client';
import { Provider } from 'react-redux';
import { BrowserRouter } from 'react-router-dom';
import '../css/index.scss';
import GamificationServices from './GamificationServices';
import { store } from './redux/config/store';
import SignUpApp from './SignupApp';
import ProfilePointsApp from './ProfilePointsApp';
import ProfilePollTriviaApp from './ProfilePollTriviaApp';
import LeaderBoardModalApp from './LeaderBoardModalApp';
import LeaderBoardChallengeApp from './LeaderBoardChallengeApp';
import CollectionApp from './CollectionApp';
import BadgesApp from './BadgesApp';
import VideosApp from './VideosApp';
import UserTutorials from './UserTutorials';
import FanProfileSectionsTutorial from './FanProfileSectionsTutorial';
import CreatorProfileSectionsTutorial from './CreatorProfileSectionsTutorial';
import NotificationsApp from './NotificationsApp';
import DiscoveredRulesModalApp from './DiscoveredRulesModalApp';
import ModalManager from './ModalManager';

const apps = {
	'gam-services': <GamificationServices />,
	'gam-signup-root': <SignUpApp />,
	'gam-profile-points-root': <ProfilePointsApp />,
	'gam-profile-poll-trivia-root': <ProfilePollTriviaApp />,
	'gam-leaderboard-modal-root': <LeaderBoardModalApp />,
	'gam-leaderboard-challenge-root': <LeaderBoardChallengeApp />,
	'gam-collection-root': <CollectionApp />,
	'gam-badges-root': <BadgesApp />,
	'gam-user-tutorials-root': <UserTutorials />,
	'gam-fan-profile-sections-tutorial-root': <FanProfileSectionsTutorial />,
	'gam-creator-profile-sections-tutorial-root': (
		<CreatorProfileSectionsTutorial />
	),
	'gam-videos-root': <VideosApp />,
	'gam-notifications-root': <NotificationsApp />,
	'gam-discovered-rules-modal-root': <DiscoveredRulesModalApp />,
	'gam-modal-manager-root': <ModalManager />,
};

Object.entries(apps).forEach(([node, app]) => {
	const elem = document.getElementById(node);
	console.log('working')
	if (elem) {
		const root = ReactDOM.createRoot(elem);

		root.render(
			<Provider store={store}>
				<BrowserRouter>{app}</BrowserRouter>
			</Provider>
		);
	}
});
