import { createSlice } from '@reduxjs/toolkit';

const initialState = {
	isModalOpen: false,
	position: 0,
};

const queueModal = createSlice({
	name: 'profileTutorialState',
	initialState,
	reducers: {},
});

export const {} = queueModal.actions;
export default queueModal;
