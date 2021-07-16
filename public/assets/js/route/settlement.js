$(document).ready(function () {
    var  inputs = document.getElementsByClassName('detail_manual');
    for (let index = 0; index < inputs.length; index++) {
        inputs[index].addEventListener('input', function (e) {
            setAmount(e.target);
        });

    }

    function setAmount(control){
        let val = convertMoney(control.value);
        let total_cobro = +(document.getElementById('total_payments').value);
        let total = +(document.getElementById('total_manual').value);
        total -= +number_format(control.getAttribute("oldValue"), 2);
        total += +number_format(val, 2);
        document.getElementById('diference').value = number_format((total_cobro - total), 2);
        document.getElementById('total_manual').value = +total;
        control.setAttribute("oldValue", +val);
        if ((total_cobro - total)!= 0){
            document.getElementById('diference').style.borderColor = 'red';
        }
        else{
            document.getElementById('diference').style.borderColor = 'green';
        }
    }
    function convertMoney(string) {
        var ns = string.replace(",", "");
        ns = ns.replace(" ", "");
        ns = ns.replace("Q", "");
        return parseFloat(ns);
    }
});
