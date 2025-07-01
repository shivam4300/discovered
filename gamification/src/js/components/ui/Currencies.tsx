import React from 'react';
import useCurrencies from '../../hooks/useCurrencies';

type CurrenciesProps = {
	showTestButtons?: boolean;
};

export default function Currencies({ showTestButtons = false }: CurrenciesProps) {
	const { wallet, grantCurrency } = useCurrencies();

	return (
		<div className="currencies">
			<div className="currencies-listing">
				{
					Object.entries(wallet).map(([currencyId, currency]) => (
						<div className="currency-listing-item" key={currencyId} title={currencyId}>
							<img src={currency.icon} alt="" /><span>{currency.amount}</span>
						</div>
					))
				}
			</div>

			{
				showTestButtons && (
					<div className="actions">
						{
							Object.entries(wallet).map(([currencyId, amount]) => (
								<button key={currencyId} className="button" onClick={() => grantCurrency(currencyId)}>Give 100 {currencyId}</button>
							))
						}
					</div>
				)
			}
		</div>
	)
}