$('#control_create_silo').change(function () {
    var siloSelector = $(this);
    
    // Request the neighborhoods of the selected city.
    $.ajax({
        url: "/getCuves",
        type: "GET",
        dataType: "JSON",
        data: {
            siloId: siloSelector.val()
        },
        success: function (cuves) {
            var cuveSelect = $("#control_create_cuve");
            var atelierInput = $("#control_create_atelier");
            atelierInput.val(cuves.atelier);
            // Remove current options
            cuveSelect.html('');
            
            // Empty value ...
            cuveSelect.append('<option value> Selection des cuves du silo ' + siloSelector.find("option:selected").text() + ' ...</option>');
            
            $.each(cuves.data, function (key, cuve) {
                cuveSelect.append('<option value="' + cuve.id + '">' + cuve.numero + '</option>');
            });
        },
        error: function (err) {
            alert("Une erreur s'est produite lors du chargement des données...");
        }
    });
});

$('#control_create_matiere').change(function () {
    var matiereSelect = $(this);
    
    $.ajax({
        url: "/getMatiere",
        type: "GET",
        dataType: "JSON",
        data: {
            matiereId: matiereSelect.val()
        },
        success: function (codejdes) {
            var codeSelect = $("#control_create_codejde");
            
            // Remove current options
            codeSelect.html('');
            
            // Empty value ...
            $.each(codejdes.data, function (key, codejde) {
                codeSelect.append('<option value="' + codejde.id + '">' + codejde.code + '</option>');
            });
        },
        error: function (err) {
            alert("Une erreur s'est produite lors du chargement des données...");
        }
    });
});  