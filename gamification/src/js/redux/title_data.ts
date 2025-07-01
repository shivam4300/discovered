import { createSlice } from '@reduxjs/toolkit';
import { playfabClientApi } from '../api/apiBridge';
import createDebouncedAsyncAction from './async/createDebouncedAsyncAction';

const defaultTitleData = {
	xp_levels: [] as number[],
	viewer_types: [] as string[],
};

type ITitleDataState = typeof defaultTitleData;

export const getTitleData = createDebouncedAsyncAction(
	'playfab/getTitleData',
	() => {
		return playfabClientApi('GetTitleData', {});
	},
);

// eslint-disable-next-line @typescript-eslint/naming-convention
const title_data = createSlice({
	name: 'title_data',
	reducers: {},
	extraReducers: (builder) => {
		builder.addCase(getTitleData.actions.fulfilled, (state:ITitleDataState, action) => {
			const data:{ [key:string]: string } = action.payload.data.Data;

			Object.entries(data).forEach(([key, value]) => {
				const json = JSON.parse(value);
				if (json) {
					data[key] = json;
				}
			});

			return {
				...state,
				...data,
			};
		});
	},
	initialState: defaultTitleData,
});

export default title_data;
