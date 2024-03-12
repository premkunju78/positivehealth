export default [{
    title: 'Home',
    route: 'dashboard',
    icon: 'HomeIcon'
},
// {
//     title: 'Users',
//     icon: 'UsersIcon',
//     permission: ['view-users'],
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
    permission: ['view-clients']
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
// }
{
    title: 'Programs',
    icon: 'HeartIcon',
    permission: ['view-program'],
    children: [
        {
            title: 'View Programs',
            route: 'packages',
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
]
