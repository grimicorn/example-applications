<template>
    <nav class="sidebar-navigation">
        <ul class="list-unstyled sidebar-navigation-menu">
            <li
            v-for="(item, key) in items"
            :key="item.href"
            :class="{
                'is-active' : item.isActive || key === section,
                'is-open' : item.isOpen,
            }">
                <div class="link-wrap">
                    <a
                    :href="item.href"
                    v-if="item.href"
                    class="sidebar-navigation-parent">
                        <i class="icon-left" :class="item.icon_left" aria-hidden="true" v-if="item.icon_left"></i>
                        {{ item.label }}
                        <i class="icon-right" :class="item.icon_right" aria-hidden="true" v-if="item.icon_right"></i>
                    </a>

                    <span
                    class="sidebar-navigation-parent"
                    v-else>
                        <i class="icon-left" :class="item.icon_left" aria-hidden="true"></i>
                        {{ item.label }}
                        <i class="icon-right" :class="item.icon_right" aria-hidden="true" v-if="item.icon_right"></i>
                    </span>
                </div>
            </li>
        </ul>
    </nav>
</template>
<script>
let _map = require("lodash.map");

module.exports = {
    data() {
        return {
            items: this.menuItems
        };
    },

    props: {
        menuItems: {
            type: Object,
            required: true
        },

        section: {
            type: String,
            default: ""
        }
    }
};
</script>
