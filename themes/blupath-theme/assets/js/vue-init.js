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
            this.view = 'list';
        },
        getInitRootParams: function() {
            let uri = window.location.search.substring(1);
            let params = new URLSearchParams(uri);
            this.type = params.get("type");
            this.name = params.get("name");
            this.locale = this.$refs.locale.value;
        },
        switchView: function() {
            if (this.view === "list") {

                var item_ids = '';
                $('[name="model_id"]').each(function (index, item) {
                    item_ids += $(item).val() + ','
                })
                if (item_ids.length > 0) {
                    item_ids = item_ids.substring(0, item_ids.length - 1);
                    $('#model_ids').val(item_ids);
                }

                this.view = "map";
                Vue.nextTick(function () {
                    mapComponentInit();
                })
            } else {
                this.view = "list";
            }
        }
    },
    mounted() {
        this.getInitRootParams();
    }
});
