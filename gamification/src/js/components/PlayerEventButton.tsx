import React from 'react';
import { useAppDispatch } from '../redux/config/store';
import { writePlayerEvent } from '../redux/playfab';

interface PlayerEventButtonProps {
	btnLabel: string;
	name: string;
	body?: any;
}

export default function PlayerEventButton({
	btnLabel,
	name,
	body,
}: PlayerEventButtonProps) {
	const dispatch = useAppDispatch();

	function onClick() {
		dispatch(writePlayerEvent({ name, body }));
	}

	return (
		<>
			<button type="button" onClick={onClick}>
				{btnLabel}
			</button>
		</>
	);
}
