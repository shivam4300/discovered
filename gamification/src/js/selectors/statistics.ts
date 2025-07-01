import { createSelector } from '@reduxjs/toolkit';
import { IRootState } from '../redux/config/store';

export const statsSelector = (state:IRootState) => state.statistics;

export const xpSelector = createSelector([
	statsSelector,
], (stats) => {
	return stats?.xp ?? 0;
});