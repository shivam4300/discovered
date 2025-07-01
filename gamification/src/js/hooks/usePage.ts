import useGlobalVariables from "./useGlobalVariables";

export default function usePage(pageId:string) {
	const { pages } = useGlobalVariables();
	return pages.find(p => p.id === pageId);
}