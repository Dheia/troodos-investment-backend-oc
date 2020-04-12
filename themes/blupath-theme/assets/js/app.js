document.addEventListener("DOMContentLoaded", () => {
    new Mmenu("#menu", {
        extensions: ["pagedim-black"],
        iconPanels: true,
        sidebar: {
            collapsed: "(min-width: 550px)"
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
    new Mmenu("#search", {
        extensions: ["popup"]
    });
});