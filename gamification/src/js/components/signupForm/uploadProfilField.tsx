import { useEffect, useState } from 'react';
import { useFormikContext } from 'formik';

function UploadProfilField() {
	const [profilPreview, setProfilPreview] = useState(null);
	const { values, setFieldValue } = useFormikContext();

	const handleRemoveImage = () => {
		setFieldValue('profile_picture', '');
		setProfilPreview(null);
	};

	useEffect(() => {
		let objectUrl;

		if (values.profile_picture) {
			objectUrl = URL.createObjectURL(values.profile_picture);
			setProfilPreview(objectUrl);
		}

		return () => URL.revokeObjectURL(objectUrl);
	}, [values.profile_picture]);

	return (
		<>
			<div className="gam-custom-file-input">
				<label htmlFor="gam-file-input">
					<span>
						If you put a profile picture you will unlock a secret badge
					</span>
					<div>
						{profilPreview ? (
							<>
								<img src={profilPreview} />
								<button type="button" onClick={() => handleRemoveImage()}>
									Remove
								</button>
							</>
						) : (
							<img
								src="https://yhpwebsites-assets.s3.us-east-2.amazonaws.com/discovered/upload-img.png"
								alt="upload icon"
							/>
						)}
					</div>
				</label>
				<input
					id="gam-file-input"
					name="file"
					type="file"
					onChange={(event) => {
						setFieldValue('profile_picture', event.currentTarget.files[0]);
					}}
				/>
			</div>
		</>
	);
}

export default UploadProfilField;
