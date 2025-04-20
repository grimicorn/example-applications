<template>
    <div class="navbar-dropdown" v-if="menuItems.length > 0">
        <i
        class="fa fa-chevron-down navbar-dropdown-activator"
        aria-hidden="true"
        @click="toggle"></i>

        <ul
        class="navbar-dropdown-list list-unstyled"
        v-show="isOpen">
            <li v-for="item in menuItems">
                <a :href="item.href">
                    <i v-if="item.iconClass" :class="item.iconClass"></i>
                    {{ item.label }}
                </a>
            </li>
        </ul>
    </div>
</template>
<script>
    module.exports = {
        name: 'app-navbar-dropdown',

        data() {
            return {
                isOpen: false,
            };
        },

        methods: {
            toggle() {
                this.isOpen = !this.isOpen;
            },
        },

        props: {
            menuItems: {
                type: Array,
                default() {
                    return [];
                },
            },
        },
    };
</script>
