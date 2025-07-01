type ProgressBarProps = {
	step: number;
	totalSteps: number;
};

export default function TutorialProgressBar({
	step,
	totalSteps,
}: ProgressBarProps) {
	const renderSteps = (currentStep, steps) => {
		const stepsArray = [];

		for (let i = 1; i <= steps; i++) {
			stepsArray.push(
				<div
					className={`gam-tutorial-progress-step gam-back-grey ${
						currentStep === i ? "gam-active-step" : ""
					}`}
					key={i}
				/>
			);
		}

		return <div className="gam-tutorial-progress-wrapper">{stepsArray}</div>;
	};

	return (
		<>
			<div className="gam-tutorial-progress-bar">
				{renderSteps(step, totalSteps)}
			</div>
		</>
	);
}
