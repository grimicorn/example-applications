let statuses = ["success", "info", "warning", "danger"];
let whitelist = [
    "page-item",
    "pagination-page-nav",
    "active",
    "pagination",
    "page-link",
    "pagination-next-nav",
    "pagination-prev-nav"
    // info,
    // success,
    // warning,
    // danger,
];

statuses.forEach(status => {
    whitelist.push(`alert-${status}`);
});

module.exports = {
    whitelist
};
