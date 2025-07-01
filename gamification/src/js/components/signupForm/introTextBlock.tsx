import useGlobalVariables from '../../hooks/useGlobalVariables';

function IntroTextBlock() {
	const { SignUpContentCommon } = useGlobalVariables();

	return (
		<>
			<div className="gam-title">
				{SignUpContentCommon.formProgressionTitle}
			</div>
			<p className="gam-description">
				{SignUpContentCommon.formProgressionText}
			</p>
		</>
	);
}

export default IntroTextBlock;
