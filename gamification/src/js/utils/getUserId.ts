import { getCookie } from './cookies';
import jwt_decode from 'jwt-decode';

export default function getUserId() {
	let jwt = getCookie('AuthTkn');
	let user_id = jwt && jwt_decode(jwt)?.user_uname;

	return user_id;
}

export function getUserUID() {
	let jwt = getCookie('AuthTkn');
	let user_id = jwt && jwt_decode(jwt)?.user_login_id;

	return user_id;
}
