
import React, { MouseEventHandler } from 'react';

type ItemCardProps = {
	item: IXRInventoryItemParsedData;
	isFocused?: boolean;
	isStatic?: boolean;
	onClick?: MouseEventHandler;
};

export default function ItemCard({ item, isFocused, isStatic, onClick }:ItemCardProps) {
	const classes = ['item-card'];
	if (isFocused) classes.push('focused');
	if (isStatic) classes.push('static');

	return (
		<div className={classes.join(' ')} onClick={onClick}>
			<div className="image">
				<img src={item.data?.image as string} alt={item.playfab.DisplayName} />
			</div>

			<div className="item-card-infos">{item.playfab.DisplayName}</div>

			{
				item.playfab.IsStackable && item.playfab.RemainingUses > 1 && (
					<div className="item-card-quantity">{item.playfab.RemainingUses}</div>
				)
			}
		</div>
	);
}