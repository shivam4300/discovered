import { getCatalog } from "../redux/catalog";
import { useEffect } from 'react';
import { useAppDispatch, useAppSelector } from "../redux/config/store";

export default function useCatalog() {
	const {
		items,
	} = useAppSelector((state) => state.catalog);

	const dispatch = useAppDispatch();

	useEffect(() => {
		dispatch(getCatalog());
	}, [dispatch]);

	return {
		items,
	};
}