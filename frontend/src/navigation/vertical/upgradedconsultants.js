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
    {
        title: 'Schedules',
        route: 'schedules',
        permission: ['view-booking'],

    },
    {
        title: 'Today\'s Appointments',
        route: 'todays-schedules',
        permission: ['view-appointments'],

    },
    {
        title: 'Upcoming Appointments',
        route: 'upcoming-schedules',
        permission: ['view-appointments'],

    },
    {
        title: 'Past Appointments',
        route: 'past-schedules',
        permission: ['view-appointments'],

    },
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
    title: 'Availability',
    route: 'availability',
    icon: 'CalendarIcon',
    permission: ['view-appointments'],
},
// {
//     title: 'Users',
//     icon: 'UsersIcon',
//     permission: ['view-clients'],
//     children: [
//         {
//             title: 'Clients',
//             route: 'clients',
//             permission: ['view-clients'],
//         },
//     ],
// },
{
    title: 'Clients',
    icon: 'UsersIcon',
    route: 'clients',
},
{
    title: 'Lead',
    icon: 'UserIcon',
    route: 'leads',
    permission: ['view-leads']
},
{
    title: 'Programs',
    icon: 'HeartIcon',
    permission: ['view-program'],
    children: [
        {
            title: 'Programs',
            route: 'packages',
        },
        // {
        //     title: 'Groups',
        //     route: 'packageGroups',```
        // },
    ],
},
{
    title: 'League Consultants',
    icon: 'UsersIcon',
    route: 'Consultations',
},
{
    title: 'Questionnaire',
    route: 'questionnaires',
    icon: 'BriefcaseIcon',
    permission: ['view-questionnaire'],

},

{
    title: 'Diet',
    icon: 'FigmaIcon', 
    permission: ['view-recipes'],
    children: [

        // {
        //     title: 'Plan',
        //     route: 'diet-plan',
        //     icon: 'HomeIcon',
        //     permission: ['view-diet-plan']
        // },
        {
            title: 'Recipe',
            route: 'recipes',
            icon: 'ApertureIcon',
            permission: ['view-recipes']
        },
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
            title: 'Accounts',
            permission: ['view-accounts'],
            children: [
                {
                    title: 'Programs',
                    route: 'accountPackages'
                },
            ],
        },
        {
            title: 'Queries',
            children: [
                {
                    title: 'Ask Query',
                    route: 'ask-query',
                },
                {
                    title: 'List',
                    route: 'enquiries',
                },
            ]
        },
        {
            title: 'Testimonials',
        },
    ],
},
{
    title: 'Affirmations',
    icon: 'TypeIcon',
    route: 'affirmations',
    permission: ['view-affirmation'],
},

{
    title: 'Configurations',
    icon: 'SettingsIcon',
    permission: ['view-investigation-config'],
    children: [
        {
            title: 'Category',
            route: 'testCategory',
            permission: ['create-investigation-config'],

        },
        {
            title: 'Item',
            route: 'testItem',
            permission: ['create-investigation-config'],

        },
    ],
},


]
