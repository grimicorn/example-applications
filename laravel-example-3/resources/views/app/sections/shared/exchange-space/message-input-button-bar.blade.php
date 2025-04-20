<div class="pt1 pb1 pl2 pr2 flex items-center">
    <div class="flex-auto text-right flex">
        <app-document-file-upload
        :hide-dropzone="true"
        button-class="btn-color5"
        :data-max-size-mb="7"
        :data-align-right="false"></app-document-file-upload>
    </div>

    <div>
        @if($isSpace)
        <a
        href="{{ route('exchange-spaces.conversations.index', [
            'id' => $space->id
        ]) }}"
        class="btn btn-color6 mr3">Cancel</a>
        @endif

        <button type="submit">Send</button>
    </div>
</div>
