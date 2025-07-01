import { AsyncThunkPayloadCreator } from '@reduxjs/toolkit';
import { getXrApi } from '../../api/apiBridge';
import { reportApiError } from '../../utils/reportApiError';
import { IAppDispatch } from '../config/store';
import { incrementErrors } from '../env';

type Config = { dispatch:IAppDispatch, state:unknown, rejectValue:unknown };

type StackActionPromise<Returned, ThunkArgs> = (args:ThunkArgs) => Promise<Returned>;

export default function xrAction<ActionType, ThunkArgs>(
	promise:StackActionPromise<ActionType, ThunkArgs>,
):AsyncThunkPayloadCreator<ActionType, ThunkArgs, Config> {
	return (data, { dispatch, rejectWithValue }) => {
		return promise(data).then((resp:ActionType & { code: number, success: boolean, message:string }) => {
			if (resp.code === 401 || resp.code === 409) {
				getXrApi().resetAuthCookies();
				dispatch({ type: 'USER_LOGOUT' });
				console.error('LOGGED OUT');
				window.location.reload();
			} else if (!resp.success) {
				dispatch(incrementErrors());
				reportApiError('PlayfabAPI', resp);
				return rejectWithValue(resp);
			}
		
			return resp;
		});
	};
}
