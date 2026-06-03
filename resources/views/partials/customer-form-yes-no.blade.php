<option value="">—</option>
@foreach ($options as $key => $labels)
    <option value="{{ $key }}" data-label-ar="{{ $labels['ar'] }}" data-label-en="{{ $labels['en'] }}">{{ $labels['ar'] }}</option>
@endforeach
