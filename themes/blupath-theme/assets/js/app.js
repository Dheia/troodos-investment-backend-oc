document.addEventListener("DOMContentLoaded", () => {
    new Mmenu("#menu", {
        extensions: ["pagedim-black"],
        iconPanels: true,
        sidebar: {
            collapsed: "(min-width: 550px)",
        },
    });
});
