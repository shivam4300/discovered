import { USER_DEFAULT_IMAGE } from '../../Constants';
import { useOtherPlayerProfile } from '../../hooks/useOtherPlayer';

function PinIcon({ pin }) {
	const pinData = useOtherPlayerProfile(pin.data.channelId);

	return (
		<a
			href={`${window.location.origin}/api/v4/Channel/redirect/${pinData?.PlayerId}`}
		>
			<div className="gam-wc-img completed lrg-icon">
				<img
					src={pinData?.AvatarUrl || USER_DEFAULT_IMAGE}
					alt={pin.playfab.DisplayName}
				/>
			</div>
		</a>
	);
}

export default PinIcon;
