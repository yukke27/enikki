<div class="mx-16 h-full">
    <p class="text-5xl font-black my-20">{{ $selectedMonth }}</p>
    <table class="table text-xs table-fix w-full my-auto">
        <thead>
            <tr class="h-20 text-left align-top">
                <th>S</th>
                <th>M</th>
                <th>T</th>
                <th>W</th>
                <th>T</th>
                <th>F</th>
                <th>S</th>
            </tr>
        </thead>
        <tbody>
            @php
                $firstDayOfMonth = \Carbon\Carbon::create($selectedYear, $selectedMonth, 1);
                $daysInMonth = $firstDayOfMonth->daysInMonth;
                $startDay = $firstDayOfMonth->dayOfWeek;
                $dayCounter = 1;
            @endphp

            @for ($row = 0; $row < 6; $row++) <!-- 最大6行 -->
                <tr class="font-bold h-20 text-left align-top">
                    @for ($col = 0; $col < 7; $col++) <!-- 1週間は7列 -->
                        @if (($row === 0 && $col < $startDay) || $dayCounter > $daysInMonth)
                            <td></td> <!-- 空のセル -->
                        @else
                            <!-- 投稿のある日付をクリック可能にする -->
                            @php $isClickable = in_array($dayCounter, $datesWithPosts); @endphp
                            <td class="{{ $isClickable ? 'cursor-pointer' : '' }}" 
                                onclick="{{ $isClickable ? "handleDateClick()" : '' }}">
                                {{ $dayCounter }}
                                @if ($isClickable)
                                    <div class="text-xs text-gray-500">・</div> <!-- 追加するテキスト -->
                                @endif
                                @php $dayCounter++; @endphp
                            </td>
                        @endif
                    @endfor
                </tr>
            @endfor
        </tbody>
    </table>
</div>

<script>
    function handleDateClick(day, month, year) {
        window.location.href = `/diaries/gallery?date=${year}-${month}-${day}`;
    }
</script>