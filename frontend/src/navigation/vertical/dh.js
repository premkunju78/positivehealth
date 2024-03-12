export default [{
    title: 'Home',
    route: 'dashboard',
    icon: 'HomeIcon'
},
{
    title: 'Clients',
    icon: 'UsersIcon',
    permission: ['view-clients'],
    route: 'clients',
},
{
    title: 'Staffs',
    icon: 'UsersIcon',
    permission: ['view-samplecollector'],
    children: [
        {
            title: 'Sample Collectors',
            route: 'samplecollector',
            permission: ['view-samplecollector'],
        },
        {
            title: 'Data Entry User',
            route: 'dataentryuser',
            permission: ['view-dataentryuser'],
        },
    ],
},
{
    title: 'Settings',
    icon: 'SettingsIcon',
    children: [{
        title: 'Roles & Permission',
        route: 'settings-role-list',
        permission: ['view-roles']
    },],
},
{
    title: 'Programs',
    icon: 'HeartIcon',
    permission: ['view-program'],
    children: [
        {
            title: 'View Programs',
            route: 'packages',
        },
        {
            title: 'Groups',
            route: 'packageGroups',
        },
    ],
},
{
    title: 'Lead',
    icon: 'UserIcon',
    route: 'leads',
    permission: ['view-leads']
},
// {
//     title: 'Leads',
//     icon: 'FilterIcon',
//     permission: ['view-leads'],
//     children: [{
//         title: 'General',
//         route: 'generalLeads',
//         permission: ['view-leads']
//     }, {
//         title: 'Website',
//         route: 'websiteLeads',
//         permission: ['view-leads']
//     },],
// },
{
    title: 'Video',
    icon: 'VideoIcon',
    permission: ['view-video'],
    children: [
        {
            title: 'Video Gallery',
            route: 'video',
        },
        {
            title: 'Groups',
            route: 'groups',
        },

    ],
},
{
    title: 'Appointment',
    icon: 'CalendarIcon',
    permission: ['view-appointments'],
    children: [{
        title: 'Availability',
        route: 'viewAvailability',
        permission: ['view-availability']

    },
    {
        title: 'Today Schedules',
        route: 'todayWorkflowSchedules',
    },
    {
        title: 'Session schedules',
        route: 'workflowschedules',
    },
    {
        title: 'Calendar',
        route: 'workflowcalendars',
    },
    ],

},
{
    title: 'Investigation',
    icon: 'PlusSquareIcon',
    permission: ['view-investigation-config'],
    children: [
        {
            title: 'Category',
            route: 'testCategory',
            permission: ['create-investigation-config'],

        },
        {
            title: 'Sub-Category',
            route: 'testItem',
            permission: ['create-investigation-config'],

        },
    ],
},
{
    title: 'Questionnaire',
    icon: 'BriefcaseIcon',
    permission: ['view-questionnaire'],
    children: [
        {
            title: 'HTML',
            route: 'htmlquestionnaires',
        },
        {
            title: 'Form Builder',
            route: 'questionnaires',
        },
    ],
},
{
    title: 'Diet',
    icon: 'TargetIcon',
    permission: ['view-diet-plan'],
    children: [

        {
            title: 'Plan',
            route: 'diet-plan',
            permission: ['view-diet-plan']
        },
        {
            title: 'Recipe',
            route: 'recipes',
            permission: ['view-recipes']
        },
    ],

},

// {
//     title: 'CaseSheets',
//     icon: 'PaperclipIcon',
//     route: 'casesheets',
//     permission: ['view-case-sheet'],
// },
// {
//     title: 'Consultants',
//     route: 'Consultations',
//     icon: 'UsersIcon',
//     permission: ['view-consultations'],
// },
// {
//     title: 'Questionaire',
//     route: 'questionnaires',
//     icon: 'BriefcaseIcon',
//     permission: ['view-questionnaire'],

// },
{
    title: 'Discount Coupons',
    icon: 'DollarSignIcon',
    route: 'discountCoupon',
    permission: ['view-discount-coupons'],
}
]
