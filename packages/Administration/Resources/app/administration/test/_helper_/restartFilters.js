export default function restartFilters() {
    const filterRegistry = SnapAdmin.Filter.getRegistry();
    filterRegistry.forEach((value, key) => {
        global.Vue.filter(key, value);
    });
}
