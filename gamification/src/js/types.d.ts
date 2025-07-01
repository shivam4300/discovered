type OverrideItem = IXRInventoryItemParsedData & {
	data: {
		GlobalVariableName: string;
		Value: any;
	};
};

type Badge = IXRInventoryItemParsedData & {
	data: {
		Rarity: string;
		StatName: string;
		Threshold: number;
	};
	isInInventory: boolean;
};
