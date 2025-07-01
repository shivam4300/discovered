import { createSlice } from '@reduxjs/toolkit';
import { getXrApi } from '../api/apiBridge';
import createDebouncedAsyncAction from './async/createDebouncedAsyncAction';
import xrAction from './async/xrAction';

const defaultMissionState = {
	loaded: false,
	list: [] as IXRMissionItem[],
};
type IMissionState = typeof defaultMissionState;

export const getMissionInventory = createDebouncedAsyncAction(
	'playfab-xr/getMissionInventory',
	xrAction(getXrApi().Client.GetMissionInventory)
);

export const resetMission = createDebouncedAsyncAction(
	'playfab-xr/resetMission',
	xrAction((param) => {
		return getXrApi().Client.ResetMission({
			ItemId: param.item_id,
		});
	}),
);

const missions = createSlice({
	name: 'missions',
	reducers: {},
	extraReducers: (builder) => {
		builder.addCase(getMissionInventory.actions.fulfilled, (state:IMissionState, action) => {
			return {
				...state,
				loaded: true,
				list: [...action.payload.data.missions.PlayerMissions.map(mission => {
					mission.data = { ...mission.publicData };

					mission.objectives = (mission.objectives as IXRMissionObjective[]).map(objective => {
						return {
							...objective,
							data: Object.entries(objective.data).reduce((c, [key, value]) => {
								c[key] = value;
								return c;
							}, {}),
						}
					});
					return mission;
				})],
			};
		});
		builder.addCase(resetMission.actions.fulfilled, () => {});
	},
	initialState: defaultMissionState,
});

export default missions;