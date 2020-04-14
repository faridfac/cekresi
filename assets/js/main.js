function validasi_form() {
    var oke = true;
    $("#form_search input").each(function() {
        if ($(this).val().length==0 || $(this).val()==0) {
            alert("Ada kesalahan, Silahkan isi no resi dengan valid!");
            $(this).focus();
            oke = false;
            return false;
        }
    });
    if (oke) return 'oke';
}

function cek_resi() {
    if (validasi_form()!="oke") {
        return false;
    }

    var ser_data = $("#form_search input,#form_search select").serialize();

    $.ajax({
        type: "POST",
        url: "tracking",
        data: ser_data,
        success: function(msg){
            $("#btnCheck").show();
            $("#btnSearch").addClass("hidden");
            $("#form_search input").removeAttr("disabled");

            var err = msg.search(/error/i);
            if (err >= 0) {
                //alert(msg);
                alert("Ada kesalahan, silahkan refresh browser dan ulangi kembali.");
            } else {
                $("#resi_result").html(msg);
                $("html, body").animate({
                        //scrollTop: $("#resi_result").offset().top
                        scrollTop: $("#form_result").offset().top
                    }, 1000);

                //window.location.hash="#tarifresult";
            }
        }, beforeSend : function(a) {
            $("#btnCheck").hide();
            $("#btnSearch").removeClass("hidden");
            $("#form_search input").attr("disabled","disabled");
        }, complete : function(a) {
            //completed
            //grecaptcha.reset(widgetJne);
        }, error: function (xhr, ajaxOptions, thrownError) {
            $("#btnCheck").show();
            $("#btnSearch").addClass("hidden");
            $("#form_search input").removeAttr("disabled");
                alert("Ada kesalahan, silahkan refresh browser dan ulangi kembali.");
            //alert(xhr.responseText);
        }
    });
  }
