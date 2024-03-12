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

    ],
},
]
