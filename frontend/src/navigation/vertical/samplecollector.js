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
    title: 'Investigation',
    icon: 'PlusSquareIcon',
    permission: ['view-clients'],
    children: [
        {
            title: 'Bookings',
            route: 'all-investigations',
            permission: ['view-clients'],

        },
    ],
}
]
