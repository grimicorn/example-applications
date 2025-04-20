/**
 * date-fns
 * @see https://date-fns.org/
 * @see https://date-fns.org/docs/format
 */
import { format } from "date-fns";

export default function (date, outputFormat) {
    date = new Date(date);
    let localOffset = date.getTimezoneOffset();
    let timestamp = date.getTime() - localOffset * 60 * 1000;

    return format(new Date(timestamp), outputFormat);
}
