import React, { useState, useRef, useEffect } from 'react';
import ReactCrop, {
	centerCrop,
	makeAspectCrop,
	Crop,
	PixelCrop,
} from 'react-image-crop';
import { CanvasPreview } from './canvasPreview';
import { UseDebounceEffect } from './useDebounceEffect';
import 'react-image-crop/dist/ReactCrop.css';
import { useFormikContext } from 'formik';

function centerAspectCrop(
	mediaWidth: number,
	mediaHeight: number,
	aspect: number
) {
	return centerCrop(
		makeAspectCrop(
			{
				unit: '%',
				width: 90,
			},
			aspect,
			mediaWidth,
			mediaHeight
		),
		mediaWidth,
		mediaHeight
	);
}

export default function ProfileImgCrop() {
	const [imgSrc, setImgSrc] = useState('');
	const previewCanvasRef = useRef<HTMLCanvasElement>(null);
	const imgRef = useRef<HTMLImageElement>(null);
	const blobUrlRef = useRef('');
	const [crop, setCrop] = useState<Crop>();
	const [completedCrop, setCompletedCrop] = useState<PixelCrop>();
	const [scale, setScale] = useState(1);
	const [rotate, setRotate] = useState(0);
	const [aspect, setAspect] = useState<number | undefined>(1 / 1);
	const { values, setFieldValue } = useFormikContext();
	const [hideInputField, setHideInputField] = useState(false);
	const [previewImage, setPreviewImage] = useState(null);

	function handleDragOver(e: React.DragEvent<HTMLLabelElement>) {
		e.preventDefault();
		e.stopPropagation();
	}

	function handleDragEnter(e: React.DragEvent<HTMLLabelElement>) {
		e.preventDefault();
		e.stopPropagation();
	}

	function handleDragLeave(e: React.DragEvent<HTMLLabelElement>) {
		e.preventDefault();
		e.stopPropagation();
	}

	function handleDrop(e: React.DragEvent<HTMLLabelElement>) {
		e.preventDefault();
		e.stopPropagation();

		if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
			setHideInputField(true);
			setCrop(undefined); // Makes crop preview update between images.
			const reader = new FileReader();
			reader.addEventListener('load', () =>
				setImgSrc(reader.result?.toString() || '')
			);
			reader.readAsDataURL(e.dataTransfer.files[0]);
		}
	}

	function onSelectFile(e: React.ChangeEvent<HTMLInputElement>) {
		if (e.target.files && e.target.files.length > 0) {
			setHideInputField(true);
			setCrop(undefined); // Makes crop preview update between images.
			const reader = new FileReader();
			reader.addEventListener('load', () =>
				setImgSrc(reader.result?.toString() || '')
			);
			reader.readAsDataURL(e.target.files[0]);
		}
	}

	function onImageLoad(e: React.SyntheticEvent<HTMLImageElement>) {
		if (aspect) {
			const { width, height } = e.currentTarget;
			setCrop(centerAspectCrop(width, height, aspect));
		}
	}

	function handleCropChange() {
		if (!previewCanvasRef.current) {
			throw new Error('Crop canvas does not exist');
		}

		previewCanvasRef.current.toBlob(
			(blob) => {
				if (!blob) {
					throw new Error('Failed to create blob');
				}

				if (blobUrlRef.current) {
					URL.revokeObjectURL(blobUrlRef.current);
				}

				blobUrlRef.current = URL.createObjectURL(blob);
				setPreviewImage(blobUrlRef.current);

				const reader = new FileReader();
				reader.onloadend = function () {
					setFieldValue('profile_picture', reader.result); // reader.result is base64
				};
				reader.readAsDataURL(blob);
			},
			'image/png',
			1
		);
	}

	function handleRemoveImage() {
		setFieldValue('profile_picture', '');
		setHideInputField(false);
	}

	UseDebounceEffect(
		async () => {
			if (
				completedCrop?.width &&
				completedCrop?.height &&
				imgRef.current &&
				previewCanvasRef.current
			) {
				// We use canvasPreview as it's much faster than imgPreview.
				CanvasPreview(
					imgRef.current,
					previewCanvasRef.current,
					completedCrop,
					scale,
					rotate
				).then(() => {
					handleCropChange();
				});
			}
		},
		100,
		[completedCrop, scale, rotate]
	);

	return (
		<div className='gam-custom-file-input'>
			{!hideInputField && (
				<div className='upload-controls'>
					<label
						className='gam-sign-upload-box'
						htmlFor='gam-file-input'
						onDragOver={handleDragOver}
						onDragEnter={handleDragEnter}
						onDragLeave={handleDragLeave}
						onDrop={handleDrop}
					>
						<div className='gam-sign-upload-box-left'>
							{values.profile_picture ? (
								<>
									<img src={values.profile_picture} alt='Profile img' />
								</>
							) : (
								<img
									src={`${window.location.origin}/repo/images/gamification/dummy_profile.svg`}
									alt='upload icon'
								/>
							)}
						</div>
						<div className='gam-sign-upload-box-right'>
							<p className='gam-sign-upload-ttl mb_0'>
								Drop your profile image here.
							</p>
							<p>
								Or <span className='primary_link'> Browse Image</span>
							</p>
						</div>
					</label>
					<input
						id='gam-file-input'
						name='file'
						type='file'
						accept='image/png, image/jpeg, image/jpg'
						onChange={onSelectFile}
					/>
					<p className='gam-file-size'>
						The Best Image Size Is 246x246 (WxH)<sup>*</sup>
					</p>
				</div>
			)}

			{!!imgSrc && hideInputField && (
				<ReactCrop
					crop={crop}
					onChange={(_, percentCrop) => setCrop(percentCrop)}
					onComplete={(c) => setCompletedCrop(c)}
					aspect={aspect}
				>
					<img ref={imgRef} alt='Crop me' src={imgSrc} onLoad={onImageLoad} />
				</ReactCrop>
			)}
			{!!completedCrop && hideInputField && (
				<>
					<div>
						<canvas
							ref={previewCanvasRef}
							style={{
								position: 'absolute',
								top: '-200vh',
								visibility: 'hidden',
								border: '1px solid black',
								objectFit: 'contain',
								width: completedCrop.width,
								height: completedCrop.height,
							}}
						/>
					</div>
					<div>
						<button onClick={handleRemoveImage} className='gam-remove-img-btn'>
							Remove image
						</button>
					</div>
				</>
			)}
		</div>
	);
}
