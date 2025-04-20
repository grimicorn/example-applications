@php
$year = date('Y');
@endphp
<tr>
    <td>
        <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0">
            <tr>
                <td class="content-cell" align="center">
                {{ Illuminate\Mail\Markdown::parse(
                    "© {$year} The Firm Exchange LLC. All Rights Reserved."
                ) }}
                </td>
            </tr>
        </table>
    </td>
</tr>
