<a
class="flex linked {{ (isset($last) and $last) ? '' : 'mb2' }}"
href="{{ $link }}">
    <div class="flex-auto">{{ $label }}</div>
    <div>{{ floatval($percentage) }}%</div>
</a>
