<template>
  <div>
    <div class="flex items-center mb-3">
      <label :for="id" v-text="dataLabel"></label>
      <div class="flex items-center ml-auto">
        <button
          type="button"
          @click="showPreview = false"
          :class="{
            'underline text-primary-900 font-bold': !showPreview,
            'text-gray-600': showPreview,
          }"
          :disabled="dataDisabled"
        >
          Edit
        </button>
        <span class="block mx-1">|</span>
        <button
          type="button"
          @click="showPreview = true"
          :disabled="dataDisabled"
          :class="{
            'underline text-primary-900 font-bold': showPreview,
            'text-gray-600': !showPreview,
          }"
        >
          Preview
        </button>
      </div>
    </div>
    <div v-show="!showPreview">
      <textarea
        ref="editor"
        :name="dataName"
        :id="attributes.id ? attributes.id : dataName"
        :value="currentValue"
        class="w-full resize-y"
        :cols="attributes.cols ? attributes.cols : 30"
        :rows="defaultRows"
        @input="update"
        v-bind="attributes"
        :disabled="dataDisabled"
      ></textarea>
    </div>
    <markdown
      :data-content="compiledMarkdown"
      v-show="showPreview"
      class="py-2"
      :style="{
        'min-height': `${defaultRows}em`,
      }"
    ></markdown>
  </div>
</template>

<script>
import marked from "marked";
import _debounce from "lodash.debounce";
import SimpleMDE from "simplemde";
import "~/simplemde/dist/simplemde.min.css";

export default {
  props: {
    value: {
      type: String,
      default: "",
    },
    dataName: {
      type: String,
      required: true,
    },
    dataId: {
      type: String,
    },
    dataLabel: {
      type: String,
      required: true,
    },
    dataInputAttributes: {
      type: Object,
      default() {
        return {};
      },
    },
    dataDisabled: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      showPreview: false,
      currentValue: this.value,
    };
  },

  computed: {
    id() {
      return this.dataId ? this.dataId : this.dataName;
    },
    compiledMarkdown: function () {
      return marked(this.currentValue ?? "");
    },

    attributes() {
      return this.dataInputAttributes;
    },

    defaultRows() {
      if (!this.currentValue) {
        return 5;
      }
      return Math.ceil(this.currentValue.length / 100) + 3;
    },
  },

  methods: {
    update: _debounce(function (e) {
      this.currentValue = e.target.value;
      this.$emit("input", this.currentValue);
    }, 300),

    initializeSimpleMde() {
      const $simplemde = new SimpleMDE({
        element: this.$refs["editor"],
        forceSync: true,
        initialValue: this.currentValue,
        indentWithTabs: false,
        spellChecker: false,
        hideIcons: ["preview", "side-by-side"],
        showIcons: ["code", "table", "horizontal-rule"],
        insertTexts: {
          horizontalRule: ["", "\n\n-----\n\n"],
          image: ["![](https://", ")"],
          link: ["[", "](https://)"],
          table: [
            "",
            "\n\n| Column 1 | Column 2 | Column 3 |\n| -------- | -------- | -------- |\n| Text     | Text      | Text     |\n\n",
          ],
        },
      });

      $simplemde.codemirror.on("change", () => {
        this.update({
          target: {
            value: $simplemde.value(),
          },
        });
      });
    },
  },

  watch: {
    value(value) {
      this.currentValue = value;
    },

    currentValue(value) {
      this.$emit("input", value);
    },
  },

  mounted() {
    this.initializeSimpleMde();
  },
};
</script>
