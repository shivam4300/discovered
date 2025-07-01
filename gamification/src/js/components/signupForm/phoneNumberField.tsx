import { useFormikContext, ErrorMessage } from 'formik';

const phoneMask = (value) => {
	return value.replace(/\D/g, '');
};

function PhoneNumberField({ name }) {
	const { values, setFieldValue } = useFormikContext();

	const handleChange = (e) => {
		const rawValue = e.target.value;
		const sanitizedValue = phoneMask(rawValue);
		setFieldValue(name, sanitizedValue);
	};

	return (
		<>
			<input
				type="tel"
				className="gam-filed h50 w-100 plr20"
				name={name}
				value={values[name]}
				onChange={handleChange}
				onBlur={handleChange}
			/>
			<div className="gam-error-msg">
				<ErrorMessage name={name} />
			</div>
		</>
	);
}

export default PhoneNumberField;
