import { combineReducers, configureStore, ThunkDispatch } from '@reduxjs/toolkit';
import { TypedUseSelectorHook, useDispatch, useSelector } from 'react-redux';
import reduxLocalStorage from 'redux-simple-localstorage';

import { AnyAction } from 'redux';
import { createLogger } from 'redux-logger';

import tracker from './middleware/tracker';

import { EXCLUDED_LOGGER_ACTIONS, STORE_NAME } from '../../Constants';
import { rootReducer } from '../rootReducer';
import { getCookie } from '../../utils/cookies';
import { getXrApi } from '../../api/apiBridge';

const { read, write } = reduxLocalStorage(STORE_NAME);

export type IRootState = ReturnType<typeof store.getState>;
export type IAppDispatch = ThunkDispatch<IRootState, any, AnyAction>;

export const useAppDispatch = () => useDispatch<IAppDispatch>();
export const useAppSelector: TypedUseSelectorHook<IRootState> = useSelector;

function filterActions(getState:() => IRootState, action:AnyAction) {
	if (EXCLUDED_LOGGER_ACTIONS.find(x => action.type.indexOf(x) >= 0)) return false;
	return true;
}

const jwt = getCookie('AuthTkn');
if (!jwt) {
	getXrApi().resetAuthCookies();
}

const props = {} as { preloadedState?: any };
const preloadedState = read();
if (jwt && preloadedState) {
	props.preloadedState = preloadedState;
}

export const store = configureStore({
	...props,
	reducer: (state, action) => {
		if (state) {
			const isExpired = !state.env.lastUpdate || state.env.lastUpdate + 1000 * 60 * 60 * 6 < Date.now(); // 6 hours cache

			if (action.type === 'USER_LOGOUT' || isExpired) {
				return rootReducer(undefined, action);
			}
		}

		return rootReducer(state, action);
	},
	middleware: (getDefaultMiddleware) => {
		const middleware = getDefaultMiddleware();

		middleware.push(createLogger({ collapsed: true, predicate: filterActions }));
		middleware.push(tracker);
		middleware.push(write);

		return middleware;
	},
});
