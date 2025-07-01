import { useEffect } from 'react';
import { getCookie, setCookie } from './utils/cookies';

function ModalManager() {
	useEffect(() => {
		const modalHDYDU = document.querySelector('.gam-modal-HowIComeONDis');
		const cookiehdydu = getCookie('hdydu_closed');

		let mcTimer = setInterval(() => {
			const cookieMCpop = getCookie('MCPopupClosed');
			const cookieMCpopSub = getCookie('MCPopupSubscribed');

			if (cookieMCpop === 'yes' || cookieMCpopSub == 'yes') {
				modalHDYDU && modalHDYDU.classList.remove('hide');
				modalHDYDU && modalHDYDU.classList.add('in');
				modalHDYDU && modalHDYDU.classList.add('show');

				clearInterval(mcTimer);
			}
		}, 1000);

		const hdyduBtns = document.querySelectorAll('.gam-hdydu-event');

		hdyduBtns.forEach((hdyduBtn) => {
			hdyduBtn.addEventListener('click', () => {
				setCookie('hdydu_closed', 'yes');
				modalHDYDU && modalHDYDU.classList.remove('show');
				modalHDYDU && modalHDYDU.classList.add('hide');
			});
		});

		return () => {
			clearInterval(mcTimer);
			hdyduBtns.forEach((hdyduBtn) => {
				hdyduBtn.removeEventListener('click', () => {});
			});
		};
	}, []);

	return <div />;
}

export default ModalManager;
