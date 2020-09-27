window.Vue = require("vue");

Vue.component("save-favorites-button", require("./SaveFavoritesButton.vue").default);
Vue.component("favorites-table", require("./FavoritesTable.vue").default);

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
        initMaterialControls() {
            $('#available_since').bootstrapMaterialDatePicker({
                format: 'DD.MM.YYYY',
                time: false,
                weekStart: 1,
                clearButton: true
            });
            $('#collapse_form').on('shown.bs.collapse', function () {
                $('#expand_collapse_icon').text('expand_less');
            });
            $('#collapse_form').on('hidden.bs.collapse', function () {
                $('#expand_collapse_icon').text('expand_more');
            })


            // Material Select
            // MAD-SELECT
            var madSelectHover = 0;
            $(".mad-select").each(function() {
              var $input = $(this).find("input"),
                  $ul = $(this).find("> ul"),
                  $ulDrop =  $ul.clone().addClass("mad-select-drop");

              $(this)
                .append('<i class="material-icons">arrow_drop_down</i>', $ulDrop)
                .on({
                hover : function() { madSelectHover ^= 1; },
                click : function() { $ulDrop.toggleClass("show"); }
              });

              // PRESELECT
              $ul.add($ulDrop).find("li[data-value='"+ $input.val() +"']").addClass("selected");

              // MAKE SELECTED
              $ulDrop.on("click", "li", function(evt) {
                evt.stopPropagation();
                $input.val($(this).data("value")); // Update hidden input value
                $ul.find("li").eq($(this).index()).add(this).addClass("selected")
                  .siblings("li").removeClass("selected");
              });
              // UPDATE LIST SCROLL POSITION
              $ul.on("click", function() {
                var liTop = $ulDrop.find("li.selected").position().top;
                $ulDrop.scrollTop(liTop + $ulDrop[0].scrollTop);
              });
            });

            $(document).on("mouseup", function(){
              if(!madSelectHover) $(".mad-select-drop").removeClass("show");
            });
        },
        setTab: function(tab) {
            this.tab = tab;
            this.view = 'list';
            var self = this;
            //https://designlink.work/en-US/bootstrap-material-datepicker/
            //https://github.com/AlexandrM/bootstrap-material-datetimepicker#readme
            if (tab === 'opportunities') {
                Vue.nextTick(function () {
                    self.initMaterialControls();
                });
            }
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

                var self = this;
                Vue.nextTick(function () {
                    self.initMaterialControls();
                })
            }
        }
    },
    mounted() {
        this.getInitRootParams();
    }
});
