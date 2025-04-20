module.exports = {
    methods: {
        formChange(form) {
            this.form.plan = form.plan;
            this.form.address = form.address;
            this.form.address_line_2 = form.address_line_2;
            this.form.city = form.city;
            this.form.country = form.country;
            this.form.state = form.state;
            this.form.zip = form.zip;
        },

        cardFormChange(cardForm) {
            this.cardForm.cvc = cardForm.cvc;
            this.cardForm.name = cardForm.name;
            this.cardForm.number = cardForm.number;
            this.cardForm.month = cardForm.month;
            this.cardForm.name = cardForm.name;
            this.cardForm.number = cardForm.number;
            this.cardForm.year = cardForm.year;
        },
    },
};
