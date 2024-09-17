<table class="table table-bordered mt-4">
    <thead>
        <tr>
            <th>日</th>
            <th>月</th>
            <th>火</th>
            <th>水</th>
            <th>木</th>
            <th>金</th>
            <th>土</th>
        </tr>
    </thead>
    <tbody>
        @php
            $firstDayOfMonth = \Carbon\Carbon::create($selectedYear, $selectedMonth, 1);
            $daysInMonth = $firstDayOfMonth->daysInMonth;
            $startDay = $firstDayOfMonth->dayOfWeek;
            $dayCounter = 1;
        @endphp

        @for ($row = 0; $row < 6; $row++) <!-- 最大6行必要 -->
            <tr>
                @for ($col = 0; $col < 7; $col++) <!-- 1週間は7列 -->
                    @if (($row === 0 && $col < $startDay) || $dayCounter > $daysInMonth)
                        <td></td> <!-- 空のセル -->
                    @else
                        <td>
                            {{ $dayCounter }}
                            @php $dayCounter++; @endphp
                        </td>
                    @endif
                @endfor
            </tr>
        @endfor
    </tbody>
</table>
