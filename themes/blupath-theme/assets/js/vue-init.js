var app = new Vue({
    el: "#app",
    data: {
        tab: "featured",
        type: "home",
        name: "all",
        view: "list",
        locale: ""
    },
    methods: {
        setTab: function(tab) {
            this.tab = tab;
        },
        getInitRootParams: function() {
            let uri = window.location.search.substring(1);
            let params = new URLSearchParams(uri);
            this.type = params.get("type");
            this.name = params.get("name");
            this.locale = this.$refs.locale.value;
        },
        switchView: function() {
            if (this.view == "list") {
                this.view = "map";
            } else {
                this.view = "list";
            }
        }
    },
    mounted() {
        this.getInitRootParams();
    }
});
