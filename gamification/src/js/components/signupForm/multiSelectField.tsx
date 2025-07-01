import Select from 'react-select';

function MultiSelectField({ field, form, options, ...props }) {
	function handleChange(option) {
		form.setFieldValue(
			field.name,
			option ? option.map((item) => item.value) : []
		);
	}

	function getValue() {
		if (options) {
			return options.filter((option) => field.value.includes(option.value));
		} else {
			return '';
		}
	}

	return (
		<>
			<Select
				{...props}
				options={options}
				isMulti
				name={field.name}
				onBlur={field.onBlur}
				value={getValue()}
				onChange={handleChange}
			/>
		</>
	);
}

export default MultiSelectField;
