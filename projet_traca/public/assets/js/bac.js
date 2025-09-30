$('#bac_create_matiere').change(function () {
    var matiereSelector = $(this);
    
    $.ajax({
        url: "/getCodejdeSac",
        type: "GET",
        dataType: "JSON",
        data: {
            matiereId: matiereSelector.val()
        },
        success: function (codejdes) {
            var codeSelect = $("#bac_create_codejde");
            
            codeSelect.html('');
            
            $.each(codejdes.data, function (key, codejde) {
                codeSelect.append('<option value="' + codejde.id + '">' + codejde.nom + '</option>');
            });
        },
        error: function (err) {
            alert("Une erreur s'est produite lors du chargement des données...");
        }
    });
});

