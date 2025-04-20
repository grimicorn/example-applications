import { library, dom } from "@fortawesome/fontawesome-svg-core";
import {
    faArrowCircleUp,
    faArrowLeft,
    faCheck
} from "@fortawesome/free-solid-svg-icons";
import {
    faFacebook,
    faYoutube,
    faTwitter,
    faInstagram
} from "@fortawesome/free-brands-svg-icons";

/**
 * Loads the required Fontawesome icons
 *
 * @see https://fontawesome.com/how-to-use/on-the-web/advanced/svg-javascript-core
 */
export default function() {
    library.add(
        faArrowLeft,
        faArrowCircleUp,
        faCheck,
        faFacebook,
        faYoutube,
        faTwitter,
        faInstagram
    );
    dom.watch();
}
