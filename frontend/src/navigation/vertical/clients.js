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
            title: 'Today\'s Appointments',
            route: 'todays-schedules',

        },
        {
            title: 'Upcoming Appointments',
            route: 'upcoming-schedules',

        },
        {
            title: 'Past Appointments',
            route: 'past-schedules',
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
            title: 'Calendar',
            route: 'workflowcalendars',
        },
        {
            title: 'Workshop Calendar',
            route: 'workshopcalendars',
        },
    ],
},
{
    title: 'My Subscriptions',
    icon: 'PlusCircleIcon',
    children: [
        {
            title: 'Diet',
            route: 'diet-plan',
            permission: ['view-diet-plan']
        },
        {
            title: 'Recipe',
            route: 'recipes',
            permission: ['view-recipes']
        },
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
        {
            title: 'Forms',
            route: 'questionnaires',
            permission: ['view-questionnaire'],

        },
    ],

},
{
    title: 'My Records',
    icon: 'TargetIcon',
    permission: ['view-diet-plan'],
    children: [
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
        {
            title: 'Case Notes',
            route: 'diet-plan',
            permission: ['view-diet-plan']
        }
    ],

},
// {
//     title: 'Video',
//     icon: 'VideoIcon',
//     permission: ['view-video'],
//     children: [
//         {
//             title: 'My Video'
//         },
//         {
//             title: 'PreRecorded Video',
//             route: 'video',
//         },
//         {
//             title: 'Groups',
//             route: 'groups',
//         },

//     ],
// },
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
    title: 'Health Team',
    icon: 'UsersIcon',
    permission: ['view-consultations'],
    children: [
        {
            title: 'My Favourite',
            route: 'Consultations'
        }
    ],
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
    title: 'Settings',
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
{
    title: 'Orders',
    icon: 'CreditCardIcon',
    children: [
        {
            title: 'Diagnostics',
            route: 'reminders',
        },
        {
            title: 'Insurance',
            route: 'reminders',
        },
        {
            title: 'Invoices',
            route: 'reminders',
        },
        {
            title: 'Store',
            route: 'reminders'
        }
    ],
},

]
