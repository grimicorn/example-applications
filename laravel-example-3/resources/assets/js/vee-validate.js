module.exports = function() {
    let VeeValidate = require('vee-validate');

    window.Vue.use(VeeValidate, {
        dictionary: {
            en: {
                messages: {
                    required: () => 'This field is required',
                    url: () => 'The field is not a valid URL.',
                },
            },
        },
    });
};
