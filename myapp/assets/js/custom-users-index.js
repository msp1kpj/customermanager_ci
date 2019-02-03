$(document).ready(function(){

    $("#frmUserPassword").validate({
        onkeyup: false,
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("invalid-feedback");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        }
    });

    $('#modal-remove-user')
        .on('show.bs.modal', function () {
            var self = $(this)
                , userId = self.data('userId')
                , removeBtn = self.find('.btn-danger')
                , href = removeBtn.attr('href')
                ;
            removeBtn.attr('href', href.replace(/\d+/g, userId));
        })
        .on('hidden.bs.modal', function () {
            var self = $(this)
                , removeBtn = self.find('.btn-danger')
                , href = removeBtn.attr('href')
                ;
            removeBtn.attr('href', href.replace(/\d+/g, 0));
        })
    ;

    function removeUser() {
        var self = $(this)
            , userId = self.data('userid')
            ;

        $('#modal-remove-user')
            .data('userId', userId)
            .modal({ backdrop: 'static', keyboard: true });
    }


    $('#modal-edit-user')
        .on('show.bs.modal', function () {
            var self = $(this)
                , userId = parseInt(self.data('userId'))
                , firstName = self.data('firstName')
                , lastName = self.data('lastName')
                , emailAddress = self.data('emailAddress')
                , frm = $('#frmUser')
            ;

            frm.find("#userId").first().val(userId);
            frm.find("#firstName").first().val(firstName);
            frm.find("#lastName").first().val(lastName);
            frm.find("#emailAddress").first().val(emailAddress);
            if(userId){
                frm.find("#frm-group-password").first().hide();
                frm.find("#userPassword1").removeAttr('required');
                frm.find("#userConfirmPassword1").removeAttr('required');
            }


        })
        .on('hidden.bs.modal', function () {
            var self = $(this)
                , frm = $('#frmUser')
            ;

            frm.find("#userId").first().val(0);
            frm.find("#firstName").first().val('');
            frm.find("#lastName").first().val('');
            frm.find("#emailAddress").first().val('');
            frm.find("#frm-group-password").first().show();
            frm.find("#userPassword1").prop('required', true);
            frm.find("#userConfirmPassword1").prop('required', true);

        })
        .on('shown.bs.modal', function () {
            $('#firstName').trigger('focus')
        })
    ;

    $('#modal-edit-user-password')
        .on('show.bs.modal', function () {
            var self = $(this)
                , userId = parseInt(self.data('userId'))
                , frm = $('#frmUserPassword')
            ;

            console.log(userId);
            frm.find("input[name='userid']").first().val(userId);
            frm.find("#userPassword1").val('');
            frm.find("#userConfirmPassword1").val('');


        })
        .on('hidden.bs.modal', function () {
            var self = $(this)
                , frm = $('#frmUserPassword')
            ;
            frm.find("input[name='userid']").first().val(0);

        })
        .on('shown.bs.modal', function () {
            $('#userPassword').trigger('focus')
        })
    ;

    function editUser() {
        var self = $(this)
            , userId = self.data('userid')
            ;

        $.ajax({
            url: '/users/user/' + userId,
            type: 'get',
            dataType: 'json',
            success: function (json) {
                $('#modal-edit-user')
                    .data('userId', json.userId)
                    .data('firstName', json.firstName)
                    .data('lastName', json.lastName)
                    .data('emailAddress', json.emailAddress)
                    .modal({ backdrop: 'static', keyboard: true });
            },
            error: function(data){
                if(data.status == 403){
                    window.location.href = "/security";
                }
            }
        });


    }

    function newUser(){
        $('#modal-edit-user')
            .data('userId', 0)
            .data('firstName', '')
            .data('lastName', '')
            .data('emailAddress', '')
            .modal({ backdrop: 'static', keyboard: true });
    }

    function userPasswordReset(){
        var self = $(this)
            , userId = self.data('userid')
        ;
        console.log(userId);

        $('#modal-edit-user-password')
            .data('userId', userId)
            .modal({ backdrop: 'static', keyboard: true })
        ;
    }


    $(".delete").on("click", removeUser);
    $(".edit").on("click", editUser);
    $(".add").on("click", newUser);
    $(".password").on("click", userPasswordReset);
});