$(function () {
    $('#keywords').val('');
    $('#f_id').val('');
    $('#f_first_name').val('');
    $('#f_last_name').val('');
    $('#f_email').val('');
    $('#from_create_date').val('');
    $('#to_create_date').val('');
    $('#from_update_date').val('');
    $('#to_update_date').val('');

    showAllUsers();

    // modal window
    $('#createEditUsersForm').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var recipient = button.data('whatever') // Extract info from data-whatever attributes

        var id = button.data('id');

        // Update the modal's content.

        var modal = $(this)

        // Show form for add user
        if (recipient == "addUser") {
            modal.find('.modal-title').text('Add new user');
            modal.find('#btn_submit').text('Add user');
            $('#btn_submit').addClass("btn-add");
            $('#btn_submit').removeClass("btn-edit");
            // document.forms['form_users_data'].action = "addUser.php";
            $('#first_name').val('');
            $('#last_name').val('');
            $('#email').val('');

            // Show form for edit user
        } else if (recipient == "editUser") {
            modal.find('.modal-title').text('Edit user');
            modal.find('#btn_submit').text('Edit user');
            $('#btn_submit').addClass("btn-edit");
            $('#btn_submit').removeClass("btn-add");

            // document.forms['form_users_data'].action = "editUser.php";

            $.getJSON("getById.php?id=" + id, function (data) {
                $('#first_name').val(data.first_name);
                $('#last_name').val(data.last_name);
                $('#email').val(data.email);
                $('#id').val(id);
            });
        }
    })

    //search form event
    $('#form_search').on('submit', function () {
        var keywords = $(this).find(":input[name='keywords']").val();
        $.getJSON("search.php?s=" + keywords, function (data) {
            showFilteredUsers(data);
        });
        return false;
    });

    //add & edit user button
    $('#form_users_data').on('submit', function () {

        // getting form data
        var form_data = JSON.stringify($(this).serializeObject());

        if ($('#btn_submit').hasClass("btn-add")) {
            // submitting form data
            $.ajax({
                url: "addUser.php",
                type: "POST",
                contentType: 'application/json',
                data: form_data
            }).done(function () {
                $("#form_users_data").trigger("reset");
                $('#createEditUsersForm').modal('hide');
                alert("User added");
                showAllUsers();
            });

        } else if ($('#btn_submit').hasClass("btn-edit")) {
            $.ajax({
                url: "editUser.php",
                type: "POST",
                contentType: 'application/json',
                data: form_data,
            })
                .done(function () {
                    $("#form_users_data").trigger("reset");
                    $('#createEditUsersForm').modal('hide');
                    alert("User edited");
                    showAllUsers();
                });
        }
        return false;
    });
});

// form data to json 
$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function showAllUsers() {
    $.getJSON("getAllUsers.php", function (data) {
        var user_row_html = "";
        $.each(data.records, function (key, val) {
            // creating a new table row for each record
            user_row_html += `
            <tr>
            <th scope='row'>`+ val.id + `</th>
            <td>`+ val.first_name + `</td>
            <td>`+ val.last_name + `</td>
            <td>`+ val.email + `</td>
            <td>`+ val.create_date + `</td>
            <td>`+ val.update_date + `</td>
            <td class='d-flex flex-column justify-content-center'>          
                  <button
                  type='button'
                  class='btn btn-warning btn-editing'
                  data-toggle='modal'
                  data-target='#createEditUsersForm'
                  data-whatever='editUser'
                  style='border: 1px solid white;'
                  data-id='`+ val.id + `'
                <i class='fas fa-user-edit pr-2'></i>
                Edit
                </button>
                </td>
            </tr>`;
        });
        $('#users_grid').html(user_row_html);

    });
}

function showFilteredUsers(data) {
    var user_row_html = "";
    $.each(data.records, function (key, val) {
        // creating a new table row for each record
        user_row_html += `
            <tr>
            <th scope='row'>`+ val.id + `</th>
            <td>`+ val.first_name + `</td>
            <td>`+ val.last_name + `</td>
            <td>`+ val.email + `</td>
            <td>`+ val.create_date + `</td>
            <td>`+ val.update_date + `</td>
            <td class='d-flex flex-column justify-content-center'>          
                  <button
                  type='button'
                  class='btn btn-warning btn-editing'
                  data-toggle='modal'
                  data-target='#createEditUsersForm'
                  data-whatever='editUser'
                  style='border: 1px solid white;'
                  data-id='`+ val.id + `'
                <i class='fas fa-user-edit pr-2'></i>
                Edit
                </button>
                </td>
            </tr>`;
    });
    $('#users_grid').html(user_row_html);
}

function filter() {
    var form_data = JSON.stringify($('#form_filters').serializeObject());

    $.post('filter.php', form_data, function (data) {
        showFilteredUsers(data);
    }, 'json');

    return false;
}