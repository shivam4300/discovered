import { useEffect, useState } from 'react';
import useGlobalVariables from '../../hooks/useGlobalVariables';
import IntroTextBlock from './introTextBlock';
import { Field, ErrorMessage, useFormikContext } from 'formik';
import MultiSelectField from './multiSelectField';
import SingleSelectField from './singleSelectField';
import PhoneNumberField from './phoneNumberField';
import DatepickerField from './datePicker';
import HelperPop from './helperPopUp';
import InterestsField from './interestsField';
import ProfileImgCrop from './ProfileImgCrop';

export default function FormIconType({ step }) {
	const { SignUpContentIcon, SignUpContentCommon } = useGlobalVariables();
	const { setFieldValue } = useFormikContext();
	const [country, setCountry] = useState([]);
	const [region, setRegion] = useState([]);
	const [iconType, setIconType] = useState([]);
	const [isChecked1, setIsChecked1] = useState(false);
	const [isChecked2, setIsChecked2] = useState(false);
	const [isChecked3, setIsChecked3] = useState(false);
	const [isChecked4, setIsChecked4] = useState(false);
	const [isCheckedError, setIsCheckedError] = useState(false);
	const [formChange, setFormChange] = useState(false);

	const handleCheckbox1Change = (e) => {
		setFieldValue('icon_status_1', e.target.checked);
		setFieldValue('icon_status_4', false);
		setIsChecked1(e.target.checked);
		setIsChecked4(false);
		setFormChange(true);
	};

	const handleCheckbox2Change = (e) => {
		setFieldValue('icon_status_2', e.target.checked);
		setFieldValue('icon_status_4', false);
		setIsChecked2(e.target.checked);
		setIsChecked4(false);
		setFormChange(true);
	};

	const handleCheckbox3Change = (e) => {
		setFieldValue('icon_status_3', e.target.checked);
		setFieldValue('icon_status_4', false);
		setIsChecked3(e.target.checked);
		setIsChecked4(false);
		setFormChange(true);
	};

	const handleCheckbox4Change = (e) => {
		setFieldValue('icon_status_4', e.target.checked);
		setIsChecked4(e.target.checked);
		setIsChecked1(false);
		setIsChecked2(false);
		setIsChecked3(false);
		setFormChange(true);
	};

	const getCountry = async () => {
		const response = await fetch(`/api/v4/Country`);
		const json = await response.json();

		const countrys = json.data.map(function (c) {
			c.country_name = c.country_name.toLowerCase();
			c.country_name =
				c.country_name[0].toUpperCase() + c.country_name.slice(1);
			return c;
		});

		setFieldValue('country', '192');
		setCountry(countrys);
	};

	const getStateByCountry = async (id) => {
		const requestOptions: RequestInit = {
			method: 'GET',
			redirect: 'follow',
		};

		const response = await fetch(
			`/api/v4/State?country_id=${id}`,
			requestOptions
		);

		const json = await response.json();

		setRegion(json.data);
	};

	const handleCountryChange = (option) => {
		setFieldValue('country', option.value);
		setFieldValue('city', '');
		getStateByCountry(option.value);
	};

	const getIconType = async () => {
		const response = await fetch(`/api/v4/ArtistCategory?parent_id=1`);
		const json = await response.json();

		setIconType(json.data);
	};

	useEffect(() => {
		getIconType();
		getCountry();
		getStateByCountry(192);
	}, []);

	useEffect(() => {
		if (
			formChange &&
			!isChecked1 &&
			!isChecked2 &&
			!isChecked3 &&
			!isChecked4
		) {
			setIsCheckedError(true);
		} else {
			setIsCheckedError(false);
		}
	}, [
		formChange,
		isCheckedError,
		isChecked1,
		isChecked2,
		isChecked3,
		isChecked4,
	]);

	return (
		<div>
			{step == 0 && (
				<>
					<div className="gam-header-logo">
						<img src={SignUpContentCommon?.formDiscoveredLogo} alt="logo" />
					</div>
					<div className="gam-step-title">
						{SignUpContentIcon?.stepOneTitle}
					</div>
					<div className="gam-description">
						{SignUpContentIcon?.stepOneText}
					</div>
					<div role="group">
						<div className="gam-top-instructions">
							<label className="gam-check-classic">
								<Field
									type="checkbox"
									name="icon_status_1"
									checked={isChecked1}
									onChange={handleCheckbox1Change}
									className="hide"
								/>
								<span className="gam-instructions-text">
									{SignUpContentIcon?.stepOneLabelOne}
								</span>
							</label>
							<label className="gam-check-classic">
								<Field
									type="checkbox"
									name="icon_status_2"
									checked={isChecked2}
									onChange={handleCheckbox2Change}
									className="hide"
								/>
								<span className="gam-instructions-text">
									{SignUpContentIcon?.stepOneLabelTwo}
								</span>
							</label>
							<label className="gam-check-classic">
								<Field
									type="checkbox"
									name="icon_status_3"
									checked={isChecked3}
									onChange={handleCheckbox3Change}
									className="hide"
								/>
								<span className="gam-instructions-text">
									{SignUpContentIcon?.stepOneLabelThree}
								</span>
							</label>
						</div>
						<label className="gam-check-classic  m_b_40">
							<Field
								type="checkbox"
								name="icon_status_4"
								checked={isChecked4}
								onChange={handleCheckbox4Change}
								className="hide"
							/>
							<span className="gam-instructions-text">
								{SignUpContentIcon?.stepOneLabelFour}{' '}
								<span className="underline">
									{SignUpContentIcon?.stepOneLabelFourNotice}
								</span>
							</span>
						</label>
					</div>
					<div className="gam-error-msg">
						{isCheckedError && <p>Please select at least one checkbox.</p>}
					</div>
				</>
			)}

			{step == 1 && (
				<>
					<IntroTextBlock />
					<div className="gam-input-fields">
						<p className="gam-title">{SignUpContentIcon?.stepTwoTitle}</p>
						{SignUpContentIcon?.stepTwoText && (
							<p className="gam-description">
								{SignUpContentIcon?.stepTwoText}
							</p>
						)}
						<Field
							name="icon_type"
							component={MultiSelectField}
							placeholder="Search type"
							className="gam-custom-select gam-multi-custom-select lrg text-left"
							classNamePrefix="gam-select"
							options={[
								...iconType?.map((it) => {
									return { value: it.category_id, label: it.category_name };
								}),
							]}
						/>
						<div className="gam-error-msg">
							<ErrorMessage name="icon_type" />
						</div>
					</div>
				</>
			)}

			{step == 2 && (
				<>
					<IntroTextBlock />
					<div className="gam-input-fields text-left">
						<div className="gam-field-row">
							<p className="gam-field-label">Date of birth:</p>
							<div className="gam-signup-calender-wrap">
								<DatepickerField
									placeholderText={'Select Date Of Birth'}
									className="gam-calender-wrap gam-filed h50 w-100 text-left plr20"
									name="date_of_birth"
								/>
								<span className="gam-calender-icon">
									<img
										src={`${window.location.origin}/repo/images/gamification/calender.svg`}
										alt="calender"
									/>
								</span>
							</div>

							<div className="gam-error-msg">
								<ErrorMessage name="date_of_birth" />
							</div>
						</div>
						<div className="gam-field-row">
							<p className="gam-field-label">Gender:</p>
							<Field
								name="gender"
								component={SingleSelectField}
								placeholder="Select gender"
								className="gam-custom-select text-left"
								classNamePrefix="gam-select"
								onChange={(option) => setFieldValue('gender', option.value)}
								options={[
									{ value: '1', label: 'Male' },
									{ value: '2', label: 'Female' },
									{ value: '3', label: 'Transgender' },
									{ value: '4', label: 'Prefer not to answer' },
								]}
							/>
							<div className="gam-error-msg">
								<ErrorMessage name="gender" />
							</div>
						</div>
					</div>
				</>
			)}

			{step == 3 && (
				<>
					<IntroTextBlock />
					<div className="gam-input-fields text-left">
						<div className="gam-field-row">
							<p className="gam-field-label">Country:</p>
							<Field
								name="country"
								component={SingleSelectField}
								placeholder="Select country"
								className="gam-custom-select"
								classNamePrefix="gam-select"
								onChange={handleCountryChange}
								defaultValue={{ value: '192', label: 'United States' }}
								options={[
									...country?.map((c) => {
										return { value: c.country_id, label: c.country_name };
									}),
								]}
							/>
							<div className="gam-error-msg">
								<ErrorMessage name="country" />
							</div>
						</div>
						<div className="gam-field-row">
							<p className="gam-field-label">State:</p>
							<Field
								name='state'
								component={(props) => <SingleSelectField {...props} />}
								placeholder='Select state'
								className='gam-custom-select'
								classNamePrefix='gam-select'
								onChange={(option) => setFieldValue('state', option.value)}
								options={[
									...region?.map((r) => {
										return { value: r.id, label: r.name };
									}),
								]}
							/>
							<div className="gam-error-msg">
								<ErrorMessage name="state" />
							</div>
						</div>
						<div className="gam-field-row">
							<p className="gam-field-label">City:</p>
							<Field
								name="city"
								className="gam-filed h50 w-100 plr20"
								placeholder="Enter City Name"
							/>
							<div className="gam-error-msg">
								<ErrorMessage name="city" />
							</div>
						</div>
					</div>
				</>
			)}

			{step == 4 && (
				<>
					<IntroTextBlock />
					<div className="gam-input-fields text-left">
						<div className="gam-field-row">
							<p className="gam-field-label">Mailing address:</p>
							<Field
								name="mailing_address"
								className="gam-filed h50 w-100 plr20"
							/>
							<div className="gam-error-msg">
								<ErrorMessage name="mailing_address" />
							</div>
						</div>
						<div className="gam-field-row">
							<p className="gam-field-label">Zip code:</p>
							<Field name="zip" className="gam-filed h50 w-100 plr20" />
							<div className="gam-error-msg">
								<ErrorMessage
									name="zip"
									className="gam-filed h50 w-100 plr20"
								/>
							</div>
						</div>
						<div className="gam-field-row">
							<p className="gam-field-label">Phone number:</p>
							<PhoneNumberField name="phone" />
						</div>
					</div>
				</>
			)}

			{step == 5 && (
				<>
					<IntroTextBlock />
					<div className="gam-input-fields text-left">
						<div className="gam-field-row">
							<div className="gam-pop-wrapper ">
								<p className="gam-field-label">Reference name :</p>
								<HelperPop text="Manager, agent, legal representative or entertainment company contact" />
							</div>
							<Field
								name="reference_name"
								className="gam-filed h50 w-100 plr20"
							/>
							<div className="gam-error-msg">
								<ErrorMessage name="reference_name" />
							</div>
						</div>
						<div className="gam-field-row">
							<div className="gam-pop-wrapper">
								<p className="gam-field-label">Reference phone number :</p>
								<HelperPop text="Manager, agent, legal representative or entertainment company contact phone number" />
							</div>
							<PhoneNumberField name="reference_phone_number" />
						</div>
						<div className="gam-field-row">
							<div className="gam-pop-wrapper">
								<p className="gam-field-label">Reference email :</p>
								<HelperPop text="Manager, agent, legal representative or entertainment company contact email address" />
							</div>
							<Field
								name="reference_email"
								className="gam-filed h50 w-100 plr20"
							/>
							<div className="gam-error-msg">
								<ErrorMessage name="reference_email" />
							</div>
						</div>
					</div>
				</>
			)}

			{step == 6 && (
				<>
					<IntroTextBlock />
					<div>
						<p className="gam-title p_t_10">
							{SignUpContentIcon?.stepSevenTitle}
						</p>
						<p className="gam-description p_b_20">
							{SignUpContentIcon?.stepSevenText}
						</p>
					</div>
					<InterestsField />
				</>
			)}

			{step == 7 && (
				<>
					<IntroTextBlock />
					<div className="gam-profile-img gam-input-fields">
						<p className="gam-title">
							{SignUpContentCommon?.stepUploadProfileTitle}
						</p>
						{SignUpContentCommon?.stepUploadProfileText && (
							<p className="gam-description">
								{SignUpContentCommon?.stepUploadProfileText}
							</p>
						)}

						<ProfileImgCrop />
						<div className="gam-error-msg">
							<ErrorMessage name="profile_picture" />
						</div>
					</div>
				</>
			)}

			{step == 8 && (
				<>
					<IntroTextBlock />
					<div className="gam-title m_b_20">
						{SignUpContentIcon?.stepNineTitle}
					</div>
					{SignUpContentIcon?.stepNineText && (
						<p>{SignUpContentIcon?.stepNineText}</p>
					)}
					<div className="gam-input-fields gam-profile-url-wrap">
						<div className="align-label-input" role="group">
							<div className="gam-pop-wrapper">
								<div className="gam-pop-left">
									<label htmlFor="gam-profile-url">
										{window.location.origin}/
									</label>
									<HelperPop text={SignUpContentIcon?.stepNineHelper} />
								</div>
								<div className="gam-pop-right">
									<Field name="profile_url" className="gam-profile-url-input" />
								</div>
							</div>
						</div>
						<div className="gam-error-msg">
							<ErrorMessage name="profile_url" />
						</div>
					</div>
				</>
			)}

			{step == 9 && (
				<>
					<IntroTextBlock />
					<div className="gam-input-fields">
						<div className="gam-title m_b_20">
							{SignUpContentCommon?.termsTitle}
						</div>
						{SignUpContentCommon?.termsText && (
							<p>{SignUpContentCommon?.termsText}</p>
						)}
						<div role="group" className="text-left">
							<label className="gam-check-classic terms">
								<Field
									type="checkbox"
									name="terms_of_condition"
									className="hide"
								/>
								<span className="gam-instructions-text">
									{SignUpContentCommon?.termsLabel}{' '}
									<a
										className="primary_link underline"
										target="_blank"
										rel="noreferrer"
										href={`${window.location.origin}/terms-and-privacy`}
									>
										Terms & Conditions
									</a>
									. {SignUpContentCommon?.termsClosingText}
								</span>
							</label>
						</div>
						<div className="gam-error-msg">
							<ErrorMessage name="terms_of_condition" />
						</div>
					</div>
				</>
			)}
		</div>
	);
}
