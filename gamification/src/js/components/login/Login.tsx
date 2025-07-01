import React from 'react';
import useLoadingScreen from '../../hooks/useLoadingScreen';
import Logo from '../ui/Logo';
import useLogin from './hooks/useLogin';

function Login() {
	const {
		loginWithCustomId,
		loginAsNewUser,
		loginAsDemoUser,
	} = useLogin();

	const { toggleLoadingScreen } = useLoadingScreen();

	async function onLogin(type:string, customId?:string) {
		toggleLoadingScreen();

		let fn = async () => {};
		switch (type) {
			case 'demo':
				fn = loginAsDemoUser;
				break;
			case 'new':
				fn = loginAsNewUser;
				break;
			case 'custom':
				fn = () => loginWithCustomId(customId);
				break;
		}

		await fn();
	}

	const [customId, setCustomId] = React.useState<string>('');

	return (
		<div className="login">
			<div className="container">
				<div className="row">
					<div className="col">
						<h1>XR Server</h1>

						<h3>Login as a</h3>
						<div className="easy-login-actions">
							<button className="button" onClick={() => onLogin('demo')}>Demo user</button>
							<button className="button" onClick={() => onLogin('new')}>New user</button>
						</div>

						<div className="existing-user">
							<h3>Or an existing user</h3>

							<div className="existing-user-actions">
								<input type="text" onChange={(e) => setCustomId(e.currentTarget.value)} placeholder="Custom id" />
								<button className="button" onClick={() => onLogin('custom', customId)}>Login</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	);
}

export default Login;