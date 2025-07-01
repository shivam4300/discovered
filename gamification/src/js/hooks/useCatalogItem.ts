import useCatalog from "./useCatalog";

function useCatalogItem(itemId: string) {
	const catalog = useCatalog();
	return catalog.items.find(item => item.itemId === itemId)
}

export default useCatalogItem;