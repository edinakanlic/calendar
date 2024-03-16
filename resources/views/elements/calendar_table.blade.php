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
                        <span id="absence_{{ $day['day'] }}">{{ $day['absence'] }}</span>
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
