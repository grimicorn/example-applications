<template>
  <font-awesome-icon :icon="icon"></font-awesome-icon>
</template>

<script>
// How to add an icon
// 1. Install the icon set if needed (solic,brand,etc.).
// 2. Import the icon below from the correct set of icons (solic,brand,etc.).
// 3. Add the icon kebab cased name name to the iconName prop validator

import _foreach from "lodash.foreach";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
  faTshirt,
  faArrowRight,
  faTimesCircle,
  faCheck,
  faInfo,
  faExclamation,
  faTimes,
  faCaretUp,
  faCaretDown,
  faChevronRight,
  faChevronLeft,
  faChevronDown,
  faUserCircle,
  faPencilAlt,
  faChessBoard,
  faTint
} from "@fortawesome/free-solid-svg-icons";

library.add(
  faTshirt,
  faArrowRight,
  faTimesCircle,
  faCaretUp,
  faCaretDown,
  faCheck,
  faInfo,
  faExclamation,
  faTimes,
  faChevronRight,
  faChevronLeft,
  faChevronDown,
  faUserCircle,
  faPencilAlt,
  faChessBoard,
  faTint
);

export default {
  components: {
    FontAwesomeIcon
  },

  props: {
    dataName: {
      type: String,
      validator: function(value) {
        return (
          [
            "tshirt",
            "arrow-right",
            "times-circle",
            "info",
            "check",
            "exclamation",
            "times",
            "caret-up",
            "caret-down",
            "info",
            "check",
            "exclamation",
            "times",
            "chevron-right",
            "chevron-left",
            "chevron-down",
            "user-circle",
            "pencil-alt",
            "chess-board",
            "tint"
          ].indexOf(value) !== -1
        );
      }
    },

    dataPrefix: {
      type: String,
      default: "fas"
    }
  },

  data() {
    return {};
  },

  computed: {
    icon() {
      return [this.iconPrefix.toString(), this.iconName.toString()];
    },

    iconName() {
      return this.dataName;
    },

    iconPrefix() {
      return this.dataPrefix;
    }
  }
};
</script>
