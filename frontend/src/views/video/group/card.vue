<template>
        <b-card no-body>
            <b-card-header>
                <b-card-title>{{group.name}}</b-card-title>
         
                <b-dropdown
                variant="link"
                no-caret
                >

                <template #button-content >
                <feather-icon
                icon="MoreVerticalIcon"
                size="16"
                class="align-middle text-body"
                
                />
                </template>
                <b-dropdown-item  >
                    <feather-icon icon="EditIcon" v-permission="['edit-group']"/>
                    <span class="align-middle ml-50">Edit</span>
                </b-dropdown-item>

                <b-dropdown-item v-permission="['delete-group']">
                    <feather-icon icon="TrashIcon" />
                    <span class="align-middle ml-50">Delete</span>
                </b-dropdown-item>
                </b-dropdown>           
            </b-card-header>
            <b-card-body>
            <router-link :to="{name : 'video'}">
            <b-img
                thumbnail
               style="width:100%"
                :src="group.pictures.sizes[1].link_with_play_button"
                :alt="group.name"
                to="/videos"
            />
            </router-link>
            </b-card-body>
            <b-card-body>
                <b-card-text>
                    {{group.description}}
                </b-card-text>
            </b-card-body>

        </b-card>
    
</template>
<style lang="scss" scoped>
    table , td, tr {
        border: none !important;
    }
</style>
<script>
import {BButton,BCardText, BButtonToolbar,BButtonGroup,BAvatar,BCard, BCardHeader,BDropdown,BDropdownItem,BCardTitle,BCardBody,BImg,BLink} from 'bootstrap-vue';
export default {
    name: 'GroupCard',
    props: {
        group: {
            type: Object,
            defualt: () => {},
        }
    },
    components: {
        BButton,
        BCard,
        BCardText,
        BButtonToolbar,
        BButtonGroup,
        BAvatar,
        BCardHeader,
        BDropdown,
        BDropdownItem,
        BCardTitle,
        BCardBody,
        BImg,
        BLink
    },
    data() {
        return {
            roles : [
                { label : 'Staff', value: 1},
                { label : 'Alliance Partners', value: 2}
            ],
            role: null
        }
    }
}
</script>
