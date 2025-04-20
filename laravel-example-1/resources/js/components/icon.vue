<template>
  <component
    :class="classes"
    :is="iconComponent"
    v-if="iconComponent"
  ></component>
  <svg
    v-else
    viewBox="53.587 60.636 242.081 242.081"
    xmlns="http://www.w3.org/2000/svg"
  >
    <path
      fill="none"
      stroke="#000"
      vector-effect="non-scaling-stroke"
      paint-order="stroke"
      stroke-width="2"
      d="M53.587 60.636h242.081v242.081H53.587z"
    />
  </svg>
</template>

<script>
import {
  XIcon,
  PlusCircleIcon,
  CheckIcon,
  ChevronDownIcon,
  ChevronUpIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  CogIcon,
  ChatAlt2Icon,
  StarIcon,
  PencilIcon,
  HeartIcon,
  LinkIcon,
  LocationMarkerIcon,
  PaperAirplaneIcon,
  LockClosedIcon,
  SaveIcon,
  TrashIcon,
  EyeIcon,
  ArrowUpIcon,
} from '@heroicons/vue/solid';
import {
  CheckCircleIcon,
  StarIcon as StarIconOutline,
  DuplicateIcon,
  ExclamationCircleIcon,
  InformationCircleIcon,
  MinusCircleIcon,
} from '@heroicons/vue/outline';

const icons = {
  'add-solid': PlusCircleIcon,
  'arrow-up': ArrowUpIcon,
  checkmark: CheckIcon,
  'checkmark-outline': CheckCircleIcon,
  'cheveron-down': ChevronDownIcon,
  'cheveron-up': ChevronUpIcon,
  'chevron-left': ChevronLeftIcon,
  'chevron-right': ChevronRightIcon,
  close: XIcon,
  cog: CogIcon,
  conversation: ChatAlt2Icon,
  'edit-copy': DuplicateIcon,
  'edit-pencil': PencilIcon,
  'exclamation-outline': ExclamationCircleIcon,
  heart: HeartIcon,
  'information-outline': InformationCircleIcon,
  link: LinkIcon,
  location: LocationMarkerIcon,
  'location-current': PaperAirplaneIcon,
  'lock-closed': LockClosedIcon,
  'minus-outline': MinusCircleIcon,
  save: SaveIcon,
  trash: TrashIcon,
  'view-show': EyeIcon,
  star: StarIcon,
  'star-outline': StarIconOutline,
};

const animationClasses = {
  none: 'animate-none',
  spin: 'animate-spin',
  ping: 'animate-ping',
  pulse: 'animate-pulse',
};

export default {
  props: {
    dataName: {
      type: String,
      required: true,
      validator(value) {
        return Object.keys(icons).includes(value) !== -1;
      },
    },

    dataAnimation: {
      type: String,
      default: 'none',
      validator(value) {
        return Object.keys(animationClasses).includes(value) !== -1;
      },
    },
  },

  data() {
    return {};
  },

  computed: {
    classes() {
      return [
        animationClasses[this.dataAnimation] ?? animationClasses['none'],
        this.dataName === 'location-current' ? 'transform rotate-45' : '',
      ].filter((value) => !!value);
    },

    iconComponent() {
      return icons[this.dataName] ?? undefined;
    },
  },

  methods: {},
};
</script>
