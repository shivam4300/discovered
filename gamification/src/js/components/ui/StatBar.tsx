import React from 'react';
import { constrain } from '../../utils/constrain';
import { map } from '../../utils/map';

type StatBarProps = {
	value: number,
	min: number,
	max: number,
};

export default function StatBar({ value, min, max }: StatBarProps) {
	const width = constrain(map(value, min, max, 0, 100), 0, 100);
	
	return (
		<>
			<div className="stat-bar">
				<div className="current-level">{min}</div>
				<div className="inner" style={{ width: `${width}%` }} />
				<div className="next-level">{max}</div>
			</div>
		</>
	);
}