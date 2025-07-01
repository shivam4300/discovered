function HelperPop({ text }) {
	return (
		<>
			<div className="gam-helper-toggle">
				<span>&#63;</span>
			</div>
			<div className="gam-helper-pop">
				<span className="gam-helper-pop-content">{text}</span>
			</div>
		</>
	);
}

export default HelperPop;
