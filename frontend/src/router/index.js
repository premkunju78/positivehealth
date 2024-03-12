import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store'


import ToastificationContent from '@core/components/toastification/ToastificationContent.vue';

Vue.use(VueRouter)

export const asyncRoutes = [{
    path: '/',
    name: 'dashboard',
    component: () =>
        import('@/views/dashboard/index.vue'),

},
{
    path: '/affirmations',
    name: 'affirmations',
    component: () =>
        import('@/views/admin/affirmation.vue'),
    meta: {
        pageTitle: 'Affirmations',
        middleware: "auth",
        breadcrumb: [{
            text: 'Affirmations',
            active: true,
        },],
    },
},
{
    path: '/leads',
    name: 'leads',
    component: () =>
        import('@/views/lead/index.vue'),
    meta: {
        pageTitle: 'Lead List',
        middleware: "auth",
        breadcrumb: [{
            text: 'Lead List',
            active: true,
        },],
    },
},

{
    path: '/leads/general',
    name: 'generalLeads',
    component: () =>
        import('@/views/lead/index.vue'),
    meta: {
        pageTitle: 'General Leads',
        middleware: "General Leads",
        breadcrumb: [{
            text: 'Leads',
            active: true,
        },],
    },
},
{
    path: '/website',
    name: 'websiteLeads',
    component: () =>
        import('@/views/lead/index.vue'),
    meta: {
        pageTitle: 'Website Leads',
        middleware: "auth",
        breadcrumb: [{
            text: 'Website Leads',
            active: true,
        },],
    },
},
{
    path: '/alliance-partners/list',
    name: 'alliance-partners',
    component: () =>
        import('@/views/admin/alliance-partner/list.vue'),
    meta: {
        pageTitle: 'Alliance Partners List',
        middleware: "auth",
        breadcrumb: [{
            text: 'Alliance Partners List',
            active: true,
        },],
    },
},
{
    path: '/alliance-partners/create',
    name: 'create-partner',
    component: () =>
        import('@/views/admin/alliance-partner/create.vue'),
    meta: {
        pageTitle: 'Alliance Partners',
        middleware: "auth",
        breadcrumb: [{
            text: 'Add Alliance Partners',
            active: true,
        },],
        backButton: '/alliance-partners/list',
    },
},
{
    path: '/alliance-partners/consultants',
    name: 'AllianConsultant',
    component: () =>
        import('@/views/admin/alliance-partner/consultantList.vue'),
    meta: {
        pageTitle: 'Show In Client',
        middleware: "auth",
        breadcrumb: [{
            text: 'consultants',
            active: true,
        },],
    },
},
{
    path: '/alliance-partners/consultants/:id',
    name: 'AllianConsultantList',
    component: () =>
        import('@/views/admin/alliance-partner/consultantList.vue'),
    meta: {
        pageTitle: 'Show In Client',
        middleware: "auth",
        breadcrumb: [{
            text: 'consultants',
            active: true,
        },],
        backButton: '/alliance-partners/consultants',
    },
    props: true

},
{
    path: "/alliance-partners/:id/edit",
    name: 'edit-partner',
    component: () =>
        import('@/views/admin/alliance-partner/create.vue'),
    meta: {
        pageTitle: 'Edit Alliance Partner',
        middleware: "auth",
        breadcrumb: [{
            text: 'Edit Alliance Partner',
            active: true,
        },],
        backButton: '/alliance-partners/list',
    },
    props: true

},
{
    path: ':role/clients/:id/',
    name: 'mapped-clients',
    component: () =>
        import('@/views/admin/client/list.vue'),
    meta: {
        pageTitle: 'Assigned Clients',
        middleware: "auth",
        breadcrumb: [{
            text: 'Assigned Clients',
            active: true,
        },],
        backButton: '/clients/list',
    },
    props: true

},
{
    path: '/clients/list',
    name: 'clients',
    component: () =>
        import('@/views/admin/client/list.vue'),
    meta: {
        pageTitle: 'Client List',
        middleware: "auth",
        breadcrumb: [{
            text: 'Client List',
            active: true,
        },],
    },
},
{
    path: '/clients/create',
    name: 'client-create',
    component: () =>
        import('@/views/admin/client/create.vue'),
    meta: {
        pageTitle: 'Create Client',
        middleware: "auth",
        breadcrumb: [{
            text: 'Add Client',
            active: true,
        },],
        backButton: '/clients/list',
    },

},
{
    path: '/clients/edit/:id',
    name: 'edit-client',
    component: () =>
        import('@/views/admin/client/create.vue'),
    meta: {
        pageTitle: 'Edit Client',
        middleware: "auth",
        breadcrumb: [{
            text: 'Edit Client',
            active: true,
        },],
        backButton: '/clients/list',
    },
    props: true,
},
{
    path: '/coordinators/list',
    name: 'coordinators',
    component: () =>
        import('@/views/admin/coordinator/list.vue'),
    meta: {
        pageTitle: 'Coordinators',
        middleware: "auth",
        breadcrumb: [{
            text: 'Coordinators',
            active: true,
        },],
    },
},
{
    path: '/coordinators/create',
    name: 'coordinator-create',
    component: () =>
        import('@/views/admin/coordinator/create.vue'),
    meta: {
        pageTitle: 'Create Coordinator',
        middleware: "auth",
        breadcrumb: [{
            text: 'Add Coordinator',
            active: true,
        },],
        backButton: '/coordinators/list',
    },

},
{
    path: '/coordinators/edit/:id',
    name: 'edit-coordinator',
    component: () =>
        import('@/views/admin/coordinator/create.vue'),
    meta: {
        pageTitle: 'Edit Coordinator',
        middleware: "auth",
        breadcrumb: [{
            text: 'Edit Coordinator',
            active: true,
        },],
        backButton: '/coordinators/list',
    },
    props: true,
},
{
    path: '/coordinators/alliance/:id',
    name: 'coodinator-alliance-view',
    component: () =>
        import('@/views/admin/coordinator/alliancepartners.vue'),
    meta: {
        pageTitle: ':name',
        middleware: "auth",
        breadcrumb: [{
            text: 'Assign Alliance Partner',
            active: true,
        },],
        backButton: '/coordinators/list',
    },
    props: true,
},

{
    path: '/healthcoaches/list',
    name: 'healthcoaches',
    component: () =>
        import('@/views/admin/healthcoach/list.vue'),
    meta: {
        pageTitle: 'Healthcoaches',
        middleware: "auth",
        breadcrumb: [{
            text: 'Healthcoaches',
            active: true,
        },],
    },
},
{
    path: '/cis/list',
    name: 'cis',
    component: () =>
        import('@/views/admin/healthcoach/cislist.vue'),
    meta: {
        pageTitle: 'CIS',
        middleware: "auth",
        breadcrumb: [{
            text: 'CIS',
            active: true,
        },],
    },
},
{
    path: '/healthcoaches/create',
    name: 'healthcoach-create',
    component: () =>
        import('@/views/admin/healthcoach/create.vue'),
    meta: {
        pageTitle: 'Create Healthcoach',
        middleware: "auth",
        breadcrumb: [{
            text: 'Add Healthcoach',
            active: true,
        },],
        backButton: '/healthcoaches/list',
    },

},
{
    path: '/healthcoaches/edit/:id',
    name: 'edit-healthcoach',
    component: () =>
        import('@/views/admin/healthcoach/create.vue'),
    meta: {
        pageTitle: 'Edit Healthcoach',
        middleware: "auth",
        breadcrumb: [{
            text: 'Edit Healthcoach',
            active: true,
        },],
        backButton: '/healthcoaches/list',
    },
    props: true,
},

{
    path: '/mh/list',
    name: 'mh',
    component: () =>
        import('@/views/admin/mh/list.vue'),
    meta: {
        pageTitle: 'Marketing Heads',
        middleware: "auth",
        breadcrumb: [{
            text: 'Marketing Heads',
            active: true,
        },],
    },
},
{
    path: '/mh/create',
    name: 'mh-create',
    component: () =>
        import('@/views/admin/mh/create.vue'),
    meta: {
        pageTitle: 'Create Marketing Head',
        middleware: "auth",
        breadcrumb: [{
            text: 'Add Marketing Head',
            active: true,
        },],
        backButton: '/mh/list',
    },

},
{
    path: '/mh/edit/:id',
    name: 'edit-mh',
    component: () =>
        import('@/views/admin/mh/create.vue'),
    meta: {
        pageTitle: 'Edit Marketing Head',
        middleware: "auth",
        breadcrumb: [{
            text: 'Edit Marketing Head',
            active: true,
        },],
        backButton: '/mh/list',
    },
    props: true,
},

{
    path: '/dh/list',
    name: 'dh',
    component: () =>
        import('@/views/admin/dh/list.vue'),
    meta: {
        pageTitle: 'Diagnostic Partners',
        middleware: "auth",
        breadcrumb: [{
            text: 'Diagnostic Partners',
            active: true,
        },],
    },
},
{
    path: '/dh/create',
    name: 'dh-create',
    component: () =>
        import('@/views/admin/dh/create.vue'),
    meta: {
        pageTitle: 'Create Diagnostic Partner',
        middleware: "auth",
        breadcrumb: [{
            text: 'Add Diagnostic Partner',
            active: true,
        },],
        backButton: '/dh/list',
    },

},
{
    path: '/dh/edit/:id',
    name: 'edit-dh',
    component: () =>
        import('@/views/admin/dh/create.vue'),
    meta: {
        pageTitle: 'Edit Diagnostic Partner',
        middleware: "auth",
        breadcrumb: [{
            text: 'Edit Diagnostic Partner',
            active: true,
        },],
        backButton: '/dh/list',
    },
    props: true,
},


{
    path: '/samplecollector/list',
    name: 'samplecollector',
    component: () =>
        import('@/views/admin/samplecollector/list.vue'),
    meta: {
        pageTitle: 'Sample Collector',
        middleware: "auth",
        breadcrumb: [{
            text: 'Sample Collector',
            active: true,
        },],
    },
},
{
    path: '/samplecollector/create',
    name: 'samplecollector-create',
    component: () =>
        import('@/views/admin/samplecollector/create.vue'),
    meta: {
        pageTitle: 'Create Sample Collector',
        middleware: "auth",
        breadcrumb: [{
            text: 'Add Sample Collector',
            active: true,
        },],
        backButton: '/samplecollector/list',
    },

},
{
    path: '/samplecollector/edit/:id',
    name: 'edit-samplecollector',
    component: () =>
        import('@/views/admin/samplecollector/create.vue'),
    meta: {
        pageTitle: 'Edit Sample Collector',
        middleware: "auth",
        breadcrumb: [{
            text: 'Edit Sample Collector',
            active: true,
        },],
        backButton: '/samplecollector/list',
    },
    props: true,
},


{
    path: '/dataentryuser/list',
    name: 'dataentryuser',
    component: () =>
        import('@/views/admin/dataentryuser/list.vue'),
    meta: {
        pageTitle: 'Data Entry User',
        middleware: "auth",
        breadcrumb: [{
            text: 'Data Entry User',
            active: true,
        },],
    },
},
{
    path: '/dataentryuser/create',
    name: 'dataentryuser-create',
    component: () =>
        import('@/views/admin/dataentryuser/create.vue'),
    meta: {
        pageTitle: 'Create Data Entry User',
        middleware: "auth",
        breadcrumb: [{
            text: 'Add Data Entry User',
            active: true,
        },],
        backButton: '/dataentryuser/list',
    },

},
{
    path: '/dataentryuser/edit/:id',
    name: 'edit-dataentryuser',
    component: () =>
        import('@/views/admin/dataentryuser/create.vue'),
    meta: {
        pageTitle: 'Edit Data Entry User',
        middleware: "auth",
        breadcrumb: [{
            text: 'Edit Data Entry User',
            active: true,
        },],
        backButton: '/dataentryuser/list',
    },
    props: true,
},


{
    path: '/me/list',
    name: 'me',
    component: () =>
        import('@/views/admin/me/list.vue'),
    meta: {
        pageTitle: 'Marketing Executives',
        middleware: "auth",
        breadcrumb: [{
            text: 'Marketing Executives',
            active: true,
        },],
    },
},

{
    path: '/accountsincharge/list',
    name: 'accountsincharge',
    component: () =>
        import('@/views/admin/accountsincharge/list.vue'),
    meta: {
        pageTitle: 'Accounts Incharge',
        middleware: "auth",
        breadcrumb: [{
            text: 'Accounts Incharge',
            active: true,
        },],
    },
},

{
    path: '/accountsincharge/create',
    name: 'accountsincharge-create',
    component: () =>
        import('@/views/admin/accountsincharge/create.vue'),
    meta: {
        pageTitle: 'Create Account Incharge',
        middleware: "auth",
        breadcrumb: [{
            text: 'Add Account Incharge',
            active: true,
        },],
        backButton: '/accountsincharge/list',
    },

},

{
    path: '/me/create',
    name: 'me-create',
    component: () =>
        import('@/views/admin/me/create.vue'),
    meta: {
        pageTitle: 'Create Harketing Executive',
        middleware: "auth",
        breadcrumb: [{
            text: 'Add Harketing Head',
            active: true,
        },],
        backButton: '/me/list',
    },

},
{
    path: '/me/edit/:id',
    name: 'edit-me',
    component: () =>
        import('@/views/admin/me/create.vue'),
    meta: {
        pageTitle: 'Edit Marketing Executive',
        middleware: "auth",
        breadcrumb: [{
            text: 'Edit Marketing Head',
            active: true,
        },],
        backButton: '/me/list',
    },
    props: true,
},

{
    path: '/client/:id/modules',
    name: 'client-modules',
    component: () =>
        import('@/views/admin/client/modules.vue'),
    meta: {
        pageTitle: 'Client Modules',
        middleware: "auth",
        breadcrumb: [{
            text: 'Client Modules',
            active: true,
        },],
        backButton: '/clients/list',
        clientModule: true,
    },
    props: true,
},
{
    path: '/clients/videolist/:id',
    name: 'assigned-videos',
    component: () =>
        import('@/views/admin/client/videoList.vue'),
    meta: {
        pageTitle: 'Assigned Videos',
        middleware: "auth",
        breadcrumb: [{
            text: 'Assigned Videos',
            active: true,
        },],
        backButton: '/client/:id/modules',
        clientModule: true,
    },
    props: true,
},
{
    path: '/consultants/list',
    name: 'consultants',
    component: () =>
        import('@/views/admin/consultants/list.vue'),
    meta: {
        pageTitle: 'Consultants',
        middleware: "auth",
        breadcrumb: [{
            text: 'Consultants',
            active: true,
        },],
    },
},
{
    path: '/consultants/create',
    name: 'addConsultant',
    component: () =>
        import('@/views/admin/consultants/create.vue'),
    meta: {
        pageTitle: 'Add Consultant',
        middleware: "auth",
        breadcrumb: [{
            text: 'Add Consultant',
            active: true,
        },],
        backButton: '/consultants/list',
    },
},
{
    path: '/consultant/:id',
    name: 'editConsultant',
    component: () =>
        import('@/views/admin/consultants/create.vue'),
    meta: {
        pageTitle: 'Edit Consultant',
        middleware: "auth",
        breadcrumb: [{
            text: 'Edit Consultant',
            active: true,
        },],
        backButton: '/consultants/list',
    },
    props: true
},
{
    path: '/options',
    name: 'options',
    component: () =>
        import('@/views/options/options.vue'),
    meta: {
        pageTitle: 'Options',
        middleware: "auth",
        breadcrumb: [{
            text: 'Options',
            active: true,
        },],
    },
},
{
    path: '/questionnaires',
    name: 'questionnaires',
    component: () =>
        import('@/views/questionnaires/index.vue'),
    meta: {
        pageTitle: 'Form Builder',
        middleware: "auth",
        breadcrumb: [{
            text: 'Form Builder',
            active: true,
        },],
    },
},
{
    path: '/questionnaires/:id',
    name: 'clientQuestionnaires',
    component: () =>
        import('@/views/questionnaires/index.vue'),
    meta: {
        pageTitle: 'Client Questionnaire',
        middleware: "auth",
        breadcrumb: [{
            text: 'Questionnaires',
            active: true,
        },],
        backButton: '/client/:id/modules',
        clientModule: true,
    },
    props: true
},
{
    path: '/assessments/:id',
    name: 'assessments',
    component: () =>
        import('@/views/questionnaires/assessments.vue'),
    meta: {
        pageTitle: 'Assessments',
        middleware: "auth",
        breadcrumb: [{
            text: 'Assessments',
            active: true,
        },],
        backButton: '/client/:id/modules',
        clientModule: true,
    },
    props: true
},


{
    path: '/htmlquestionnaires/:id',
    name: 'clientHTMLQuestionnaires',
    component: () =>
        import('@/views/htmlquestionnaires/clientindex.vue'),
    meta: {
        pageTitle: 'Client HTML Questionnaire',
        middleware: "auth",
        breadcrumb: [{
            text: 'HTML Questionnaires',
            active: true,
        },],
        backButton: '/client/:id/modules',
        clientModule: true,
    },
    props: true
},
{
    path: '/questionnaires/create',
    name: 'create-questionnaires',
    component: () =>
        import('@/views/questionnaires/create.vue'),
    meta: {
        pageTitle: 'Create Questionnaire',
        middleware: "auth",
        breadcrumb: [{
            text: 'Create Questionnaire',
            active: true,
        },],
        backButton: '/questionnaires',
    },
},
{
    path: '/questionnaires/update/:id',
    name: 'update-questionnaires',
    component: () =>
        import('@/views/questionnaires/update.vue'),
    meta: {
        pageTitle: 'Update Questionnaire',
        middleware: "auth",
        breadcrumb: [{
            text: 'Update Questionnaire',
            active: true,
        },],
        backButton: '/questionnaires',
    },
    props: true,
},
{
    path: '/questionnaires/fill/:id',
    name: 'fill-questionnaire',
    component: () =>
        import('@/views/questionnaires/fill.vue'),
    meta: {
        pageTitle: 'Fill Questionnaire',
        middleware: "auth",
        breadcrumb: [{
            text: 'Fill',
            active: true,
        },],
        backButton: '/questionnaires',
    },
    props: true,
},
{
    path: '/questionnaires/edit/:id',
    name: 'edit-questionnaire',
    component: () =>
        import('@/views/questionnaires/edit.vue'),
    meta: {
        pageTitle: 'Edit Questionnaire',
        middleware: "auth",
        breadcrumb: [{
            text: 'Edit',
            active: true,
        },],
        backButton: '/questionnaires',
    },
    props: true,
},
{
    path: '/questionnaires/view/:id',
    name: 'view-questionnaire',
    component: () =>
        import('@/views/questionnaires/view.vue'),
    meta: {
        pageTitle: 'View Questionnaire',
        middleware: "auth",
        breadcrumb: [{
            text: 'View',
            active: true,
        },],
        backButton: '/questionnaires',
    },
    props: true,
},
{
    path: '/questionnaires/preview/:id',
    name: 'preview-questionnaire',
    component: () =>
        import('@/views/questionnaires/preview.vue'),
    meta: {
        pageTitle: 'Preview Questionnaire',
        middleware: "auth",
        breadcrumb: [{
            text: 'Preview',
            active: true,
        },],
        backButton: '/questionnaires',
    },
    props: true,
},
{
    path: '/htmlquestionnaires',
    name: 'htmlquestionnaires',
    component: () =>
        import('@/views/htmlquestionnaires/index.vue'),
    meta: {
        pageTitle: 'HTML Questionnaire',
        middleware: "auth",
        breadcrumb: [{
            text: 'HTML Questionnaire',
            active: true,
        },],
    },
},
{
    path: '/htmlquestionnaires/preview/:id',
    name: 'preview-htmlquestionnaire',
    component: () =>
        import('@/views/htmlquestionnaires/preview.vue'),
    meta: {
        pageTitle: 'Preview Questionnaire',
        middleware: "auth",
        breadcrumb: [{
            text: 'Preview',
            active: true,
        },],
        backButton: '/htmlquestionnaires',
    },
    props: true,
},
{
    path: '/htmlquestionnaires/view/:id',
    name: 'view-htmlquestionnaires',
    component: () =>
        import('@/views/htmlquestionnaires/view.vue'),
    meta: {
        pageTitle: 'View Questionnaire',
        middleware: "auth",
        breadcrumb: [{
            text: 'View',
            active: true,
        },],
        backButton: '/htmlquestionnaires/:id',
    },
    props: true,
},
{
    path: '/htmlquestionnaires/view/:id/:userid',
    name: 'view-userhtmlquestionnaires',
    component: () =>
        import('@/views/htmlquestionnaires/viewresult.vue'),
    meta: {
        pageTitle: 'View Questionnaire',
        middleware: "auth",
        breadcrumb: [{
            text: 'View',
            active: true,
        },],
        backButton: '/htmlquestionnaires/:userid',
    },
    props: true,
},
{
    path: '/settings/role/list',
    name: 'settings-role-list',
    component: () =>
        import('@/views/admin/settings/RoleList.vue'),
    meta: {
        pageTitle: 'Role List',
        middleware: "auth",
        breadcrumb: [{
            text: 'Role List',
            active: true,
        },],
    },
},
{
    path: "/settings/role/:id/edit",
    name: 'settings-edit-role',
    component: () =>
        import('@/views/admin/settings/CreateRole.vue'),
    meta: {
        pageTitle: 'Edit Role',
        middleware: "auth",
        breadcrumb: [{
            text: 'Edit Role',
            active: true,
        },],
        backButton: '/settings/role/list',
    },
    props: true
},

{
    path: '/settings/role/create',
    name: 'settings-create-role',
    component: () =>
        import('@/views/admin/settings/CreateRole.vue'),
    meta: {
        pageTitle: 'Create Role',
        middleware: "auth",
        breadcrumb: [{
            text: 'Create Role',
            active: true,
        },],
        backButton: '/settings/role/list',
    },
    props: true
},
{
    path: '/diet-plan/list',
    name: 'diet-plan',
    component: () =>
        import('@/views/diet-plan/list.vue'),
    meta: {
        pageTitle: 'Diet Plan',
        middleware: "auth",
        breadcrumb: [{
            text: 'Diet-Plan',
            active: true,
        },],
    },
},
{
    path: '/diet-plan/:id/list',
    name: 'diet-plan-client',
    component: () =>
        import('@/views/diet-plan/client.vue'),
    meta: {
        pageTitle: 'Diet Plan',
        middleware: "auth",
        breadcrumb: [{
            text: 'Diet-Plan',
            active: true,
        },],
        backButton: '/client/:id/modules',
        clientModule: true,
    },
    props: true
},
{
    path: '/video/list',
    name: 'video',
    component: () =>
        import('@/views/video/list.vue'),
    meta: {
        pageTitle: 'Video Gallery',
        middleware: "auth",
        breadcrumb: [{
            text: 'Video Gallery',
            active: true,
        },],
    },
},
{
    path: '/group/list',
    name: 'groups',
    component: () =>
        import('@/views/video/group/list.vue'),
    meta: {
        pageTitle: 'Groups',
        middleware: "auth",
        breadcrumb: [{
            text: 'Groups',
            active: true,
        },],
    },
},
{
    path: '/group/:id',
    name: 'viewGroup',
    component: () =>
        import('@/views/video/group/view.vue'),
    meta: {
        pageTitle: 'View Group',
        middleware: "auth",
        breadcrumb: [{
            text: 'View Group',
            active: true,
        },],
        backButton: '/group/list',
    },
    props: true
},
{
    path: '/programs',
    name: 'viewPackages',
    component: () =>
        import('@/views/package/index.vue'),
    meta: {
        pageTitle: 'Our Programs',
        middleware: "auth",
        breadcrumb: [{
            text: 'Our Programs',
            active: true,
        },],
    },
    props: true
},
{
    path: '/programmes/me',
    name: 'myPackages',
    component: () =>
        import('@/views/package/me.vue'),
    meta: {
        pageTitle: 'My Programs',
        middleware: "auth",
        breadcrumb: [{
            text: 'My Programs',
            active: true,
        },],
    },
    props: true
},
{
    path: '/program/:id/view',
    name: 'viewPackage',
    component: () =>
        import('@/views/package/detail.vue'),
    meta: {
        middleware: "auth",
    },
    props: true
},
{
    path: '/program/create',
    name: 'createProgram',
    component: () =>
        import('@/views/package/create.vue'),
    meta: {
        pageTitle: 'New Program',
        middleware: "auth",
        breadcrumb: [{
            text: 'New Program',
            active: true,
        },],
        backButton: '/programs',
    },
    props: true
},
{
    path: '/package/list',
    name: 'packages',
    component: () =>
        import('@/views/package/list.vue'),
    meta: {
        pageTitle: 'Programs',
        middleware: "auth",
        breadcrumb: [{
            text: 'Programs',
            active: true,
        },],
    },
    props: true
},
{
    path: '/package/create',
    name: 'createpackages',
    component: () =>
        import('@/views/package/createpackage.vue'),
    meta: {
        pageTitle: 'Programs',
        middleware: "auth",
        breadcrumb: [{
            text: 'Programs',
            active: true,
        },],
        backButton: '/package/list',
    },
    props: true
},
{
    path: '/package/edit/:id',
    name: 'editPackage',
    component: () =>
        import('@/views/package/createpackage.vue'),
    meta: {
        pageTitle: 'Programs',
        middleware: "auth",
        breadcrumb: [{
            text: 'Programs',
            active: true,
        },],
        backButton: '/package/list',
    },
    props: true
},

{
    path: '/package/group/list',
    name: 'packageGroups',
    component: () =>
        import('@/views/package/group/list.vue'),
    meta: {
        pageTitle: 'Program Groups',
        middleware: "auth",
        breadcrumb: [{
            text: 'Groups',
            active: true,
        },],
    },
    props: true
},

{
    path: '/consultations',
    name: 'Consultations',
    component: () =>
        import('@/views/consultations/consultants.vue'),
    meta: {
        pageTitle: 'Consultations',
        middleware: "auth",
        breadcrumb: [{
            text: 'Consultations',
            active: true,
        },],
    },
    props: true
},

{
    path: '/consultation/:id',
    name: 'viewConsultant',
    component: () =>
        import('@/views/consultations/detail.vue'),
    meta: {
        pageTitle: 'Consultant Detail',
        middleware: "auth",
        breadcrumb: [{
            text: 'Consultant Detail',
            active: true,
        },],
        backButton: '/consultations',
    },
    props: true
},

{
    path: '/casesheet/create/:id',
    name: 'newcasesheet',
    component: () =>
        import('@/views/casesheet/create.vue'),
    meta: {
        pageTitle: 'Case Sheets',
        middleware: "auth",
        breadcrumb: [{
            text: 'Casesheets',
            active: true,
        },],
        backButton: '/casesheet/list/:id',
    },
    props: true
},
{
    path: '/casesheet/list',
    name: 'casesheets',
    component: () =>
        import('@/views/casesheet/list.vue'),
    meta: {
        middleware: "auth",
        contentRenderer: 'sidebar-left',
        contentClass: 'email-application',
        navActiveLink: 'casesheet-list',

    },
    props: true
},
{
    path: '/casesheet/list/:id',
    name: 'clientCaseSheets',
    component: () =>
        import('@/views/casesheet/list.vue'),
    meta: {
        middleware: "auth",
        contentRenderer: 'sidebar-left',
        contentClass: 'email-application',
        navActiveLink: 'casesheet-list',

    },
    props: true
},
{
    path: '/casesheet/list/history',
    name: 'view-history',
    component: () =>
        import('@/views/casesheet/history.vue'),
    meta: {
        middleware: "auth",
        breadcrumb: [{
            text: 'Casesheets',
            active: true,
        },],
        backButton: '/casesheet/list',
    },
    props: true
},
{
    path: '/crs/:id',
    name: 'crs',
    component: () =>
        import('@/views/crs/index.vue'),
    meta: {
        middleware: "auth",
        breadcrumb: [{
            text: 'Cross Referral',
            active: true,
        },],

    },
    props: true
},
{
    path: '/recipes/list',
    name: 'recipes',
    component: () =>
        import('@/views/recipe/list.vue'),
    meta: {
        pageTitle: 'Recipe',
        middleware: "auth",
        breadcrumb: [{
            text: 'Recipe',
            active: true,
        },],
    },
},
{
    path: '/recipes/:id/list',
    name: 'recipe-client',
    component: () =>
        import('@/views/recipe/client.vue'),
    meta: {
        pageTitle: 'Recipes',
        middleware: "auth",
        breadcrumb: [{
            text: 'Recipes',
            active: true,
        },],
        backButton: '/client/:id/modules',
        clientModule: true,
    },
    props: true
},
{
    path: '/reminders/list',
    name: 'reminders',
    component: () =>
        import('@/views/reminder/list.vue'),
    meta: {
        pageTitle: 'Reminders',
        middleware: "auth",
        breadcrumb: [{
            text: 'Reminders',
            active: true,
        },],
    },
},

{
    path: '/availability/list',
    name: 'availability',
    component: () =>
        import('@/views/appointments/availability.vue'),
    meta: {
        pageTitle: 'Availability',
        middleware: "auth",
        breadcrumb: [{
            text: 'Availability',
            active: true,
        },],
    },
},
{
    path: '/availability',
    name: 'viewAvailability',
    component: () =>
        import('@/views/appointments/list.vue'),
    meta: {
        pageTitle: 'Availability',
        middleware: "auth",
        breadcrumb: [{
            text: 'Availability',
            active: true,
        },],
    },
},
{
    path: '/booking/list',
    name: 'book-appointments',
    component: () =>
        import('@/views/appointments/createAppointment.vue'),
    meta: {
        pageTitle: 'Book Appointments',
        middleware: "auth",
        breadcrumb: [{
            text: 'Bookings',
            active: true,
        },],
    },
    props: true,
},
{
    path: '/schedules',
    name: 'viewSchedules',
    component: () =>
        import('@/views/appointments/viewSchedules.vue'),
    meta: {
        pageTitle: 'Schedules',
        middleware: "auth",
        breadcrumb: [{
            text: 'Schedules',
            active: true,
        },],
    },
},
{
    path: '/workflowschedules',
    name: 'workflowschedules',
    component: () =>
        import('@/views/workflow/viewSchedules.vue'),
    meta: {
        pageTitle: 'Session schedules',
        middleware: "auth",
        breadcrumb: [{
            text: 'Session schedules',
            active: true,
        },],
    },
},
{
    path: '/todayWorkflowSchedules',
    name: 'todayWorkflowSchedules',
    component: () =>
        import('@/views/workflow/todayWorkflowSchedules.vue'),
    meta: {
        pageTitle: 'Today Schedules',
        middleware: "auth",
        breadcrumb: [{
            text: 'Today Schedules',
            active: true,
        },],
    },
},
{
    path: '/workflowcalendars',
    name: 'workflowcalendars',
    component: () =>
        import('@/views/workflow/workFlowCalendars.vue'),
    meta: {
        pageTitle: 'Today Schedules',
        middleware: "auth",
        breadcrumb: [{
            text: 'Today Schedules',
            active: true,
        },],
    },
},

{
    path: '/schedules/list',
    name: 'schedules',
    component: () =>
        import('@/views/appointments/schedules.vue'),
    meta: {
        pageTitle: 'Schedules',
        middleware: "auth",
        breadcrumb: [{
            text: 'Schedules',
            active: true,
        },],
    },
},
{
    path: '/schedule/:id/list',
    name: 'clientSchedules',
    component: () =>
        import('@/views/appointments/schedules.vue'),
    meta: {
        pageTitle: 'Client Schedules',
        middleware: "auth",
        breadcrumb: [{
            text: 'Schedule',
            active: true,
        },],
        backButton: '/client/:id/modules',
        clientModule: true,
    },
    props: true
},
{
    path: '/workflow/:id/list',
    name: 'workFlow',
    component: () =>
        import('@/views/workflow/list.vue'),
    meta: {
        pageTitle: 'Session Schedules',
        middleware: "auth",
        breadcrumb: [{
            text: 'Schedule',
            active: true,
        },],
        backButton: '/client/:id/modules',
        clientModule: true,
    },
    props: true
},
{
    path: '/workflow/:id/calendar',
    name: 'workFlowCalendar',
    component: () =>
        import('@/views/workflow/calendar.vue'),
    meta: {
        pageTitle: 'Session Schedules',
        middleware: "auth",
        breadcrumb: [{
            text: 'Schedule',
            active: true,
        },],
        backButton: '/client/:id/modules',
        clientModule: true,
    },
    props: true
},
{
    path: '/meetings',
    name: 'meetings',
    component: () =>
        import('@/views/appointments/meeting.vue'),
    meta: {
        pageTitle: 'Meetings',
        middleware: "auth",
        breadcrumb: [{
            text: 'Meetings',
            active: true,
        },],
    },
    props: true,
},
{
    path: '/meeting/join?link=:link',
    name: 'meeting',
    component: () =>
        import('@/views/appointments/joinMeeting.vue'),
    meta: {
        pageTitle: 'Join Meeting',
        middleware: "auth",
        breadcrumb: [{
            text: 'Meeting',
            active: true,
        },],
        backButton: '/meetings',
    },
    props: true,
},

{
    path: '/investigation/:id',
    name: 'investigations',
    component: () =>
        import('@/views/investigation/index.vue'),
    meta: {
        pageTitle: 'Investigations',
        middleware: "auth",
        breadcrumb: [{
            text: 'Investigations',
            active: true,
        },],
    },
    props: true,
},

{
    path: '/investigation/',
    name: 'clientInvestigation',
    component: () =>
        import('@/views/investigation/index.vue'),
    meta: {
        pageTitle: 'Investigations',
        middleware: "auth",
        breadcrumb: [{
            text: 'Investigations',
            active: true,
        },],
    },
    props: true,
},

{
    path: '/investigation/create/:id',
    name: 'newInvestigation',
    component: () =>
        import('@/views/investigation/create.vue'),
    meta: {
        pageTitle: 'New Investigation',
        middleware: "auth",
        breadcrumb: [{
            text: 'New Investigation',
            active: true,
        },],
        backButton: '/investigation',
    },
    props: true,
},

{
    path: '/investigation/edit/:id',
    name: 'editInvestigation',
    component: () =>
        import('@/views/investigation/edit.vue'),
    meta: {
        pageTitle: 'Edit Investigation',
        middleware: "auth",
        breadcrumb: [{
            text: 'Edit Investigation',
            active: true,
        },],
        backButton: '/investigation',
    },
    props: true,
},
{
    path: '/investigation/view/:id',
    name: 'viewInvestigation',
    component: () =>
        import('@/views/investigation/view.vue'),
    meta: {
        pageTitle: 'View Investigation',
        middleware: "auth",
        breadcrumb: [{
            text: 'View Investigation',
            active: true,
        },],
        backButton: '/investigation',
    },
    props: true,
},
{
    path: '/prescription/:id',
    name: 'prescriptions',
    component: () =>
        import('@/views/prescription/index.vue'),
    meta: {
        pageTitle: 'Prescriptions',
        middleware: "auth",
        breadcrumb: [{
            text: 'Prescriptions',
            active: true,
        },],
        backButton: '/client/:id/modules',
    },
    props: true,
},
{
    path: '/prescription/create/:id',
    name: 'newPrescription',
    component: () =>
        import('@/views/prescription/create.vue'),
    meta: {
        pageTitle: 'New Presriptions',
        middleware: "auth",
        breadcrumb: [{
            text: 'New Prescription',
            active: true,
        },],
        backButton: '/prescription/:id',
    },
    props: true,
},
{
    path: '/prescription/',
    name: 'clientPrescription',
    component: () =>
        import('@/views/prescription/index.vue'),
    meta: {
        pageTitle: 'Prescriptions',
        middleware: "auth",
        breadcrumb: [{
            text: 'Prescriptions',
            active: true,
        },],
    },
    props: true,
},
{
    path: '/prescription/edit/:prescriptionId',
    name: 'editPrescription',
    component: () =>
        import('@/views/prescription/create.vue'),
    meta: {
        pageTitle: 'Edit Prescription',
        middleware: "auth",
        breadcrumb: [{
            text: 'Edit Prescription',
            active: true,
        },],
        backButton: '/prescription/',
    },
    props: true,
},
{
    path: '/prescription/view/:id',
    name: 'viewPrescription',
    component: () =>
        import('@/views/prescription/view.vue'),
    meta: {
        pageTitle: 'View Prescription',
        middleware: "auth",
        breadcrumb: [{
            text: 'View Prescription',
            active: true,
        },],
        backButton: '/prescription/',
    },
    props: true,
},
{
    path: '/investigation/category',
    name: 'testCategory',
    component: () =>
        import('@/views/investigation/config/category.vue'),
    meta: {
        pageTitle: 'Test Category',
        middleware: "auth",
        breadcrumb: [{
            text: 'Test Category',
            active: true,
        },],
    },
    props: true,
},
{
    path: '/investigation/item',
    name: 'testItem',
    component: () =>
        import('@/views/investigation/config/item.vue'),
    meta: {
        pageTitle: 'Test Item',
        middleware: "auth",
        breadcrumb: [{
            text: 'Test Item',
            active: true,
        },],
    },
    props: true,
},

{
    path: '/investigation/configuration',
    name: 'configureInvestigation',
    component: () =>
        import('@/views/investigation/config/configure.vue'),
    meta: {
        pageTitle: 'Configure Investigation',
        middleware: "auth",
        breadcrumb: [{
            text: 'Configure Investigation',
            active: true,
        },],
    },
    props: true,
},

{
    path: '/investigation/:id/upload',
    name: 'uploadReports',
    component: () =>
        import('@/views/investigation/upload.vue'),
    meta: {
        pageTitle: 'Upload Details',
        middleware: "auth",
        breadcrumb: [{
            text: 'Upload Details',
            active: true,
        },],
        backButton: '/investigation/:id',
    },
    props: true,
},



{
    path: '/profile',
    name: 'user-profile',
    component: () =>
        import('@/views/account-setting/AccountSetting.vue'),
    meta: {
        pageTitle: 'Profile',
        middleware: "auth",
        breadcrumb: [{
            text: 'Profile Settings',
            active: true,
        },],
    },
},

{
    path: '/accounts/packages',
    name: 'accountPackages',
    component: () =>
        import('@/views/accounts/package.vue'),
    meta: {
        pageTitle: 'Packages',
        middleware: "auth",
        breadcrumb: [{
            text: 'Payment Details',
            active: true,
        },],
    },
},
{
    path: '/accounts/consultations',
    name: 'accountConsultation',
    component: () =>
        import('@/views/accounts/consultation.vue'),
    meta: {
        pageTitle: 'Consultations',
        middleware: "auth",
        breadcrumb: [{
            text: 'Payment Details',
            active: true,
        },],
    },
},
{
    path: '/accounts/discount',
    name: 'discountCoupon',
    component: () =>
        import('@/views/discount/index.vue'),
    meta: {
        pageTitle: 'Discount Coupon',
        middleware: "auth",
        breadcrumb: [{
            text: 'Discount Coupon',
            active: true,
        },],
    },
},
{
    path: '/accounts/discount/create',
    name: 'createDiscountCoupon',
    component: () =>
        import('@/views/discount/createDiscountCoupon.vue'),
    meta: {
        pageTitle: 'Discount Coupon',
        middleware: "auth",
        breadcrumb: [{
            text: 'Discount Coupon',
            active: true,
        },],
        backButton: '/accounts/discount',

    },

},

{
    path: '/accounts/discount/:id',
    name: 'editDiscountCoupon',
    component: () =>
        import('@/views/discount/createDiscountCoupon.vue'),
    meta: {
        pageTitle: 'Discount Coupon',
        middleware: "auth",
        breadcrumb: [{
            text: 'Discount Coupon',
            active: true,
        },],
        backButton: '/accounts/discount',
    },
    props: true
},
{
    path: '/enquiries',
    name: 'enquiries',
    component: () =>
        import('@/views/enquiries/list.vue'),
    meta: {
        pageTitle: 'Queries',
        middleware: "auth",
        breadcrumb: [{
            text: 'Queries Enquiries',
            active: true,
        },],
    },
},
{
    path: '/program-enquiries',
    name: 'program-enquiries',
    component: () =>
        import('@/views/program-enquiries/list.vue'),
    meta: {
        pageTitle: 'Enquiries',
        middleware: "auth",
        breadcrumb: [{
            text: 'Queries Enquiries',
            active: true,
        },],
    },
},
{
    path: '/clientgroups',
    name: 'clientgroups',
    component: () =>
        import('@/views/admin/client/group/list.vue'),
    meta: {
        pageTitle: 'Client Groups',
        middleware: "auth",
        breadcrumb: [{
            text: 'Client Groups',
            active: true,
        },],
    },
},
{
    path: '/chat',
    name: 'chat',
    component: () =>
        import('@/views/chat/index.vue'),
    meta: {
        pageTitle: 'Chat',
        middleware: "auth",
        breadcrumb: [{
            text: 'Chat',
            active: true,
        },],
    },
},
{
    path: '/feedback',
    name: 'feedback',
    component: () =>
        import('@/views/feedback/list.vue'),
    meta: {
        pageTitle: 'Feedback',
        middleware: "auth",
        breadcrumb: [{
            text: 'Feedback',
            active: true,
        },],
    },
},
{
    path: '/login',
    name: 'login',
    component: () =>
        import('@/views/Login.vue'),
    meta: {
        pageTitle: 'Login',
        middleware: "guest",
        layout: 'full',
    },
},
{
    path: '/:url/login',
    name: 'Partnerlogin',
    component: () =>
        import('@/views/Login.vue'),
    meta: {
        pageTitle: 'Login',
        middleware: "guest",
        layout: 'full',
    },
    props: true,
},
{
    path: '/:url/register',
    name: 'Leadregister',
    component: () =>
        import('@/views/LeadRegister.vue'),
    meta: {
        pageTitle: 'register',
        middleware: "guest",
        layout: 'full',
    },
    props: true,
},
{
    path: '/:url/programs',
    name: 'Leadregister',
    component: () =>
        import('@/views/LeadPrograms.vue'),
    meta: {
        pageTitle: 'register',
        middleware: "guest",
        layout: 'full',
    },
    props: true,
},
{
    path: '/generate/password',
    name: 'generate-password4',
    component: () =>
        import('@/views/Password.vue'),
    meta: {
        pageTitle: 'Generate Password',
        layout: 'full',
        middleware: "guest",
    },
    props: true
},
{
    path: '/lead/programpayment/:id',
    name: 'programpayment',
    component: () =>
        import('@/views/programpayment.vue'),
    meta: {
        pageTitle: 'Payment',
        layout: 'full',
        middleware: "guest",
    },
    props: true
},
{
    path: '/error-404',
    name: 'error-404',
    component: () =>
        import('@/views/error/Error404.vue'),
    meta: {
        layout: 'full',
    },
},
{
    path: '*',
    redirect: 'error-404',
},



]

var router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    scrollBehavior() {
        return { x: 0, y: 0 }
    },
    routes: asyncRoutes

});

// ? For splash screen
// Remove afterEach hook if you are not using splash screen
router.afterEach(() => {
    // Remove initial loading
    const appLoading = document.getElementById('loading-bg')
    if (appLoading) {
        appLoading.style.display = 'none'
    }
})

router.beforeEach((to, from, next) => {
    document.pageTitle = `${to.meta.title} - ${process.env.MIX_APP_NAME}`
    if (to.meta.middleware == "guest") {
        if (store.state.auth.authenticated) {
            next({ name: "dashboard" })
        }
        next()
    } else {
        if (store.state.auth.authenticated) {
            store.dispatch('setPermissions');
            if (store.state.auth.user.role_id > 1 && store.state.auth.user.profile_updated == 0) {
                Vue.$toast({
                    component: ToastificationContent,
                    props: {
                        title: "Please update your profile first to use all features",
                        icon: 'BellIcon',
                        variant: 'info',
                    },
                });
                if (to.name !== "user-profile") {
                    next({ name: "user-profile" })
                }
            }
            next()

        } else {
            next({ name: "login" })
        }
    }
})

export default router
