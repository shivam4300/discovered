import { useOtherPlayerProfile } from "../../hooks/useOtherPlayer";
import ProfileImgIcon from "../ProfileImgIcon";

export default function FeaturedCreatorItem({ mission }) {
	const otherCreator = useOtherPlayerProfile(
		mission.data.channel
	);

	return otherCreator && (
		<li
			className="gam-featured-item"
		>
			<a
				href={`${window.location.origin}/api/v4/Channel/redirect/${mission.data.channel}`}
			>
				<ProfileImgIcon
					progress={
						mission?.PlayerStatus
							? mission?.PlayerStatus
							: null
					}
					creator={{
						Profile: {
							AvatarUrl: otherCreator?.AvatarUrl,
						},
					}}
					lrg={true}
				/>
				<p>{otherCreator?.DisplayName}</p>
			</a>
		</li>
	);
}