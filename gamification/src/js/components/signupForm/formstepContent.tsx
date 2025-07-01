import { Suspense, lazy } from 'react';

const IconType = lazy(() => import('./formIconType'));
const EmergingType = lazy(() => import('./formEmergingType'));
const FanType = lazy(() => import('./formFanType'));
const BrandType = lazy(() => import('./formBrandType'));

export default function FormStepContent({ step, type }) {
	const renderStepContent = (step, type) => {
		switch (type) {
			case 'icon':
				return <IconType step={step} />;
			case 'emerging':
				return <EmergingType step={step} />;
			case 'brand':
				return <BrandType step={step} />;
			case 'fan':
				return <FanType step={step} />;
			default:
				return <div>Not Found</div>;
		}
	};

	return (
		<Suspense fallback={<div>Loading...</div>}>
			{renderStepContent(step, type)}
		</Suspense>
	);
}
