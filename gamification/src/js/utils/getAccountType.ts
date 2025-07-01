import { getCookie } from './cookies';
import jwt_decode from 'jwt-decode';

export default function getAccountType() {
	let jwt = getCookie('AuthTkn');
	let account_type = jwt_decode(jwt)?.sigup_acc_type;

	return account_type;
}
