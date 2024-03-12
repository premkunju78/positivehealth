export default [{
    title: 'Home',
    route: 'dashboard',
    icon: 'HomeIcon'
},
{
    title: 'Appointments',
    icon: 'CalendarIcon',
    permission: ['view-video-meeting'],
    children: [
        // {
        //     title: 'Schedules',
        //     route: 'schedules',
        //     permission: ['view-booking'],
        // },
        {
            title: "Today's Meeting",
            route: 'meetings',
            permission: ['view-video-meeting'],
        },
        // {
        //     title: 'Availability',
        //     route: 'availability',
        //     permission: ['view-appointments'],

        // },
        // {
        //     title: 'Workflow shedules',
        //     route: 'workflowschedules',
        //     permission: ['view-workflows'],
        // },
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
    title: 'Medical',
    icon: 'PlusCircleIcon',
    children: [
        {
            title: 'Forms',
            route: 'questionnaires',
            permission: ['view-questionnaire'],

        },
        {
            title: 'Questionnaire',
            route: 'htmlquestionnaires',
        },
        {
            title: 'Prescriptions',
            route: 'clientPrescription',
            permission: ['view-prescription'],
        },
        {
            title: 'Investigations',
            route: 'clientInvestigation',
            permission: ['view-investigation'],
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
{
    title: 'Video',
    icon: 'VideoIcon',
    permission: ['view-video'],
    children: [
        {
            title: 'My Video'
        },
        {
            title: 'PreRecorded Video',
            route: 'video',
        },
        {
            title: 'Groups',
            route: 'groups',
        },

    ],
},
// {
//     title: 'Investigations',
//     icon: 'FileIcon',
//     route:'clientInvestigation',
//     permission: ['view-investigation'],
// },
// {
//     title: 'Prescriptions',
//     icon: 'FileIcon',
//     route:'clientPrescription',
//     permission: ['view-prescription'],
// },
{
    title: 'League Consultants',
    route: 'Consultations',
    icon: 'UsersIcon',
    permission: ['view-consultations'],
},

{
    title: 'Programs',
    icon: 'HeartIcon',
    permission: ['view-pkgs'],
    children: [
        {
            title: 'All Programs',
            route: 'viewPackages'
        },
        {
            title: 'My Programs',
            route: 'myPackages',
        }
    ],
},
{
    title: 'Discount Coupons',
    icon: 'DollarSignIcon',
    route: 'discountCoupon',
    permission: ['view-discount-coupons'],
},

{
    title: 'General',
    icon: 'BookOpenIcon',
    children: [
        {
            title: 'Reminder',
            route: 'reminders',
        },
        {
            title: 'Queries/Enquiries',
            route: 'enquiries',
        },
        {
            title: 'Chat',
            route: 'chat',
        },
        {
            title: 'Payments',

        },
        {
            title: 'Feedback',
            route: 'feedback',
        },
    ],
},

]
