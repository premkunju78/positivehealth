export default [{
        title: 'Dashboard',
        route: 'dashboard',
        icon: 'HomeIcon'
    },
    {
        title: 'Appointments',
        icon: 'CalendarIcon',
        permission: ['view-video-meeting'],
        children: [{
                title: 'Availability',
                route: 'availability',
                permission: ['view-appointments'],

            },
            {
                title: 'Schedules',
                route: 'schedules',
                permission: ['view-booking'],

            },
            {
                title: 'Schedules',
                route: 'schedules',
                permission: ['view-appointments'],

            },
            {
                title: "Today's Meeting",
                route: 'meetings',
                permission: ['view-video-meeting'],

            },
        ],

    },
    {
        title: 'Peoples',
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
                title: 'Consultants',
                route: 'consultants',
                permission: ['view-users'],
            },
            {
                title: 'Client Consultants',
                route: 'AllianConsultant',
                permission: ['view-client-consultants'],
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
        }, ],
    },
   
    {
        title: 'Medical',
        icon: 'BriefcaseIcon',
        permission: ['view-video-meeting'],
        children: [
            {
                title: 'Questionaire',
                route: 'questionnaires',
                icon: 'BriefcaseIcon',
                permission: ['view-questionnaire'],
        
            },
            {
                title: 'Prescriptions',
                icon: 'FileIcon',
                route:'clientPrescription',
                permission: ['view-prescription'],
            },
            {
                title: 'Investigations',
                icon: 'FileIcon',
                route:'clientInvestigation',
                permission: ['view-investigation'],
            },
        ],

    },
    {
        title: 'Diet',
        icon: 'FigmaIcon',
        permission: ['view-diet-plan'],
        children: [

            {
                title: 'Plan',
                route: 'diet-plan',
                icon: 'HomeIcon',
                permission: ['view-diet-plan']
            },
            {
                title: 'Recipe',
                route: 'recipes',
                icon: 'ApertureIcon',
                permission: ['view-recipes']
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
        title: 'CaseSheets',
        icon: 'PaperclipIcon',
        route: 'casesheets',
        permission: ['view-case-sheet'],
    },
    {
        title: 'Programs',
        icon: 'PackageIcon',
        permission: ['view-program'],
        children: [
          {
              title: 'Programmes',
              route: 'packages',
          },
          {
              title: 'Groups',
              route: 'packageGroups',
          },
        ],
    },
    {
        title: 'Video',
        icon: 'VideoIcon',
        permission: ['view-video'],
        children: [{
                title: 'Groups',
                route: 'groups',
            },
            {
                title: 'Videos',
                route: 'video',
            },
        ],
    },
    {
        title: 'Consultants',
        route: 'Consultations',
        icon: 'UsersIcon',
        permission: ['view-consultations'],
    },
    // {
    //     title: 'Questionaire',
    //     route: 'questionnaires',
    //     icon: 'BriefcaseIcon',
    //     permission: ['view-questionnaire'],

    // },


    {
        title: 'Programmes',
        icon: 'PackageIcon',
        permission: ['view-pkgs'],
        children: [
          {
              title: 'All Programmes',
              route: 'viewPackages'
          },
          {
              title: 'My Programmes',
              route: 'myPackages',
          }
        ],
    },


    {
        title: 'Appointment',
        icon: 'CalendarIcon',
        permission: ['view-availability'],
        children: [{
                title: 'Availability',
                route: 'viewAvailability',
                permission: ['view-availability']

            },
            {
                title: 'Schedules',
                route: 'viewSchedules',

            },
        ],

    },

    {
        title: 'Lead',
        icon: 'UserIcon',
        route: 'leads',
        permission: ['view-leads']
    },
    {
        title: 'Leads',
        icon: 'UserIcon',
        permission: ['view-lead'],
        children: [{
            title: 'General',
            route: 'generalLeads',
            permission: ['view-lead']
        },{
            title: 'Website',
            route: 'websiteLeads',
            permission: ['view-lead']
        }, ],
    },
    {
        title: 'Discount Coupons',
        icon: 'DollarSignIcon',
        route: 'discountCoupon',
        permission: ['view-discount-coupons'],
    },
    {
        title: 'General',
        icon: 'VideoIcon',
        permission: ['view-video'],
        children: [
            {
                title: 'Reminder',
                route: 'reminders',
                icon: 'BellIcon'
            },
            {
                title: 'Accounts',
                icon: 'DollarSignIcon',
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
                route:'testCategory',
                permission: ['create-investigation-config'],

            },
            {
                title: 'Item',
                route:'testItem',
                permission: ['create-investigation-config'],

            },
        ],
    },


]
