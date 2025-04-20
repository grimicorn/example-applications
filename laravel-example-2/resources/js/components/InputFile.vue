<template>
  <div
    class="input-wrap input-file relative mb-6"
    :class="{
      'input-has-error': error,
      'input-disabled': disabled,
    }"
  >
    <div
      class="input-label-wrap"
      :class="{
        'mb-2': instructions
      }"
    >
      <label
        :for="id"
        v-text="dataLabel"
        class="block"
      ></label>

      <div v-if="showCurrentFile">
        <a
          :href="currentFile.full_url"
          v-text="currentFile.file_name"
          target="_blank"
        ></a>

        <button
          @click.stop="clearCurrentFile"
          class="text-gray-300"
        >
          <icon data-name="times-circle"></icon>
          <span class="sr-only">Cancel</span>
        </button>
      </div>

      <div
        v-else
        class="relative border border-dashed inline-block rounded-sm p-1"
      >
        <input
          ref="input"
          class="absolute opacity-0"
          :class="{
            'w-full h-full inset-0' : !fileUploaded,
            'w-0 h-0 top-0 left-0 pointer-events-none' : fileUploaded,
          }"
          :id="id"
          :name="name"
          type="file"
          :disabled="disabled"
          :readonly="readonly"
          :required="dataRequired"
          @change="handleChange($event)"
        >

        <div
          v-if="fileUploaded"
          class="flex items-center leading-tight"
        >
          <div>
            File <strong v-text="inputValue.name"></strong> selected.
            <br>
            Click <strong>Upload</strong> to accept.
          </div>

          <button
            @click.stop="reset"
            class="text-gray-300"
          >
            <icon data-name="times-circle"></icon>
            <span class="sr-only">Cancel</span>
          </button>
        </div>

        <div
          v-else
          class="text-sm text-gray-300"
        >
          <span class="inline-block pl-3 pr-2">Drop a file here to upload or</span>
          <span class="button ghost py-1 px-4 inline-block cursor-pointer">Browse</span>
        </div>
      </div>
    </div>

    <strong
      class="
        input-instructions"
      v-if="instructions"
      v-text="instructions"
    >
    </strong>

    <strong
      class="input-error"
      v-if="error"
      v-text="error"
    >
    </strong>

    <div v-show="!showCurrentFile">
      <button
        class="button inline-block mt-4"
        type="button"
        @click="save"
        :disabled="uploadDisabled"
      >Upload</button>
    </div>
  </div>
</template>

<script>
// ========================================================================
// ** Important ***********************************************************
// ** If it's not in here its defined in /resources/js/mixins/input.js
// ========================================================================

import InputMixin from "@mixins/input.js";
export default {
  mixins: [InputMixin],

  props: {
    dataCurrentFile: {
      type: Object
    }
  },

  data() {
    return {
      currentFile: this.dataCurrentFile,
      showCurrentFile: !!this.dataCurrentFile,
      shouldAutoSave: false
    };
  },

  computed: {
    fileUploaded() {
      return !!this.inputValue;
    },

    uploadDisabled() {
      if (this.dataDisabled) {
        return this.dataDisabled;
      }

      return !this.inputValue;
    }
  },

  methods: {
    clearCurrentFile() {
      let confirmation = confirm(
        `Are you sure you want to delete ${this.currentFile.file_name}.\nImportant: This action can not be reveresed!`
      );

      if (confirmation) {
        this.reset();
        this.showCurrentFile = false;
        this.save();
      }
    },

    reset() {
      if (this.$refs.input) {
        this.$refs.input.value = "";
      }

      this.inputValue = null;
    },

    handleSaveSuccess({ data }) {
      this.reset();
      this.showCurrentFile = !!data.data.media;
      this.currentFile = data.data.media ? data.data.media : null;
      this.$emit("save:success", this.currentFile);
    },

    handleChange(event) {
      this.inputValue = event.target.files[0];
    }
  }
};
</script>
