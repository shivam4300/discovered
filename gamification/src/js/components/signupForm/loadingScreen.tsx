import PuffLoader from 'react-spinners/PuffLoader';

function LoadingScreen({ isSubmitting }) {
	return (
		<>
			{isSubmitting && (
				<div className="gam-loading-screen">
					<PuffLoader color="#36d7b7" loading size={75} speedMultiplier={1} />
				</div>
			)}
		</>
	);
}

export default LoadingScreen;
