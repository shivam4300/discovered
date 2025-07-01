import React from 'react';
import { constrain } from '../../utils/constrain';
import { map } from '../../utils/map';
import StatBar from './StatBar';

type XPBarProps = {
	xp: number,
	level: number,
	currentLevelXp: number,
	nextLevelXp: number,
};

export default function XpBar({
	xp,
	level,
	currentLevelXp,
	nextLevelXp,
}: XPBarProps) {
	const width = constrain(map(xp, currentLevelXp, nextLevelXp, 0, 100), 0, 100);
	const relativeLevel = level + width / 100;
	
	return (
		<div className="xp-bar">
			<StatBar value={relativeLevel} min={level} max={level + 1} />
			<div className="xp-text">
				<div className="left"><strong>XP {xp}</strong> / {nextLevelXp}</div>
				<div className="right"><strong>{nextLevelXp - xp} XP</strong> to level up</div>
			</div>
		</div>
	);
}