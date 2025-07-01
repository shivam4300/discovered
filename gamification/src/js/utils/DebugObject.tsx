import React, { memo } from 'react';
// @ts-ignore
import highlight from 'json-format-highlight';
import { raw } from './textUtils';

const colors = {
	keyColor: 'lightsalmon',
	stringColor: 'lightgreen',
	// numberColor: 'blue',
	// trueColor: '#00cc00',
	// falseColor: '#ff8080',
	// nullColor: 'cornflowerblue'
}

function DebugObject({ obj }) {
	return (
		<pre {...raw(highlight(obj, colors))} />
	);
}

export default memo(DebugObject);