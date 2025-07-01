/* eslint-disable @typescript-eslint/naming-convention */
import { createSlice } from '@reduxjs/toolkit';
import { getXrApi } from '../api/apiBridge';
import createDebouncedAsyncAction from './async/createDebouncedAsyncAction';
import xrAction from './async/xrAction';

type IXrStoreState = typeof initialState;

const initialState = {
	loadout: [] as IXRStore[],
};

export const getStoreLoadout = createDebouncedAsyncAction(
	'playfab-xr/getStoreLoadout',
	xrAction(() => {
		return getXrApi().Client.GetStoreLoadout();
	})
);

const xr_store = createSlice({
	name: 'xr_store',
	reducers: {
	},
	extraReducers: (builder) => {
		builder.addCase(getStoreLoadout.actions.fulfilled, (state:IXrStoreState, action) => {
			state.loadout = [...action.payload.data.StoreLoadout];
		});
	},
	initialState,
});

export default xr_store;
