import { createSlice, createAction } from '@reduxjs/toolkit';
import { DEFAULT_LANG, CDN_BASE } from '../Constants';
import { getQueryParameterByName } from '../utils/urlUtils';
import { getJSON } from '../api/api';
import createDebouncedAsyncAction from './async/createDebouncedAsyncAction';

type IEnvState = typeof initialState;

export const incrementErrors = createAction<number[]>('env/error');

export const getTexts = createDebouncedAsyncAction<IEnvState>(
	'env/getTexts',
	() => {
		return getJSON(CDN_BASE + '/assets/texts.json?v=' + Date.now());
	}
);

const initialState = {
	Lang: getQueryParameterByName('lang') || DEFAULT_LANG,
	Errors: [] as number[],
	lastUpdate: Date.now(),
};

const env = createSlice({
	name: 'env',
	reducers: {
	},
	extraReducers: (builder) => {
		builder.addCase(getTexts.actions.fulfilled, (state, action) => {
			return {
				...state,
				Texts: action.payload,
			};
		});
		builder.addCase(incrementErrors, (state) => {
			return {
				...state,
				Errors: [
					...state.Errors,
					Date.now(),
				],
			};
		});
	},
	initialState,
});

export default env;
