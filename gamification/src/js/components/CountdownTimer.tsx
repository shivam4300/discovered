import Countdown from 'react-countdown';

function CountdownTimer({ countdownDate }) {
	const renderer = ({ days, hours, minutes, seconds, completed }) => {
		if (completed) {
			return <div>Countdown over!</div>;
		} else {
			return (
				<div className="gam-countdown">
					<span>{days.toString().padStart(2, '0')}</span> :{' '}
					<span>{hours.toString().padStart(2, '0')}</span> :{' '}
					<span>{minutes.toString().padStart(2, '0')}</span> :{' '}
					<span>{seconds.toString().padStart(2, '0')}</span>
				</div>
			);
		}
	};

	return <Countdown date={countdownDate} renderer={renderer} zeroPadTime={2} />;
}

export default CountdownTimer;
