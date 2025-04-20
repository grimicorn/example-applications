<template>
    <div
    class="exchange-space-member flex items-center mb1">
        <avatar
        :src="user.photo_thumbnail_small_url"
        width="32"
        height="32"
        class="mr1"
        image-class="rounded"
        :initials="user.initials"
        :uses-two-factor="user.uses_two_factor_auth"></avatar>

        <div>
            <!-- User name/profile url -->
            <a
            :href="user.profile_url"
            target="_blank"
            class="a-ul fc-color7 fz-14 lh-solid"
            v-text="user.name"
            v-if="member.approved || !currentMember.is_seller"></a>

            <!-- User add modal -->
            <app-exchange-space-new-member
            v-else
            :is-approval="true"
            :member-name="user.name"
            :allow-auto-open-modal="false"
            :route="addRoute"
            :search-route="addSearchRoute"
            :data-load-user-id="user.id"
            class="lh-solid"
            :deny-route="`/dashboard/exchange-spaces/${member.space.id}/member/${member.id}/destroy`"
            ></app-exchange-space-new-member>

            <!-- Occupation -->
            <span
            :class="occupationClass"
            v-if="occupation"
            v-text="occupation"></span>
            <span
            v-else
            :class="occupationClass">&nbsp;</span>


        </div>
    </div>
</template>

<script>
module.exports = {
    props: {
        member: {
            type: Object,
            required: true
        },

        currentMember: {
            type: Object,
            required: true
        },

        addSearchRoute: {
            type: String,
            default: ""
        },

        addRoute: {
            type: String,
            default: ""
        }
    },

    data() {
        return {
            user: this.member.user,
            approved: this.member.approved
        };
    },

    computed: {
        occupation() {
            return this.user.occupation;
        },

        occupationClass() {
            return [
                "fc-color7 fz-14 block lh-solid",
                this.member.approved ? "" : "o-70"
            ];
        }
    },

    methods: {}
};
</script>
