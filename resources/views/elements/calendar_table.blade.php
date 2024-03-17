<table class="calendar-table">
    <thead>
        <tr>
            @foreach ($daysOfWeek as $dayName)
                <th>{{ $dayName }}</th>
            @endforeach
        </tr>
    </thead>

    <tbody>
        @foreach ($weeks as $week)
            <tr>
                @foreach ($week as $day)
                    <td>
                        {{ $day['day'] }}
                        <br>
                        @if ($day['archived'] == 0)
                            <span id="absence_{{ $day['day'] }}">{{ $day['absence'] }}</span>
                        @else
                            <span id="absence_{{ $day['day'] }}" class="archived">{{ $day['absence'] }} (arhivirano)</span>
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
