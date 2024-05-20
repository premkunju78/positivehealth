export default [{
    title: 'Home',
    route: 'dashboard',
    icon: 'HomeIcon'
},
{
    title: 'Client',
    icon: 'UsersIcon',
    route: 'clients',
},
{
    title: 'League Consultants',
    route: 'Consultations',
    icon: 'UserCheckIcon',
},
{
    title: 'Program',
    icon: 'ListIcon',
    route: 'packages',
},
{
    title: 'Leads',
    icon: 'UserMinusIcon',
    route: 'leads',
    permission: ['view-leads']
},
{
    title: 'Questionnaire',
    icon: 'BriefcaseIcon',
    permission: ['view-questionnaire'],
    children: [
        {
            title: 'Form Builder',
            route: 'questionnaires',
        },
    ],
},
{
    title: 'General',
    icon: 'VideoIcon',
    children: [
        {
            title: 'Reminder',
            route: 'reminders',
        },
        {
            title: 'Accounts',
            route: 'accountPackages'
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
            title: 'Feedback',
            route: 'feedback',
        },
    ],
},
    // {
    //     title: 'Appointments',
    //     icon: 'CalendarIcon',
    //     permission: ['view-video-meeting'],
    //     children: [{
    //         title: 'Availability',
    //         route: 'availability',
    //         permission: ['view-appointments'],

    //     },
    //     {
    //         title: 'Schedules',
    //         route: 'schedules',
    //         permission: ['view-booking'],

    //     },
    //     {
    //         title: 'Schedules',
    //         route: 'schedules',
    //         permission: ['view-appointments'],

    //     },
    //     {
    //         title: "Today's Meeting",
    //         route: 'meetings',
    //         permission: ['view-video-meeting'],

    //     },
    //     ],

    // },
    // {
    //     title: 'Users',
    //     icon: 'UsersIcon',
    //     permission: ['view-clients'],
    //     children: [{
    //         title: 'Alliance Partners',
    //         route: 'alliance-partners',
    //         permission: ['view-alliance-partners'],
    //     },
    //     {
    //         title: 'Clients',
    //         route: 'clients',
    //         permission: ['view-clients'],
    //     },
    //     ],
    // },
    // {
    //     title: 'Settings',
    //     icon: 'SettingsIcon',
    //     children: [{
    //         title: 'Roles & Permission',
    //         route: 'settings-role-list',
    //         permission: ['view-roles']
    //     },],
    // },

    // {
    //     title: 'Medical',
    //     icon: 'BriefcaseIcon',
    //     children: [
    //         {
    //             title: 'Questionnaire',
    //             route: 'questionnaire',
    //             permission: ['view-questionnaire'],

    //         },
    //         {
    //             title: 'Prescriptions',
    //             route: 'clientPrescription',
    //             permission: ['view-prescription'],
    //         },
    //         {
    //             title: 'Investigations',
    //             route: 'clientInvestigation',
    //             permission: ['view-investigation'],
    //         },
    //     ],

    // },
    // {
    //     title: 'Diet',
    //     icon: 'FigmaIcon',
    //     permission: ['view-diet-plan'],
    //     children: [

    //         {
    //             title: 'Plan',
    //             route: 'diet-plan',
    //             permission: ['view-diet-plan']
    //         },
    //         {
    //             title: 'Recipe',
    //             route: 'recipes',
    //             permission: ['view-recipes']
    //         },
    //     ],

    // },
    // // {
    // //     title: 'Investigations',
    // //     icon: 'FileIcon',
    // //     route:'clientInvestigation',
    // //     permission: ['view-investigation'],
    // // },
    // // {
    // //     title: 'Prescriptions',
    // //     icon: 'FileIcon',
    // //     route:'clientPrescription',
    // //     permission: ['view-prescription'],
    // // },
    // {
    //     title: 'CaseSheets',
    //     icon: 'PaperclipIcon',
    //     route: 'casesheets',
    //     permission: ['view-case-sheet'],
    // },
    // {
    //     title: 'Programs',
    //     icon: 'PackageIcon',
    //     permission: ['view-program'],
    //     children: [
    //         {
    //             title: 'Programmes',
    //             route: 'packages',
    //         },
    //         {
    //             title: 'Groups',
    //             route: 'packageGroups',
    //         },
    //     ],
    // },
    // {
    //     title: 'Video',
    //     icon: 'VideoIcon',
    //     permission: ['view-video'],
    //     children: [{
    //         title: 'Groups',
    //         route: 'groups',
    //     },
    //     {
    //         title: 'Videos',
    //         route: 'video',
    //     },
    //     ],
    // },

    // // {
    // //     title: 'Questionaire',
    // //     route: 'questionnaire',
    // //     icon: 'BriefcaseIcon',
    // //     permission: ['view-questionnaire'],

    // // },


    // {
    //     title: 'Programmes',
    //     icon: 'PackageIcon',
    //     permission: ['view-pkgs'],
    //     children: [
    //         {
    //             title: 'All Programmes',
    //             route: 'viewPackages'
    //         },
    //         {
    //             title: 'My Programmes',
    //             route: 'myPackages',
    //         }
    //     ],
    // },


    // {
    //     title: 'Appointment',
    //     icon: 'CalendarIcon',
    //     permission: ['view-availability'],
    //     children: [{
    //         title: 'Availability',
    //         route: 'viewAvailability',
    //         permission: ['view-availability']

    //     },
    //     {
    //         title: 'Schedules',
    //         route: 'viewSchedules',

    //     },
    //     ],

    // },


    // {
    //     title: 'Leads',
    //     icon: 'UserIcon',
    //     permission: ['view-lead'],
    //     children: [{
    //         title: 'General',
    //         route: 'generalLeads',
    //         permission: ['view-lead']
    //     }, {
    //         title: 'Website',
    //         route: 'websiteLeads',
    //         permission: ['view-lead']
    //     },],
    // },
    // {
    //     title: 'Discount Coupons',
    //     icon: 'DollarSignIcon',
    //     route: 'discountCoupon',
    //     permission: ['view-discount-coupons'],
    // },


    // {
    //     title: 'Affirmations',
    //     icon: 'TypeIcon',
    //     route: 'affirmations',
    //     permission: ['view-affirmation'],
    // },

    // {
    //     title: 'Configurations',
    //     icon: 'SettingsIcon',
    //     permission: ['view-investigation-config'],
    //     children: [
    //         {
    //             title: 'Category',
    //             route: 'testCategory',
    //             permission: ['create-investigation-config'],

    //         },
    //         {
    //             title: 'Item',
    //             route: 'testItem',
    //             permission: ['create-investigation-config'],

    //         },
    //     ],
    // },


]
