import useGlobalVariables from '../../hooks/useGlobalVariables';

export default function ModalLeaderBoardHelperContent() {
	const { WeeklyLeaderboardHelper } = useGlobalVariables();

	return (
		<>
			<h3 className="gam-modal-title">
				{WeeklyLeaderboardHelper?.topTitle} <br />
				{WeeklyLeaderboardHelper?.subTitle}
			</h3>
			<ul className="gam-s-cb-list green">
				{WeeklyLeaderboardHelper?.weeklyCreatorRules?.rules &&
					WeeklyLeaderboardHelper?.weeklyCreatorRules?.rules.map((rule, i) => {
						return (
							<li key={i}>
								<p>{rule.rule}</p>
							</li>
						);
					})}
			</ul>
		</>
	);
}
