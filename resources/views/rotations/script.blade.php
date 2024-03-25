<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>

<script type="text/javascript">
    var translations = {
        vehicle_rotation_hours: {!! json_encode(__('models/rotations.fields.vehicle_rotation_hours')) !!}
    };

    $(document).ready(function() {
        $('#vehicleHandle').change(function() {
            var loader = $('#loader');
            var overlay = $('#overlay');
            loader.show();
            overlay.show();
            var selectedVehicle = $(this).val();
            if (selectedVehicle !== '') {
                $.ajax({
                    url: '{{ route("rotations.by.vehicle", ":vehicle") }}'.replace(':vehicle', selectedVehicle),
                    type: 'GET',
                    success: function(data) {
                        loader.hide();
                        overlay.hide();
                        var message = translations.vehicle_rotation_hours.replace(':vehicle', selectedVehicle).replace(':hours', data.totalHours);
                        $('#rotation-report').html('<li class="list-group-item d-flex justify-content-between align-items-center"><h5>' + message + '</h5></li>');
                    }
                });
            }
        });
    });

</script>