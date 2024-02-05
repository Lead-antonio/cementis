<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>

<script type="text/javascript">
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
                        $('#rotation-report').html('<li class="list-group-item d-flex justify-content-between align-items-center"><h5>Le véhicule portant l\'immatriculation ' + selectedVehicle + ' a effectué : '+ data.totalHours +' heures de rotation</h5></li>');
                    }
                });
            }
        });
    });

</script>