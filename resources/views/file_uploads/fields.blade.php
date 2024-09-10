<!-- File Upload Field -->
<div class="form-group col-sm-6">
    <div class="input-group">
        <div class="custom-file">
            {!! Form::file('excel_file', ['class' => 'custom-file-input', 'id' => 'fileUpload']) !!}
            {!! Form::label('excel_file', 'Choisir un fichier', ['class' => 'custom-file-label']) !!}
        </div>
    </div>
</div>
<div class="clearfix"></div>

<script>
document.getElementById('fileUpload').addEventListener('change', function(event) {
    var file = this.files[0];
    if (file) {
        var formData = new FormData();
        formData.append('excel_file', file);

        // Envoi du fichier via AJAX
        fetch('{{ route('fileUploads.read') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.redirect_url) {
                // Redirection vers la page avec les données traitées
                window.location.href = data.redirect_url;
            } else {
                console.error('Erreur lors de l\'upload du fichier.');
            }
        })
        .catch(error => console.error('Erreur:', error));
    }
});
</script>
