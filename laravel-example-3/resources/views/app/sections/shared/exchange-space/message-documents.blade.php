@if(!$message->documents->isEmpty())
<strong class="block">Documents</strong>
    @foreach($message->documents as $document)
    <div class="attached-file">
        <app-file-preview-link
        label="{{ $document->file_name }}"
        mime-type="{{ $document->mime_type }}"
        url="{{ $document->getFullUrl() }}"></app-file-preview-link>
    </div>
    @endforeach
@endif
