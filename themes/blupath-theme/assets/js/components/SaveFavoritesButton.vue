<template>
    <div>
        <p class="text-center" v-if="isFavorite">
            <strong
                >You have saved this opportunity in your
                <a href="/investor" class="btn-outline-accent btn-flat"
                    >Investor profile</a
                ></strong
            >
        </p>
        <a
            class="btn btn-outline-accent btn-flat"
            v-if="loggedIn"
            v-text="btnText"
            @click="toggleFavorite"
            :disabled="disabled"
        ></a>
        <a class="btn btn-outline-accent btn-flat">Email</a>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "Save-Favorites-Button",
    data: function() {
        return {
            disabled: false,
            isFavorite: false,
            loggedIn: false,
            name: ""
        };
    },
    computed: {
        btnText: function() {
            return this.isFavorite ? "Remove" : "Save";
        }
    },
    methods: {
        ioid: function() {
            return document.getElementById("ioid").value;
        },
        toggleFavorite: function() {
            this.disabled = true;
            axios
                .post("/api/favorites/", { ioid: this.ioid() })
                .then(response => {
                    this.isFavorite = response.data.isFavorite;
                    this.disabled = false;
                })
                .catch(error => {
                    this.disabled = false;
                });
        }
    },
    created() {
        axios
            .post("/api/favorites/check", { ioid: this.ioid() })
            .then(response => {
                this.loggedIn = true;
                this.isFavorite = response.data.check ? true : false;
            })
            .catch(error => {
                this.loggedIn = false;
            });
    }
};
</script>
