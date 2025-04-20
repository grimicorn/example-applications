<div style="text-align:center;padding:20px;background:grey;color:white;font-weight:bold">
    @isset($subject)
        Subject: {{ $subject }}
    @endisset

    @isset($notificationLink)
        <br><br>
        <a
            style="color:white"
            target="_blank"
            href="{{ $notificationLink }}"
        >View Matching Notification &gt;</a>
    @endisset
</div>
