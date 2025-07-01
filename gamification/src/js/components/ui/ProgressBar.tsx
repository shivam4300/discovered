import useGlobalVariables from '../../hooks/useGlobalVariables';

type ProgressBarProps = {
	step: number;
	totalSteps: number;
};

export default function ProgressBar({ step, totalSteps }: ProgressBarProps) {
	const { SignUpContentCommon } = useGlobalVariables();
	const progress = Math.floor((step / totalSteps) * 100);

	return (
		<>
			<div className="gam-progress-bar gam-back-light-grey">
				<div
					className="gam-progress gam-bg-green"
					style={{ width: `${progress}%` }}
				/>
				<span className="gam-progress-gift-icon">
					<img src={SignUpContentCommon.formProgressionIcon} alt="gift"/>
				</span>
			</div>
		</>
	);
}
