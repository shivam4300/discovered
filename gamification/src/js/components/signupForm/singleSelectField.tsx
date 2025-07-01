import Select from 'react-select';

function SingleSelectField({ field, form, options, ...props }) {
	function getValue() {
		if (options) {
			return options.find((option) => option.value === field.value);
		} else {
			return '';
		}
	}

	return (
		<>
			<Select
				{...props}
				options={options}
				name={field.name}
				onBlur={field.onBlur}
				value={getValue()}
			/>
		</>
	);
}

export default SingleSelectField;
