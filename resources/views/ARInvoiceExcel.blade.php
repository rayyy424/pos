<html>

<body>
    <table>
        <thead>
            <tr>
                @foreach($detail as $key=>$value)

                    @if ($key==0)

                    @foreach($value as $field=>$value)
                    <th>{{ $field }}</th>
                    @endforeach

                    @endif

                @endforeach

            </tr>
        </thead>
        <?php $i = 0; ?>
        @foreach($detail as $detail)

            <tr id="row_{{ $i }}" >
                @foreach($detail as $key=>$value)
                <td>
                    {{ $value }}
                </td>
                @endforeach
            </tr>
            <?php $i++; ?>

        @endforeach

    </table>

</body>
</html>
