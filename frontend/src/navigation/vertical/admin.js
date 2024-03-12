export default [{
    title: 'Home',
    route: 'dashboard',
    icon: 'HomeIcon'
},
{
    title: 'Users',
    icon: 'UsersIcon',
    permission: ['view-clients'],
    children: [{
        title: 'Alliance Partners',
        route: 'alliance-partners',
        permission: ['view-alliance-partners'],
    },
    {
        title: 'Clients',
        route: 'clients',
        permission: ['view-clients'],
    },
    {
        title: 'Client Groups',
        route: 'clientgroups',
    },
    {
        title: 'Consultants',
        route: 'consultants',
        permission: ['view-users'],
    },
    ],
},
{
    title: 'Staffs',
    icon: 'UsersIcon',
    permission: ['view-clients'],
    children: [
        {
            title: 'Coordinators',
            route: 'coordinators',
            permission: ['view-coordinators'],
        },
        {
            title: 'Health Coach',
            route: 'healthcoaches',
            permission: ['view-healthcoaches'],
        },
        {
            title: 'CIS',
            route: 'cis',
            permission: ['view-healthcoaches'],
        },
        {
            title: 'Marketing Head',
            route: 'mh',
            permission: ['view-marketinghead'],
        },
        {
            title: 'Marketing Executive',
            route: 'me',
            permission: ['view-marketingexecutive'],
        },
        {
            title: 'Account Incharge',
            route: 'accountsincharge',
            permission: ['view-marketingexecutive'],
        },
        {
            title: 'Diagnostic Partner',
            route: 'dh',
            permission: ['view-diagnostichead'],
        },
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
    }],
},
{
    title: 'Programs',
    icon: 'HeartIcon',
    permission: ['view-program'],
    children: [
        {
            title: 'Create Programs',
            route: 'createpackages',
            permission: ['create-program']
        },
        {
            title: 'View Programs',
            route: 'packages',
            permission: ['view-program']
        },
        {
            title: 'Groups',
            route: 'packageGroups',
        },
    ],
},
{
    title: 'Lead',
    icon: 'UsersIcon',
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
    children: [{
        title: 'Availability',
        route: 'viewAvailability',
    },
    {
        title: 'Schedules',
        route: 'viewSchedules',
        permission: ['view-availability']
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
    icon: 'GiftIcon',
    route: 'discountCoupon',
    permission: ['view-discount-coupons'],
},
{
    title: 'General',
    icon: 'BookOpenIcon',
    children: [
        {
            title: 'Positive Affirmation',
            route: 'affirmations',
            permission: ['view-affirmation'],
        },
        {
            title: 'Accounts',
            permission: ['view-accounts'],
            children: [
                {
                    title: 'Programs',
                    route: 'accountPackages'
                },
                {
                    title: 'Consultations',
                    route: 'accountConsultation'
                },
            ],
        },
        {
            title: 'Queries',
            route: 'enquiries'
        },
        {
            title: 'Enquiries',
            route: 'program-enquiries'
        },
        {
            title: 'Testimonials',
        }
    ],
},
    // {
    //     title: 'Medical',
    //     icon: 'BriefcaseIcon',
    //     children: [
    //         {
    //             title: 'Questionaire',
    //             route: 'questionnaires',
    //             icon: 'BriefcaseIcon',
    //             permission: ['view-questionnaire'],

    //         },
    //         {
    //             title: 'Prescriptions',
    //             icon: 'FileIcon',
    //             route:'clientPrescription',
    //             permission: ['view-prescription'],
    //         },
    //         {
    //             title: 'Investigations',
    //             icon: 'FileIcon',
    //             route:'clientInvestigation',
    //             permission: ['view-investigation'],
    //         },
    //     ],

    // },

]
