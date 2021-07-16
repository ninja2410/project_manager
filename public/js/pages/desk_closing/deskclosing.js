new Vue({
    el: "#app",
    components: {
        Multiselect: window.VueMultiselect.default
    },
    data: {
        show_error:false,
        billetes: [],
        efectivo: 'Q 0.00',
        desk: 0,
        revenues: [],
        pagos: [],
        cheque: 'Q 0.00',
        deposito: 'Q 0.00',
        card: 'Q 0.00',
        transferencia: 'Q 0.00',
        Desk: '',
        serie: "0",
        series: [],
        correlativo: "",
        gasto: "",
        ingreso: "",
        saldo: "",
        suma: [],
        wizard:1,
        actualFocus:"efectivo",
        totalIngreso:0,
        totalSistema:0,
        totalDiferencia:0,
        efectivoNumber:0,
        chequeNumber:0,
        depositoNumber:0,
        cardNumber:0,
        transferenciaNumber:0,
        error:false,
        errores:[],
        parametro:false,
        flag:true,
        numberDiferencia:-1,
        numberTotal:0,
        Dates:[],
        move:false,
        listNames:[
            'Efectivo',
            'Cheque',
            'Depósito',
            'Tarjeta de crédito/débito',
            'Transferencia',
            'Movimientos',
            'Balance',
            'Transferir / Depositar'
        ],
        flagEfectivo:true,
        cuentas:[],
        seleccionCuentas:[],
        selectCuenta:[],
        cuentasBancarias:[],
        myCheques:0,
        myEfectivo:0,
        myTodo:0,
        myGeneric:0,
        amountActual:0,
        flagMovimiento:false,
        successMovimiento:false,
        flagDesk:false,
        finalSeleccion:0,
        totalesCaja:[],
        totalesCheque:[],
        myindice:0,
        totalDepositar:0,
        totalDepositarNumber:0,
        myFlagEfectivo:false
    },
    computed: {
        Efectivo: function () {
            return this.revenues.filter(i => i.pm === '1' && i.tipo==="Ingreso" && i.status!="Inactivo")
        },
        Cheque: function () {
            return this.revenues.filter(i => i.pm === '2' && i.tipo==="Ingreso" && i.status!="Inactivo")
        },
        Deposito: function () {
            return this.revenues.filter(i => i.pm === '3' && i.tipo==="Ingreso" && i.status!="Inactivo")
        },
        Card: function () {
            return this.revenues.filter(i => i.pm === '4' && i.tipo==="Ingreso" && i.status!="Inactivo")
        },
        Transferencia: function () {
            return this.revenues.filter(i => i.pm === '5' && i.tipo==="Ingreso" && i.status!="Inactivo")
        },  
        inEfectivo: function () {
            return this.revenues.filter(i => i.pm === '1' && i.status!="Inactivo")
        },
        inCheque: function () {
            return this.revenues.filter(i => i.pm === '2' && i.status!="inactivo")
        },
        inDeposito: function () {
            return this.revenues.filter(i => i.pm === '3' && i.status!="inactivo")
        },
        inCard: function () {
            return this.revenues.filter(i => i.pm === '4' && i.status!="inactivo")
        },
        inTransferencia: function () {
            return this.revenues.filter(i => i.pm === '5' && i.status!="inactivo")
        },
    },
    watch: {
        efectivoValidar(){
            this.efectivo=Math.abs(this.efectivo);
        },
        serie: function (val) {
            this.getCorrelative(val);
        },
        wizard:function(val){
            if(val===1 && this.billetes.length>0){
                this.setFocus('efectivo0',this.billetes.length);
                this.actualFocus='efectivo';
            }else if(val===2&&this.Cheque.length>0){
                this.setFocus('cheque0',this.Cheque.length);
                this.actualFocus='cheque';
            }else if(val===3 && this.Deposito.length>0){
                this.setFocus('deposito0',this.Deposito.length);
                this.actualFocus='deposito';
            }else if(val===4 && this.Card.length>0){
                this.setFocus('card0',this.Card.length);
                this.actualFocus='card';
            }else if(val===5 && this.Transferencia.length>0){
                this.setFocus('transferencia0',this.Transferencia.length);
                this.actualFocus='transferencia';
            }else if(val===7){
                this.calcularTotales();
            }else if(val===8&&this.flagDesk==true){
                this.seleccionCuentas=[];
                this.selectCuenta=[];
                this.cuentas=[];
                if(this.Desk.fixed_amount>0)
                    this.cargarCuentas(this.Cheque,this.cuentasBancarias,(this.efectivoNumber-parseFloat(this.Desk.fixed_amount)),true);
                else
                    this.cargarCuentas(this.Cheque,this.cuentasBancarias,this.efectivoNumber,true);
            }
        },
        
    },
    mounted() {
        this.getUrl();
        this.getDesk();
        this.getSerie();
        this.$emit('input');
        this.getBillete();
        this.$refs['document'].focus();
        this.getCuentasBancarias();
    },
    methods: {
        sumarTotal(){
            this.totalDepositar=0;
            this.totalDepositarNumber=0;
            this.selectCuenta.forEach(element => {
                this.totalDepositarNumber+=parseFloat(element.amount);
            });
            this.totalDepositar=formato_moneda(this.totalDepositarNumber);
        },
        getAnterior(valor){
            var miLista=[];
            miLista.push(valor);
            return miLista[0];
        },
        getDifernecia(universo,utilizados){
            var diferencia=[];
            universo.forEach(element => {
                if(typeof(utilizados.find(i=>i.id==element.id))=="undefined")
                    diferencia.push(element);
            });
            return diferencia;
        },
        getCuentas(listB){
            var finalArray=[];
            listB.forEach(element => {
                finalArray.push(element);
            });
            return finalArray;
        },
        insertarMenosTodo(universo,utilizados){
            var diferencia=[];
            universo.forEach(element => {
                if(typeof(utilizados.find(i=>i.id==element.id))=="undefined")
                    diferencia.push(element);
            });
            return diferencia;
        },
        todos(lista,xEfectivo){
            var total=0;
            var item=new Object();
            lista.forEach(element => {
                total+=parseFloat(element.amount);
            });
            total+=parseFloat(xEfectivo);
            item.id=Date.now() + Math.random();
            item.pm="0";
            item.amount=parseFloat(total).toFixed(2);
            item.bank="";
            item.reference="";
            item.method="Todo";
            item.estado=true;
            return item;
        },
        nameWithLang({method,reference,bank,amount}) {
            if(method=="Efectivo"||method=="Todo")
                return `${method} \/ ${formato_moneda(amount)}`;
            else
                return `${method} # ${reference} - ${bank} \/ ${formato_moneda(amount)}`;
        },
        nameBank({account_name,account_number}) {
            return `${account_name} \/ ${account_number}`;
        },
        cargarCuentas(lista,bancos,xEfectivo,flag){
            this.totalesCaja.push(formato_moneda(xEfectivo));
            var xitem=new Object;
            var ncuentas=[];
            var verificarTotal=0;
            var verificarCheque=0;
            // INSERTAR PARA EFECTIVO
            if(xEfectivo>0){
                var item=new Object;
                item.id=Date.now() + Math.random();
                item.pm="1";
                item.amount=xEfectivo;
                item.bank="";
                item.reference="";
                item.method="Efectivo";
                ncuentas.push(item);
                verificarTotal++;    
            }
            //INSERTAR PARA LOS CHEQUES
            if(flag==true)
                lista.forEach(element => {
                    if(element.selected==true)
                    {
                        var item=new Object();
                        item.id=Date.now() + Math.random();
                        item.pm=element.pm;
                        item.amount=element.amount;
                        item.bank=element.bank_name;
                        item.reference=element.reference;
                        item.method=element.payment_method;
                        ncuentas.push(item);
                        verificarTotal++;
                        verificarCheque++;
                    }
                });
            else
                lista.forEach(element => {
                    if(element.method=="Cheque"){
                        var item=new Object();
                        item.id=Date.now() + Math.random();
                        item.pm=element.pm;
                        item.amount=element.amount;
                        item.bank=element.bank;
                        item.reference=element.reference;
                        item.method=element.method;
                        ncuentas.push(item);
                        verificarTotal++;
                        verificarCheque++;
                    }
                });
            //INSERTAR TOTAL
            this.totalesCheque.push(verificarCheque);
            if(verificarTotal>1){
                var TemporalTodo=this.todos(lista,xEfectivo);
                ncuentas.unshift(TemporalTodo);
            }
            xitem.id=Date.now() + Math.random();
            xitem.cuentas=ncuentas;
            xitem.bancos=bancos;
            this.seleccionCuentas.push(xitem);
            this.cuentas.push(this.getCuentas(ncuentas));

            var xxitem=new Object();
            xxitem.id=xitem.id;
            xxitem.cuentas=null;
            xxitem.bancos=null;
            xxitem.reference=null;
            xxitem.monto=null;
            xxitem.amount=0;
            xxitem.estado=false;
            xxitem.verificacion=false;
            xxitem.anterior=false;
            this.selectCuenta.push(xxitem);
            
        },
        changeVueMultiselect(index){
            var flags=[false,false,false];
            var namesPagos=["Efectivo","Cheque","Todo"];
            var total=0;

            this.selectCuenta[index].cuentas.forEach(element => {
                if(element.method==namesPagos[2])
                    flags[2]=true;
                else if(element.method==namesPagos[1])
                    flags[1]=true;
                else if (element.method==namesPagos[0])
                    flags[0]=true;
                total+=parseFloat(element.amount);
            });
            this.selectCuenta[index].monto=formato_moneda(total);
            this.selectCuenta[index].amount=parseFloat(total).toFixed(2);
            if(flags[2]==true&&flags[1]==false&&flags[0]==false){
                this.seleccionCuentas[index].cuentas=this.selectCuenta[index].cuentas;
                this.selectCuenta[index].estado=true;    
            }else if(flags[2]==false&& flags[1]==false && flags[0]==false){
                this.seleccionCuentas[index].cuentas=[];
                this.seleccionCuentas[index].cuentas=this.getCuentas(this.cuentas[index]);
                this.selectCuenta[index].estado=false;
            }else if(flags[2]==false&&flags[1]==true&&flags[0]==false){
                this.selectCuenta[index].estado=true;
                var indice=this.seleccionCuentas[index].cuentas.findIndex(x=>x.method=="Todo");
                if(indice!=(-1))
                    this.seleccionCuentas[index].cuentas.splice(indice,1);  
            }else if(flags[2]==false&&flags[1]==true&&flags[0]==true){
                this.selectCuenta[index].estado=false;
                var indice=this.seleccionCuentas[index].cuentas.findIndex(x=>x.method=="Todo");
                if(indice!=(-1))
                    this.seleccionCuentas[index].cuentas.splice(indice,1);  
            }else if(flags[2]==false&&flags[1]==false&&flags[0]==true){
                this.selectCuenta[index].estado=false;
                var indice=this.seleccionCuentas[index].cuentas.findIndex(x=>x.method=="Todo");
                if(indice!=(-1))
                    this.seleccionCuentas[index].cuentas.splice(indice,1);  
            }
        },
        changeInput(index){
            this.flagMovimiento=false;
            if(isNaN(this.selectCuenta[index].amount))
                this.selectCuenta[index].amount=0;
            
            this.selectCuenta[index].amount=Math.abs(this.selectCuenta[index].amount);
            this.selectCuenta[index].amount=parseFloat(this.selectCuenta[index].amount).toFixed(2);
            var flags=[false,false,false];
            var namesPagos=["Efectivo","Cheque","Todo"];
            var efectivo=0;
            var cheque=0;
            this.selectCuenta[index].cuentas.forEach(element => {
                if(element.method==namesPagos[1]){
                    flags[1]=true;
                    cheque+=parseFloat(element.amount);        
                }else if (element.method==namesPagos[0]){
                    flags[0]=true;
                    efectivo+=parseFloat(element.amount);
                }
            });
            total=efectivo+cheque;
            if(flags[2]==false&&flags[1]==true&&flags[0]==true){
                if((this.selectCuenta[index].amount-cheque)<1){
                    this.selectCuenta[index].amount=parseFloat(1+parseFloat(cheque)).toFixed(2);
                    toastr.error('Si selecciona efectivo la cantidad mínima a depositar no puede ser menor a 1');
                    this.flagMovimiento=false;
                }else if((this.selectCuenta[index].amount-cheque)>efectivo){
                    this.selectCuenta[index].amount=parseFloat(efectivo+cheque).toFixed(2);
                    toastr.error('Si selecciona efectivo la cantidad máxima a depositar no puede ser mayor o lo registrado');
                    this.flagMovimiento=false;
                }
            }else if(flags[2]==false&&flags[1]==false&&flags[0]==true){
                if((this.selectCuenta[index].amount-cheque)<1){
                    this.selectCuenta[index].amount=parseFloat(1+parseFloat(cheque)).toFixed(2);
                    toastr.error('Si selecciona efectivo la cantidad mínima a depositar no puede ser menor a 1');
                    this.flagMovimiento=false;
                }else if(this.selectCuenta[index].amount>efectivo){
                    this.selectCuenta[index].amount=parseFloat(efectivo).toFixed(2);
                    toastr.error('Si selecciona efectivo la cantidad máxima a depositar no puede ser mayor o lo registrado');
                    this.flagMovimiento=false;
                }
            }        
        },
        miniSuma(list){
            var total=0;
            list.forEach(element => {
                if(element.method=="Cheque")
                    total+=parseFloat(element.amount);
            });
            return total;
        },
        previousMovimiento(index){
            console.log(index,this.myindice);
            var indice=index;
            if(index>0){
                indice--;
                this.seleccionCuentas.splice(index,1);
                this.selectCuenta.splice(index,1);
                this.cuentas.splice(index,1);
            }else{
                indice=0;
            }
            if(this.myindice>2){
                this.totalesCaja.splice(this.myindice,1);
                this.totalesCheque.splice(this.myindice,1);
                this.myindice--;
            }else{
                this.totalesCaja.splice(1,2);
                this.totalesCheque.splice(1,2);
                this.myindice=0;
            }
            this.selectCuenta[indice].verificacion=false;
            this.successMovimiento=false;
            this.selectCuenta[indice].estado=this.selectCuenta[indice].anterior;
            this.sumarTotal();
                        
        },
        nextMovimiento(index){
            this.successMovimiento=false;
            if(this.flagMovimiento==true){
                toastr.error('Verifique la cantidad ingresada para continuar');
                return;
            }
            if(this.selectCuenta[index].bancos!=null){            
                if(this.selectCuenta[index].cuentas!=null){
                    this.selectCuenta[index].verificacion=true;
                    this.selectCuenta[index].anterior=this.selectCuenta[index].estado;
                    this.selectCuenta[index].estado=true;
                    if(this.selectCuenta[index].cuentas.length==this.seleccionCuentas[index].cuentas.length){
                        if(typeof(this.selectCuenta[index].cuentas.find(i=>i.method=="Efectivo"))!="undefined"){
                            if((parseFloat(this.selectCuenta[index].amount-this.miniSuma(this.selectCuenta[index].cuentas)).toFixed(2))==(parseFloat(this.selectCuenta[index].cuentas.find(i=>i.method=="Efectivo").amount).toFixed(2))){
                                toastr.success('LISTO');
                                this.successMovimiento=true;
                                this.totalesCaja.push(formato_moneda(0));
                                this.totalesCheque.push(0);
                            }else{
                                this.selectCuenta[index].monto=formato_moneda(this.selectCuenta[index].amount);
                                this.cargarCuentas([],this.cuentasBancarias,(this.seleccionCuentas[index].cuentas.find(i=>i.method=="Efectivo").amount)-(this.selectCuenta[index].amount-this.miniSuma(this.selectCuenta[index].cuentas)),false);
                            }
                        }else{
                            toastr.success('LISTO');
                            this.successMovimiento=true;
                            this.totalesCaja.push(formato_moneda(0));
                            this.totalesCheque.push(0);
                        }
                    }else{
                        
                        var FlagEfectivo=false;
                        var FlagCheque=false;
                        if(typeof(this.selectCuenta[index].cuentas.find(i=>i.method=="Efectivo"))!="undefined"){
                            if((parseFloat((this.selectCuenta[index].amount-this.miniSuma(this.selectCuenta[index].cuentas)).toFixed(2))==(parseFloat(this.selectCuenta[index].cuentas.find(i=>i.method=="Efectivo").amount).toFixed(2)))){
                                FlagEfectivo=false;
                            }else{
                                FlagEfectivo=true;
                            }
                        }else if(typeof(this.seleccionCuentas[index].cuentas.find(i=>i.method=="Efectivo"))!="undefined"){
                            FlagEfectivo=true;
                        }
                        if(typeof(this.selectCuenta[index].cuentas.find(i=>i.method=="Cheque"))!="undefined"){
                            if(this.selectCuenta[index].cuentas.filter(i=>i.method=="Cheque").length==this.seleccionCuentas[index].cuentas.filter(i=>i.method=="Cheque").length){
                                FlagCheque=false;
                            }else{
                                FlagCheque=true;
                            }
                        }else if(typeof(this.seleccionCuentas[index].cuentas.find(i=>i.method=="Cheque"))!="undefined"){
                            FlagCheque=true;
                        }

                        if(FlagEfectivo==true&&FlagCheque==true){
                            this.selectCuenta[index].monto=formato_moneda(this.selectCuenta[index].amount);
                            this.cargarCuentas(this.getDifernecia(this.seleccionCuentas[index].cuentas,this.selectCuenta[index].cuentas),this.cuentasBancarias,(this.seleccionCuentas[index].cuentas.find(i=>i.method=="Efectivo").amount)-(this.selectCuenta[index].amount-this.miniSuma(this.selectCuenta[index].cuentas)),false);
                        }else if(FlagEfectivo==false&&FlagCheque==true){
                            this.cargarCuentas(this.getDifernecia(this.seleccionCuentas[index].cuentas,this.selectCuenta[index].cuentas),this.cuentasBancarias,0,false);
                        }else if(FlagEfectivo==true&&FlagCheque==false){
                            this.selectCuenta[index].monto=formato_moneda(this.selectCuenta[index].amount);
                            this.cargarCuentas([],this.cuentasBancarias,(this.seleccionCuentas[index].cuentas.find(i=>i.method=="Efectivo").amount)-(this.selectCuenta[index].amount-this.miniSuma(this.selectCuenta[index].cuentas)),false);
                        }
                    }
                    this.myindice++;    
                }else{
                    toastr.error('Tiene que seleccionar al menos un ingreso');
                }
                this.sumarTotal();
            }else{
                toastr.error('Seleccione a que cuenta transferir el dinero');
            }
        },
        isNumberKey(){
            const regex = /^\d+(\.\d{1,2})?$/;
            const matches = regex.exec(this.efectivoNumber);
            if(matches!=null){
                this.pagos[0].diferencia=this.diferencia(this.efectivoNumber,this.pagos[0].value);
                this.pagos[0].diferencia=Math.round(this.pagos[0].diferencia*100)/100;
                this.efectivo=formato_moneda(this.efectivoNumber);
            }else{
                this.efectivoNumber=Math.abs(this.efectivoNumber);
                if(this.efectivoNumber.isNaN())
                    this.efectivoNumber=0;
                myFlagEfectivo=true;
            }
        },
        calcularSuma(){
            this.pagos[0].diferencia=this.diferencia(this.efectivoNumber,this.pagos[0].value);
            this.pagos[0].diferencia=Math.round(this.pagos[0].diferencia*100)/100;
            this.efectivo=formato_moneda(this.efectivoNumber);
            this.calcularTotales();
        },
        validar(){
            this.flag=false;
            if(this.parametro==true){
                if(this.numberDiferencia!=0){
                    toastr.error('Los registros debe coincidir, con lo ingresado');
                    this.flag=true;
                }   
            }else{
                if(this.numberDiferencia!=0){
                    toastr.error('Los registros debe coincidir, con lo ingresado');
                    this.flag=true;
                }
                this.pagos.forEach(element => {
                    if(element.diferencia!=0){
                        toastr.error(`El tipo de pago ${element.name} no coincide con lo ingresado`);
                        this.flag=true;
                    }
                });
                
            }
            if(this.correlativo===""||this.correlativo<=0||this.correlativo==null){
                this.flag=true;
                toastr.error('Es obligatorio ingresar un correlativo');
            }
            if(this.serie==="0")
                toastr.error('Es obligatorio seleccionar un documento');
            if(this.flag==false)
                toastr.success('Los registros correponden con lo ingesado')
        },
        toSerie(){
            this.$refs['serie'][0].focus();
        },
        toWizard(){
            this.$refs[this.actualFocus+'0'][0].focus();
        },
        documentoSelect(){
            this.correlativo=Math.abs(this.correlativo);
            if(this.serie==="0"){
                toastr.error('Seleccione un documento');
                this.correlativo="";
            }
        },
        getUrl() {
            var ruta = window.location.pathname.split("/");
            this.desk = ruta[3];
        },
        getDesk() {
            axios.get(`/desk_closing/api/desk/${this.desk}`)
                .then(response => {
                    this.Desk = response.data.desk;
                    this.parametro=response.data.parametro[0].active=="0"?false:true;
                    this.Desk.updated_at = moment(this.Desk.updated_at).format("DD/MM/YYYY HH:mm:ss");
                    this.getRevenues();
                    this.flagDesk=true;
                }).catch(error => {
                    console.log(error);
                    this.flagDesk=false;
                });
        },
        getCuentasBancarias(){
            axios.get('/desk_closing/api/getAccounts')
                .then(response => {
                    this.cuentasBancarias=response.data;
                }).catch(error => {
                    console.log(error);
                });
        },
        serializarRevenue(){
            this.revenues.forEach((element,index) => {
                this.revenues[index].selected=false;
            });
            this.pagos.forEach((element,index) => {
                this.pagos[index].diferencia=this.diferencia(0,this.pagos[index].value);
            });
            
        },
        money: function (value) {
            return formato_moneda(isNaN(value)?0:value);
        },
        fecha(myDate) {
            return moment(myDate).format("DD/MM/YYYY");
        },
        getSerie() {
            axios.get('/desk_closing/api/documents')
                .then(response => {
                    this.series = response.data;
                }).catch(errores => {
                    console.log(errores);
                });
        },
        getCorrelative(id) {
            axios.get(`/desk_closing/api/selectCorrelative/${id}`)
                .then(response => {
                    this.correlativo = response.data.correlative;
                }).catch(errores => {
                    console.log(errores);
                });
        },
        getBillete() {
            axios.get('/desk_closing/api/billetes')
                .then(response => {
                    this.billetes = response.data;
                })
                .catch(errors => {
                    console.log(errors);

                });
        },
        getIndex(list, id) {
            return list.findIndex((e) => e.id == id)
        },
        getQuantity(index) {
            if(this.flagEfectivo===true)
            {
                this.efectivo = 0;
                this.billetes[index].quantity = Math.abs(this.billetes[index].quantity);
                this.billetes.forEach(b => {
                    this.efectivo += (parseFloat(b.quantity) * parseFloat(b.value));
                    this.pagos[0].diferencia=this.diferencia(this.efectivo,this.pagos[0].value);
                });
                this.efectivoNumber=Math.round(this.efectivo*100)/100;
                this.efectivo = formato_moneda(this.efectivo);
            }
        },
        onKeydown (evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
              evt.preventDefault();;
            } else {
              return true;
            }
        },
        addtoSum(index) {
            this.revenues[index].selected = 1 - this.revenues[index].selected;
            this.cheque = 0;
            this.deposito = 0;
            this.card = 0;
            this.transferencia = 0;
            this.revenues.forEach(element => {
                if (element.selected == 1) {
                    if (element.pm == 2){
                        this.cheque += (parseFloat(element.amount));
                        this.pagos[1].diferencia=this.diferencia(this.cheque,this.pagos[1].value);
                    }else if (element.pm == 3){
                        this.deposito += (parseFloat(element.amount));
                        this.pagos[2].diferencia=this.diferencia(this.deposito,this.pagos[2].value);
                    }else if (element.pm == 4){
                        this.card += (parseFloat(element.amount));
                        this.pagos[3].diferencia=this.diferencia(this.card,this.pagos[3].value);
                    }else if (element.pm == 5){
                        this.transferencia += (parseFloat(element.amount));
                        this.pagos[4].diferencia=this.diferencia(this.transferencia,this.pagos[4].value);
                    }
                }
            });
            
            this.chequeNumber=this.cheque;
            this.depositoNumber=this.deposito;
            this.cardNumber=this.card;
            this.transferenciaNumber=this.transferencia;
            
            

            this.cheque = formato_moneda(this.cheque);
            this.deposito = formato_moneda(this.deposito);
            this.card = formato_moneda(this.card);
            this.transferencia = formato_moneda(this.transferencia);
            
        },
        diferencia(Ingresado,Sistema){
            return ((parseFloat(Ingresado))-(parseFloat(Sistema)));
        },
        getRevenues() {
            axios.get(`/desk_closing/api/sales?caja=${this.desk}&date=${this.Desk.created_at}`)
                .then(response => {
                    this.revenues = response.data.accounts;
                    if(this.revenues.length<1){
                        this.show_error=true;
                    }else{
                        this.show_error=false;
                    }
                    
                    this.pagos = response.data.pagos;
                    this.gasto = formato_moneda(response.data.gasto);
                    this.ingreso = formato_moneda(response.data.ingreso);
                    this.saldo = formato_moneda(response.data.saldo);
                    this.Dates=response.data.Desk;
                    this.sum();
                    this.serializarRevenue();
                    
                })
                .catch(errors => {
                    console.log(errors)
                });
        },
        sum() {
            var total = 0;
            this.revenues.forEach(element => {
                if (element.status != 'Inactivo') {
                    if(element.tipo=='Ingreso')
                        total += (parseFloat(element.amount));
                    else
                        total -= (parseFloat(element.amount));
                    this.suma.push(this.money(total));
                }
                else {
                    this.suma.push(this.money(total));
                }
            });
        },
        setFocus(value,list){
            var myRef=value.split(this.actualFocus);
            if(myRef[1]==list){
                this.$refs[this.actualFocus+'0'][0].focus();
            }else
                this.$refs[value][0].focus();
        },
        nextWizard(){
            if(this.wizard>=8)
                this.wizard=8;
            else
                this.wizard++;    
        },
        previuosWizard(){
            if(this.wizard<=1)
                this.wizard=1;
            else
                this.wizard--;
        },
        seleccionar(estado){
            this.cheque = 0;
            this.deposito = 0;
            this.card = 0;
            this.transferencia = 0;
            this.revenues.forEach((element,index) => {
                if(element.status!="Inactivo"&&element.pm==(this.wizard))
                    this.revenues[index].selected = estado;
                if (element.selected == 1) {
                    if (element.pm == 2){
                        this.cheque += (parseFloat(element.amount));
                        this.pagos[1].diferencia=this.diferencia(this.cheque,this.pagos[1].value);
                    }else if (element.pm == 3){
                        this.deposito += (parseFloat(element.amount));
                        this.pagos[2].diferencia=this.diferencia(this.deposito,this.pagos[2].value);
                    }else if (element.pm == 4){
                        this.card += (parseFloat(element.amount));
                        this.pagos[3].diferencia=this.diferencia(this.card,this.pagos[3].value);
                    }else if (element.pm == 5){
                        this.transferencia += (parseFloat(element.amount));
                        this.pagos[4].diferencia=this.diferencia(this.transferencia,this.pagos[4].value);
                    }
                }
                
            });
            
            this.chequeNumber=this.cheque;
            this.depositoNumber=this.deposito;
            this.cardNumber=this.card;
            this.transferenciaNumber=this.transferencia;
            
            this.cheque = formato_moneda(this.cheque);
            this.deposito = formato_moneda(this.deposito);
            this.card = formato_moneda(this.card);
            this.transferencia = formato_moneda(this.transferencia);
            
        },
        calcularTotales(){
            this.totalIngreso=0;
            this.totalSistema=0;
            this.totalDiferencia=0;
            
            this.totalIngreso=parseFloat(this.efectivoNumber)+this.chequeNumber+this.depositoNumber+this.cardNumber+this.transferenciaNumber;
            this.numberTotal=this.totalIngreso;
            this.pagos.forEach(element => {
                this.totalSistema+=parseFloat(element.value);
            });
            this.numberDiferencia=this.diferencia(this.totalIngreso,this.totalSistema);
            this.numberDiferencia=Math.round(this.numberDiferencia*100)/100;
            this.totalDiferencia=formato_moneda(this.diferencia(this.totalIngreso,this.totalSistema));
            this.totalSistema=formato_moneda(this.totalSistema);
            this.totalIngreso=formato_moneda(this.totalIngreso);
            this.validar();
        },
        save(){
            this.validar();
            if (this.flag === true) {
                return;
            }
            $('#modalDelete').modal('show');
        },
        saveCashRegister(){
            this.finalSeleccion=[];
            this.selectCuenta.forEach((element,indice)=>{
                if(typeof(this.selectCuenta[indice].cuentas.find(i=>i.method=="Todo"))!="undefined"){
                    this.selectCuenta[indice].cuentas=this.getCuentas(this.cuentas[indice]);
                    var index=this.selectCuenta[indice].cuentas.findIndex(i=>i.method=="Todo");
                    if(index!=(-1))
                        this.selectCuenta[indice].cuentas.splice(index,1);
                }
                    
            });
            this.selectCuenta.forEach((element,indice) => {
                element.cuentas.forEach((item,index) => {
                    var temporal=new Object();
                    temporal.account_id=this.Desk.id;
                    if(item.method=="Cheque"){
                        temporal.amount=parseFloat(item.amount).toFixed(2);
                        temporal.description=`Cierre de caja: ${this.Desk.account_name} / Cheque #${item.reference} / ${item.bank}`;
                    
                    }else if(item.method=="Efectivo"){
                        temporal.amount=parseFloat(element.amount-this.miniSuma(this.selectCuenta[indice].cuentas)).toFixed(2);
                        temporal.description=`Cierre de caja: ${this.Desk.account_name} / Efectivo`;
                    }
                    temporal.category_id=this.Desk.category_id;
                    temporal.reference=element.reference;
                    temporal.payment_method=item.method;
                    temporal.pm=item.pm;
                    //Bancos
                    temporalBanco=new Object();
                    temporalBanco.id=element.bancos.id;
                    temporalBanco.amount=temporal.amount;
                    temporalBanco.description=temporal.description;
                    temporalBanco.category_id=13;
                    temporalBanco.reference=element.reference;
                    temporalBanco.payment_method=3;
                    temporal.bancos=temporalBanco;
                
                    this.finalSeleccion.push(temporal);
                });    
            });
            showLoading();
            axios.post('/desk_closing', {
                account_id:this.Desk.id,
                startDate:this.Desk.created_at,
                correlative:this.correlativo,
                serie_id:this.serie,
                cash_amount:this.efectivoNumber,
                deposit_amount:this.depositoNumber,
                transfer_amount:this.transferenciaNumber,
                card_amount:this.cardNumber,
                check_amount:this.chequeNumber,
                total:this.numberTotal,
                status_id:'1',
                billetes:JSON.parse(JSON.stringify(this.billetes)),
                revenue:JSON.parse(JSON.stringify(this.revenues)),
                dates:JSON.parse(JSON.stringify(this.Dates)),
                flag:this.flagEfectivo,
                transfers:JSON.parse(JSON.stringify(this.finalSeleccion)),
                dateX:this.Desk.updated_at
            }).then((response)=>{
                toastr.success('Cierre de caja agregado');
                console.log(response);
                hideLoading();
                window.location.href="/desk_closing/show/"+response.data.message[1];
            }).catch((error)=>{
                console.log(error);
                error.response.data.message.forEach(element => {
                    toastr.error(element);
                });
                hideLoading();
            });
            $('#modalDelete').modal('hide');
        }
    }
});

