import DatePicker from 'react-datepicker';
import 'react-datepicker/dist/react-datepicker.css';
import { useField, useFormikContext } from 'formik';

const DatepickerField = ({ ...props }) => {
	const { setFieldValue } = useFormikContext();
	const [field] = useField(props);

	return (
		<DatePicker
			{...field}
			{...props}
			showMonthDropdown
			showYearDropdown
			selected={(field.value && new Date(field.value)) || null}
			onChange={(val) => {
				setFieldValue(field.name, val);
			}}
			maxDate={new Date()}
		/>
	);
};

export default DatepickerField;
