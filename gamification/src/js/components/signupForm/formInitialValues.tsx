import { getCookie } from '../../utils/cookies';
import { getUserUID } from '../../utils/getUserId';

let formInitialValues = {
	account_type: '',
	phone: '',
	country: '',
	state: '',
	zip: '',
	city: '',
	mailing_address: '',
	date_of_birth: '',
	icon_status_1: false,
	icon_status_2: false,
	icon_status_3: false,
	icon_status_4: false,
	gender: '',
	brand_level: '',
	interests: [],
	terms_of_condition: false,
	icon_type: [],
	emerging_type: [],
	brand_type: [],
	profile_picture: '',
	profile_url: '',
	reference_name: '',
	reference_phone_number: '',
	reference_email: '',
};

let genders = [
	{ value: '1', label: 'Male' },
	{ value: '2', label: 'Female' },
	{ value: '3', label: 'Transgender' },
	{ value: '4', label: 'Prefer not to answer' },
];

const getProfileInfo = async (uid) => {
	const myHeaders = new Headers();
	myHeaders.append('Authorization', `Bearer ${getCookie('AuthTkn')}`);

	let formData = new FormData();
	formData.append('user_id', uid);

	var requestOptions: RequestInit = {
		method: 'POST',
		redirect: 'follow',
		headers: myHeaders,
		body: formData,
	};

	const response = await fetch(`/api/v2/appdashboard/profile`, requestOptions);
	const json = await response.json();

	if (json) {
		let userData = json['userDetail'][0];

		// date of birth
		if (checkDateisValid(new Date(userData.uc_dob))) {
			formInitialValues.date_of_birth = userData.uc_dob;
		}

		// gender
		formInitialValues.gender = genders.filter(
			(g) => g.label == userData?.uc_gender
		)[0]?.value;

		// city
		formInitialValues.city = userData?.uc_city || '';

		// country
		const country = await fetch(`/api/v4/Country`);
		const jsonC = await country.json();

		if (jsonC) {
			formInitialValues.country = jsonC.data.filter(
				(c) => c.country_name == userData.country_name
			)[0]?.country_id;

			let requestOptions: RequestInit = {
				method: 'GET',
				redirect: 'follow',
			};

			const states = await fetch(
				`/api/v4/State?country_id=${formInitialValues.country}`,
				requestOptions
			);

			const jsonS = await states.json();

			if (jsonS) {
				formInitialValues.state =
					userData.name &&
					jsonS.data.filter((s) => s.name == userData.name)[0]?.id;
			}
		}

		// adress
		formInitialValues.mailing_address = userData?.user_address || '';

		// zip code
		//formInitialValues.zip = userData.user_address;

		// phone
		formInitialValues.phone = userData?.user_phone || '';

		// profile url
		formInitialValues.profile_url = userData?.user_uname || '';
	}
};

function checkDateisValid(date) {
	return date instanceof Date && !isNaN(date);
}

if (getUserUID()) getProfileInfo(getUserUID());

export default formInitialValues;
