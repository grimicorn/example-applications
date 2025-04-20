let utilities = require('./utilities.js');

module.exports = {
  filters: {
    price: utilities.methods.formatPrice,

    date: utilities.methods.formatDate,

    datetime: utilities.methods.formatDatetime,

    stringTrim: utilities.methods.stringTrim,
  },
};
