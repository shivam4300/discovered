import { useState, useEffect } from 'react';
import useGlobalVariables from '../../hooks/useGlobalVariables';
import IntroTextBlock from './introTextBlock';
import { useFormikContext, Field, ErrorMessage } from 'formik';
import DatepickerField from './datePicker';
import MultiSelectField from './multiSelectField';
import SingleSelectField from './singleSelectField';
import PhoneNumberField from './phoneNumberField';
import ProfileImgCrop from './ProfileImgCrop';
import HelperPop from './helperPopUp';
import InterestsField from './interestsField';

export default function FormBrandType({ step }) {
	const { SignUpContentBrand, SignUpContentCommon } = useGlobalVariables();
	const { setFieldValue } = useFormikContext();
	const [country, setCountry] = useState([]);
	const [region, setRegion] = useState([]);
	const [brandType, setBrandType] = useState([]);
	const [brandLevel, setBrandLevel] = useState([]);

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
		var requestOptions: RequestInit = {
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

	const getBrandType = async () => {
		const response = await fetch(`/api/v4/ArtistCategory?parent_id=3`);
		const json = await response.json();

		setBrandType(json.data);
	};

	const getBrandLevel = async () => {
		const response = await fetch(`/api/v4/level?cate_id=3`);
		const json = await response.json();

		setBrandLevel(json.data);
	};

	const handleCountryChange = (option) => {
		setFieldValue('country', option.value);
		setFieldValue('city', '');
		getStateByCountry(option.value);
	};

	useEffect(() => {
		getBrandLevel();
		getBrandType();
		getCountry();
		getStateByCountry(192);
	}, []);

	return (
		<div>
			{step == 0 && (
				<>
					<div className="gam-header-logo">
						<img src={SignUpContentCommon?.formDiscoveredLogo} alt="logo" />
					</div>
					<IntroTextBlock />
					<div className="gam-input-fields">
						<p className="gam-title">{SignUpContentBrand?.stepOneTitle}</p>
						{SignUpContentBrand?.stepOneText && (
							<p className="gam-description">
								{SignUpContentBrand?.stepOneText}
							</p>
						)}
						<div className="gam-field-row text-left">
							<p className="gam-field-label">Select Brand Type(s):</p>
							<Field
								name="brand_type"
								component={MultiSelectField}
								placeholder="Search type"
								className="gam-custom-select"
								classNamePrefix="gam-select"
								options={[
									...brandType?.map((bt) => {
										return { value: bt.category_id, label: bt.category_name };
									}),
								]}
							/>
							<div className="gam-error-msg">
								<ErrorMessage name="brand_type" />
							</div>
						</div>

						<div className="gam-field-row text-left">
							<p className="gam-field-label">Brand level:</p>
							<Field
								name="brand_level"
								component={SingleSelectField}
								placeholder="Brand level"
								className="gam-custom-select"
								classNamePrefix="gam-select"
								onChange={(option) =>
									setFieldValue('brand_level', option.value)
								}
								options={[
									...brandLevel?.map((bl) => {
										return { value: bl.level_id, label: bl.level_name };
									}),
								]}
							/>
							<div className="gam-error-msg">
								<ErrorMessage name="brand_level" />
							</div>
						</div>
					</div>
				</>
			)}

			{step == 1 && (
				<>
					<IntroTextBlock />
					<div className="gam-input-fields text-left">
						<div className="gam-field-row">
							<p className="gam-field-label">Date of birth:</p>
							<div className="gam-field-row">
								<div className="gam-signup-calender-wrap">
									<DatepickerField
										placeholderText={'Select Date Of Birth'}
										className="gam-calender-wrap gam-filed h50 w-100 text-left plr20"
										name="date_of_birth"
									/>
									<span className="gam-calender-icon d">
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

			{step == 2 && (
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
								name="state"
								component={SingleSelectField}
								placeholder="Select state"
								className="gam-custom-select"
								classNamePrefix="gam-select"
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
							<Field name="city" className="gam-filed h50 w-100 plr20" />
							<div className="gam-error-msg">
								<ErrorMessage name="city" />
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
								<ErrorMessage name="zip" />
							</div>
						</div>

						<div className="gam-field-row">
							<p className="gam-field-label">Phone number:</p>
							<PhoneNumberField name="phone" />
							{/* <div className="gam-error-msg">
								<ErrorMessage name="phone" />
							</div> */}
						</div>
					</div>
				</>
			)}

			{step == 4 && (
				<>
					<IntroTextBlock />
					<div>
						<p className="gam-title p_t_10">
							{SignUpContentCommon?.stepInterestTitle}
						</p>
						<p className="gam-description p_b_20">
							{SignUpContentCommon?.stepInterestText}
						</p>
					</div>
					<InterestsField />
				</>
			)}

			{step == 5 && (
				<>
					<IntroTextBlock />
					<div className="gam-profile-img  gam-input-fields">
						<p className="gam-title">
							{SignUpContentCommon?.stepUploadProfileTitle}
						</p>
						<p className="gam-description">
							{SignUpContentCommon?.stepUploadProfileText}
						</p>
						<ProfileImgCrop />
						<div className="gam-error-msg">
							<ErrorMessage name="profile_picture" />
						</div>
					</div>
				</>
			)}

			{step == 6 && (
				<>
					<IntroTextBlock />
					<div className="gam-title m_b_20">
						{SignUpContentCommon?.stepProfileUrlTitle}
					</div>
					{SignUpContentCommon?.stepProfileUrlTitle && (
						<p>{SignUpContentCommon?.stepProfileUrlText}</p>
					)}
					<div className="gam-input-fields gam-profile-url-wrap">
						<div className="align-label-input" role="group">
							<div className="gam-pop-wrapper">
								<div className="gam-pop-left">
									<label htmlFor="gam-profile-url">
										{window.location.origin}/
									</label>
									<HelperPop text={SignUpContentCommon?.stepProfileUrlHelper} />
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

			{step == 7 && (
				<>
					<IntroTextBlock />
					<div className="gam-input-fields">
						<p className="gam-title">{SignUpContentCommon?.termsTitle}</p>
						{SignUpContentCommon?.termsText && (
							<p className="gam-description">
								{SignUpContentCommon?.termsText}
							</p>
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
