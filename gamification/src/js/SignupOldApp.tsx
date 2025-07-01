import { useState } from "react";

export default function SignUpApp() {
	const num = 3;
	const [steps, setSteps] = useState([
		'Personal info',
		'Account info',
		'Confirm',
	]);

	const removeStep = () => {
		const newSteps = [...steps];
		newSteps.shift();
		setSteps(newSteps);
	}

	return (
		<div className="signup-app">
			<h1>Sign up form...</h1>

			<div className="steps">{(num - steps.length + 1)} / {num}</div>

			<h2>{steps?.[0]}</h2>

			{steps.length > 1 && <button onClick={removeStep}>Next Step</button>}
		</div>
	)
}