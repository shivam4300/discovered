import React, { MouseEventHandler } from 'react';
import { Link } from 'react-router-dom';

type ButtonProps = {
	onClick?:MouseEventHandler,
	label:string,
	url?:string,
	to?:string,
	isBlank?:boolean,
	className?:string,
	disabled?:boolean,
};

function Button({ className = '', onClick, label, url, to, isBlank, disabled = false }:ButtonProps) {
	const props = {
		className: 'button' + (className ? ' ' + className : '') + (disabled ? ' disabled' : ''),
		onClick: null,
		disabled: false,
	} as Record<string, any>;

	if (onClick) {
		props.onClick = onClick;
	}

	if (disabled) props.disabled = true;

	if (to) {
		return <Link {...props} to={to}>{label} <i/></Link>;
	} else if (url) {
		props.href = url;
		
		if (isBlank) props.target = '_blank';

		return <a {...props}>{label} <i/></a>
	}
	
	return <button {...props}>{label} <i/></button>;
}

export default Button;