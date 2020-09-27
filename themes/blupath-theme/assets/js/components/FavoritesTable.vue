<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Type</th>
                <th scope="col">Investment</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="favorite in favorites" :key="favorite.id">
                <td>
                    <strong>
                        <a
                            :href="
                                '/investment-platform/opportunity/' +
                                    favorite.slug
                            "
                            v-text="favorite.name"
                            class="primary-text"
                        >
                        </a>
                    </strong>
                </td>
                <td>
                    <span
                        v-for="business_type in favorite.business_types"
                        :key="business_type.id"
                    >
                        {{ business_type.name }}
                    </span>
                </td>
                <td v-if="favorite.show_target">
                    €{{ favorite.investment_target }}
                </td>
                <td v-else>N/A</td>
                <td>
                    <i
                        class="fa fa-trash"
                        style="cursor: pointer;"
                        data-toggle="modal"
                        :data-target="'#' + favorite.slug + 'Modal'"
                    ></i>
                </td>

                <div
                    class="modal fade"
                    :id="favorite.slug + 'Modal'"
                    tabindex="-1"
                    role="dialog"
                    aria-hidden="true"
                >
                    <div
                        class="modal-dialog modal-lg absolute-center"
                        role="document"
                        style="max-width: 1200px"
                    >
                        <div class="modal-content px-2 py-2">
                            <div class="modal-header">
                                <h5 class="modal-title">Remove?</h5>
                                <button
                                    type="button"
                                    class="close"
                                    data-dismiss="modal"
                                    aria-label="Close"
                                >
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div style="font-size: 0.9em">
                                    Are you sure you want to stop following this
                                    opportunity?
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a
                                    class="btn btn-flat btn-outline-accent"
                                    data-dismiss="modal"
                                    >Close</a
                                >
                                <a
                                    class="btn btn-flat btn-outline-accent"
                                    data-dismiss="modal"
                                    @click="removeFavorite(favorite.id)"
                                    >Delete</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </tr>
        </tbody>
    </table>
</template>

<script>
import axios from "axios";

export default {
    name: "Favorites-Table",
    props: ["labels"],
    data: function() {
        return {
            favorites: [],
        };
    },
    computed: {
        locale: function() {
            return document.getElementById("activeLocale").value;
        }
    },
    methods: {
        getFavorites: function() {
            axios.get("/api/favorites/", {locale: this.locale}).then(response => {
                this.favorites = response.data.favorites;
            });
        },
        removeFavorite: function(ioid) {
            axios.post("/api/favorites/", { ioid }).then(response => {
                this.getFavorites();
            });
        }
    },
    created() {
        this.getFavorites();
        console.log(this.locale);
    }
};
</script>
