import React from 'react';
import { USER_DEFAULT_IMAGE } from '../Constants';

export default function ProfileImgIcon({ progress, creator, lrg = false }) {
	return (
		<div
			className={`gam-wc-img ${lrg ? 'lrg-icon' : ''} ${
				progress && progress.Percent === 100 ? 'completed' : ''
			}`}
		>
			<img src={creator?.Profile?.AvatarUrl || USER_DEFAULT_IMAGE} alt={creator.name} />
		</div>
	);
}
