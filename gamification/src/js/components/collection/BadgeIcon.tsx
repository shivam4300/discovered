import { useOtherPlayerProfile } from '../../hooks/useOtherPlayer';

function BadgeIcon({
	image = 'https://picsum.photos/300/300',
	badge,
	score = 0,
	total = 0,
	completed = false,
}) {
	const badgeData = useOtherPlayerProfile(badge.data.channelId);

	return (
		<>
			<div className={`gam-badge-img lrg-icon ${completed ? 'completed' : ''}`}>
				<img src={image} alt={badge.playfab.DisplayName} />
			</div>
			{total > 0 && (
				<div className="gam-badge-stats">
					<span className="gam-badge-score">{Math.min(score || 0, total)}</span>
					/<span className="gam-badge-total">{total}</span>
				</div>
			)}
			<p className="gam-badge-progress-text">{badge.playfab.DisplayName}</p>
		</>
	);
}

export default BadgeIcon;
