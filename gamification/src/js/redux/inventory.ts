import { createSlice } from '@reduxjs/toolkit';
import { getXrApi } from '../api/apiBridge';
import createDebouncedAsyncAction from './async/createDebouncedAsyncAction';
import xrAction from './async/xrAction';

export const getItemInventory = createDebouncedAsyncAction(
	'inventory/getItemInventory',
	xrAction(getXrApi().Client.GetItemInventory)
);

const initialState: IXRInventoryItemParsedData[] = [];

const inventory = createSlice({
	name: 'inventory',
	reducers: {
		deleteItem: (state, action: { payload: string }) => {
			return state.filter(
				(item) => item.playfab.ItemInstanceId !== action.payload
			);
		},
		changeItemRemainingUses: (state, action) => {
			const { itemId, amount } = action.payload;

			const item = state.find((i) => i.itemId === itemId);

			if (item) {
				item.playfab.RemainingUses += amount;
			}
		},
	},
	extraReducers: (builder) => {
		builder.addCase(getItemInventory.actions.fulfilled, (state, action) => {
			return (action.payload.data?.items || []).map((item) => ({
				...item,
				data: {
					...item.privateData,
					...item.publicData,
				},
			}));
		});
	},
	initialState,
});

export default inventory;

export const { changeItemRemainingUses, deleteItem } = inventory.actions;
