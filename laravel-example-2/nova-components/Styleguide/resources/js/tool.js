Nova.booting((Vue, router, store) => {
    router.addRoutes([
        {
            name: 'styleguide',
            path: '/styleguide',
            component: require('./components/Tool'),
        },
    ])
})
