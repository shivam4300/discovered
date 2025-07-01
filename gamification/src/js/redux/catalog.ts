import { createSlice } from '@reduxjs/toolkit';
import { getXrApi } from '../api/apiBridge';
import createDebouncedAsyncAction from './async/createDebouncedAsyncAction';
import xrAction from './async/xrAction';

const initialState = {
	items: [] as IXRInventoryItemParsedData[],
};

export const getCatalog = createDebouncedAsyncAction(
	'playfab-xr/getItemCatalog',
	xrAction(() => getXrApi().Client.GetItemCatalog())
);

const catalog = createSlice({
	name: 'catalog',
	reducers: {},
	extraReducers: (builder) => {
		builder.addCase(getCatalog.actions.fulfilled, (state, action) => {
			return {
				...state,
				items: (action.payload?.data?.Catalog || []).map((item) => ({
					...item,
					data: {
						...item.publicData,
					},
				})),
			};
		});
	},
	initialState,
});

export default catalog;