let payments = [];
let payments_counter = 0;
$(document).ready(function () {
    $('#btnAddPayment').click(function () {
        addPayment();
    });
    var cleave = new Cleave('#payment_amount', {
        numeral: true,
        numeralPositiveOnly: true,
        numeralThousandsGroupStyle: 'thousand'
    });

    document.getElementById('payments_container').addEventListener("DOMSubtreeModified", function () {
        calcTotal();
    });
});
let changeCreditAccount = function (control) {
    if (control.value != '') {
        $('#debit_accounts').select2('destroy');
        let varx = document.getElementById('debit_accounts').getElementsByTagName("option");
        for (let x of varx) {
            x.removeAttribute("disabled");
        }
        document.getElementById('account2_' + control.value).setAttribute("disabled", "disabled");
        $('#debit_accounts').select2({
            allowClear: true,
            theme: "bootstrap",
            placeholder: "Buscar"
        });
    }
};

let addPayment = function () {
    let account = $('#account_origin').val();
    let account_debit = $('#debit_accounts').val();
    let amount = cleanNumber($('#payment_amount').val());
    let select = $("#debit_accounts option:selected")[0];
    let acc_name = $("#debit_accounts option:selected")[0].textContent;
    let account_balance = cleanNumber(select.getAttribute("balance"));
    if (account == '') {
        toastr.error("Debe seleccionar una cuenta a acreditar el monto del traslado.")
        $('#account_origin').focus();
        return;
    }
    if (account_debit == '') {
        toastr.error("Debe seleccionar una cuenta a debitar monto del traslado.");
        $('#debit_accounts').focus();
        return;
    }
    if (amount <= 0) {
        toastr.error("Debe ingresar un monto mayor que 0.");
        $('#payment_amount').focus();
        return;
    }
    if (amount > account_balance){
        toastr.error("La cuenta no tiene suficiente saldo para realizar el pago.")
        return;
    }
    let det = new Payment(account_debit, account_balance, acc_name, amount, payments_counter);
    payments.push(det);
    $('#tblPayments').find('tbody').append(det.render());
    setMoneyMask();
    payments_counter++;
    $('#payment_amount').val(0);
    $('#debit_accounts').val(null).trigger('change');
    $('#debit_accounts').focus();
};

class Payment {
    constructor(account_id, account_balance, name_account, amount, id) {
        this.account_id = account_id;
        this.name = name_account;
        this.balance = account_balance;
        this.amount = amount;
        this.id = id;
    }

    render() {
        let classRmv = "row_payment_detail";
        return '<tr class="row_payment_detail">' +
            '          <td>' +
            this.name +
            '          </td>' +
            '          <td>' +
            '            <input onchange="verifyInput(this)" type="text" det_id="'+this.id+'" value="'+this.amount+'" oldValue="'+this.amount+'" class="form-control money payment_detail" account_id="'+this.account_id+'" max="'+this.balance+'">' +
            '          </td>' +
            '          <td>' +
            '            <button class="btn danger btn-xs green-stripe" det_id="'+this.id+'" onclick="deleteRow(this, \''+classRmv+'\')">Eliminar</button>' +
            '          </td>' +
            '        </tr>';
    }
}

let deleteRow = function (elRef, classDelete) {
    $(elRef).parents('.'+classDelete)[0].remove();
    let del_id = $(elRef).attr("det_id");
    for (let i =0; i < payments.length; i++){
        if (payments[i].id == del_id){
            payments.splice(i, 1);
            calcTotal();
        }
    }
}

let setMoneyMask = () => {
    $('.money').toArray().forEach(function(field){
        new Cleave(field, {
            numeral: true,
            numeralPositiveOnly: true,
            numeralThousandsGroupStyle: 'thousand'
        });
    });
};

let calcTotal = function () {
    let total_payments = 0;
    payments.forEach(function (det) {
        total_payments += det.amount;
    });
    $('#total_debit').text('Q '+total_payments.format(2));
    $('#_total_debit').val(total_payments);
    return total_payments;
};

let findPayment = function(detail, id){
    return detail.id === id;
};

let verifyInput = function (input) {
    let id = $(input).attr("det_id");
    let newValue = cleanNumber(input.value);
    let max_amount = cleanNumber(input.getAttribute("max"));
    let oldValue = cleanNumber(input.getAttribute("oldValue"));
    if (newValue>max_amount){
        toastr.error("La cuenta no tiene suficiente saldo para realizar el pago.");
        input.value = oldValue;
        return;
    }
    input.setAttribute("oldValue", newValue);
    payments.forEach(function (det) {
        if (det.id == id){
            det.amount = cleanNumber(input.value);
        }
    });
    calcTotal();
};