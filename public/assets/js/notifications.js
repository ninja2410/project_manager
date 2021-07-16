function verifyNotifications(){
    if ($('#first_page').val()){
        showLoading("Verificando notificaciones... por favor espere");
        $.ajax({
            method: 'get',
            url: APP_URL + '/getNotifications',
            success: function (data) {
                notifications = data;
                if (notifications.length>0){
                    notifications.forEach(function (data_, index) {
                        if (data_.proveedor){
                            var name = data_.proveedor;
                        }
                        else{
                            var name = data_.cliente;
                        }
                        var htmlTags = '<tr>'+
                            '<td>' + data_.message + '</td>'+
                            '<td>' + name + '</td>'+
                            '<td><a class="btn btn-danger" href="'+data_.url+'" data-toggle="tooltip" data-original-title="Ir a módulo" target="_blank"><span class="glyphicon glyphicon-edit"></span></a></td>'+
                            '</tr>';
                        $('#table_notifications tbody').append(htmlTags);
                    });
                    $('#modal-notifications').modal('show');
                }
                hideLoading();
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
}

function getNotifications() {
    let not = new NotificationHeader();
    not.getList();
}

class NotificationHeader {
    constructor() {
        this._counter = 0;
    }
    set counter(val){
        this._counter = val;
    }
    getList(){
        document.getElementById('notification_loader').style.display = 'inline';
        $.ajax({
            method: 'get',
            async:true,
            url: APP_URL + '/getNotifications',
            success: function (data) {
                let json =  data;
                let contador = json.length;
                let span = document.getElementById('notification_counter');
                let title = document.getElementById('notification_title');
                if (contador > 0){
                    span.style.display = 'inline';
                    span.innerHTML='';
                    span.append(contador);
                    title.innerHTML = '';
                    title.append("Tiene "+contador+" notificaciones");
                }
                else{
                    span.style.display = 'none';
                    title.innerHTML = '';
                    title.append("No tiene notificaciones");
                }
                json.forEach(function (value) {
                    let htmlTemp = '<li>' +
                        '<i class="livicon warning" data-n="timer" data-s="20" data-c="white" data-hc="white"></i>' +
                        '<a href="'+APP_URL+value.url+'">'+value.message+'</a>' +
                        '<small class="pull-right">' +
                        '<span class="livicon paddingright_10" data-n="timer" data-s="10"></span>' +
                        'Cuenta Bancaria'+
                        '</small>' +
                        '</li>';
                    document.getElementById('notification_list').innerHTML += htmlTemp;
                });
                document.getElementById('notification_loader').style.display = 'none';
            },
            error: function (error) {
                return error;
            }
        });
    }
}
