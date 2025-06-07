@if (!empty($validationAlert))
    <script>
        alert("{!! addslashes($validationAlert) !!}");
    </script>
@endif
