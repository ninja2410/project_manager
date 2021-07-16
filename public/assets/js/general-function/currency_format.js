function currency_format(cantidad,cif = 3, dec = 2) {
    // tomamos el valor que tiene el input
    let inputNum =cantidad;
    inputNum = inputNum.toString()
    inputNum = inputNum.split('.')
    if (!inputNum[1]) {
        inputNum[1] = '00'
    }
    let separados
    if (inputNum[0].length > cif) {
        let uno = inputNum[0].length % cif
        if (uno === 0) {
            separados = []
        } else {
            separados = [inputNum[0].substring(0, uno)]
        }
        let posiciones = parseInt(inputNum[0].length / cif)
        for (let i = 0; i < posiciones; i++) {
            let pos = ((i * cif) + uno)
            separados.push(inputNum[0].substring(pos, (pos + 3)))
        }
    } else {
        separados = [inputNum[0]]
    }
    return valorTotalFormateado =  separados.join(',') + '.' + inputNum[1];
}