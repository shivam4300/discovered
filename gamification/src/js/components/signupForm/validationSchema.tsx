import * as Yup from 'yup';
import getUserId from '../../utils/getUserId';

const validateProfilUrl = async (value) => {
	const specialChars = [
		'[',
		']',
		'@',
		'/',
		'#',
		'$',
		'%',
		'=',
		'!',
		'^',
		'&',
		'*',
		'`',
		'\\',
	];

	const containsSpecialChar = specialChars.some((char) => value.includes(char));

	if (!containsSpecialChar) {
		let requestOptions: RequestInit = {
			method: 'GET',
			redirect: 'follow',
		};

		const response = await fetch(
			`/api/v4/account/isUsernameAvailable/${value}`,
			requestOptions
		);

		const json = await response.json();

		if (json.data.available) return true;

		if (getUserId() == value) return true;

		return false;
	}
};

const ValidationSchema = {
	icon: {
		0: Yup.object().shape({
			icon_status_1: Yup.boolean(),
			icon_status_2: Yup.boolean(),
			icon_status_3: Yup.boolean(),
			icon_status_4: Yup.boolean(),
			atLeastOneTrue: Yup.mixed().test(
				'at-least-one-true',
				'Select at least 1 option',
				function (value) {
					const { icon_status_1, icon_status_2, icon_status_3, icon_status_4 } =
						this.parent;
					return (
						icon_status_1 || icon_status_2 || icon_status_3 || icon_status_4
					);
				}
			),
		}),
		1: Yup.object().shape({
			icon_type: Yup.array().min(1, 'Select at least 1 option'),
		}),
		2: Yup.object().shape({
			date_of_birth: Yup.string().required('Date of birth is required'),
			gender: Yup.string().required('This field is required'),
		}),
		3: Yup.object().shape({
			country: Yup.string().required('This field is required'),
			state: Yup.string().required('This field is required'),
			city: Yup.string().required('This field is required'),
		}),
		4: Yup.object().shape({
			mailing_address: Yup.string().required('This field is required'),
			zip: Yup.string().required('This field is required'),
			phone: Yup.string()
				.matches(/^[0-9]*$/, 'Phone number must only contain numbers')
				.required('Phone number is required'),
		}),
		5: Yup.object().shape({
			// reference_phone_number: Yup.string().required('This field is required'),
		}),
		6: Yup.object().shape({
			interests: Yup.array().min(1, 'Select at least 1 option'),
		}),
		7: Yup.object().shape({}),
		8: Yup.object().shape({
			profile_url: Yup.string()
				.matches(/^[a-zA-Z0-9.-]*$/, 'Invalid characters are now allowed.')
				.test(
					'profile_url',
					'This username is already registered.',
					validateProfilUrl
				)
				.required('This field is required'),
		}),
		9: Yup.object().shape({
			terms_of_condition: Yup.boolean().oneOf(
				[true],
				'You need to accept terms and conditions.'
			),
		}),
	},
	emerging: {
		0: Yup.object().shape({
			emerging_type: Yup.array().min(1, 'Select at least 1 option'),
		}),
		1: Yup.object().shape({
			date_of_birth: Yup.string().required('Date of birth is required'),
			gender: Yup.string().required('This field is required'),
		}),
		2: Yup.object().shape({
			country: Yup.string().required('This field is required'),
			state: Yup.string().required('This field is required'),
			city: Yup.string().required('This field is required'),
		}),
		3: Yup.object().shape({
			mailing_address: Yup.string().required('This field is required'),
			zip: Yup.string().required('This field is required'),
			phone: Yup.number().required('This field is required'),
		}),
		4: Yup.object().shape({
			interests: Yup.array().min(1, 'Select at least 1 option'),
		}),
		5: Yup.object().shape({}),
		6: Yup.object().shape({
			profile_url: Yup.string()
				.matches(/^[a-zA-Z0-9.-]*$/, 'Invalid characters are now allowed.')
				.test(
					'profile_url',
					'This username is already registered.',
					validateProfilUrl
				)
				.required('This field is required'),
		}),
		7: Yup.object().shape({
			terms_of_condition: Yup.boolean().oneOf(
				[true],
				'You need to accept terms and conditions.'
			),
		}),
	},
	brand: {
		0: Yup.object().shape({
			brand_type: Yup.array().min(1, 'Select at least 1 option'),
			brand_level: Yup.string().required('This field is required'),
		}),
		1: Yup.object().shape({
			date_of_birth: Yup.string().required('Date of birth is required'),
			gender: Yup.string().required('This field is required'),
		}),
		2: Yup.object().shape({
			country: Yup.string().required('This field is required'),
			state: Yup.string().required('This field is required'),
			city: Yup.string().required('This field is required'),
		}),
		3: Yup.object().shape({
			mailing_address: Yup.string().required('This field is required'),
			zip: Yup.string().required('This field is required'),
			phone: Yup.number().required('This field is required'),
		}),
		4: Yup.object().shape({
			interests: Yup.array().min(1, 'Select at least 1 option'),
		}),
		5: Yup.object().shape({}),
		6: Yup.object().shape({
			profile_url: Yup.string()
				.matches(/^[a-zA-Z0-9.-]*$/, 'Invalid characters are now allowed.')
				.test(
					'profile_url',
					'This username is already registered.',
					validateProfilUrl
				)
				.required('This field is required'),
		}),
		7: Yup.object().shape({
			terms_of_condition: Yup.boolean().oneOf(
				[true],
				'You need to accept terms and conditions.'
			),
		}),
	},
	fan: {
		0: Yup.object().shape({
			date_of_birth: Yup.string().required('Date of birth is required'),
			gender: Yup.string().required('This field is required'),
		}),
		1: Yup.object().shape({
			country: Yup.string().required('This field is required'),
			state: Yup.string().required('This field is required'),
			city: Yup.string().required('This field is required'),
		}),
		2: Yup.object().shape({
			mailing_address: Yup.string().required('This field is required'),
			zip: Yup.string().required('This field is required'),
			phone: Yup.number().required('This field is required'),
		}),
		3: Yup.object().shape({
			interests: Yup.array().min(1, 'Select at least 1 option'),
		}),
		4: Yup.object().shape({}),
		5: Yup.object().shape({
			profile_url: Yup.string()
				.matches(/^[a-zA-Z0-9.-]*$/, 'Invalid characters are now allowed.')
				.test(
					'profile_url',
					'This username is already registered.',
					validateProfilUrl
				)
				.required('This field is required'),
		}),
		6: Yup.object().shape({
			terms_of_condition: Yup.boolean().oneOf(
				[true],
				'You need to accept terms and conditions.'
			),
		}),
	},
};

export default ValidationSchema;
