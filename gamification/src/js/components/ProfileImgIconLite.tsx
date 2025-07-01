import { useOtherPlayerProfile } from '../hooks/useOtherPlayer';
import { USER_DEFAULT_IMAGE } from '../Constants';

export default function ProfileImgIconLite({ creatorChannel, lrg = false }) {
  const creator = useOtherPlayerProfile(creatorChannel);
	
	return (
		<div
			className={`gam-wc-img ${lrg ? 'lrg-icon' : ''}`}
		>
			<img src={creator?.AvatarUrl || USER_DEFAULT_IMAGE} alt={creator?.name} />
		</div>
	);
}
