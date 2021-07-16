new Vue({
    el: "#app",
    components: {
        Multiselect: window.VueMultiselect.default
    },
    data: {
        bodega:"",
        bodegas:[],
        serie:"0",
        series:[],
        correlativo:"",
        loading:false,
        fecha:"",
        items: [],
        item: "",
        description: "",
        Items: [],
        error: false,
        errores: [],
        sign:"",
        quantity:0,
        amount:'Q 0.00',
        amountNumber:0,
        
    },
    watch:{
        serie:function (val) {
            this.getCorrelative(val);
        },
        correlativo:function(val){
            if(this.serie=="0"||this.serie=="")
            {
                this.changeInput('serie');
                this.correlativo="";
                toastr.error('Seleccione una serie');
            }
            else
                this.correlativo=Math.abs(val);
        },
        bodega:function(val){
            showLoading();
            let largo=this.Items.length;
            this.Items.forEach((element,index) => {
                axios.get(`/api/inventory_adjustment/existence?id=${element.id}&bodega=${val}`)
                .then(response => {
                    if(largo==index+1){
                        hideLoading();
                    }
                    var nuevaExistencia=response.data.quantity;
                    if(this.sign=="+"){
                        if(typeof(nuevaExistencia)=="undefined")
                            nuevaExistencia=0;
                        this.Items[index].existence=nuevaExistencia;
                        this.Items[index].newExistence=this.operationExistence(nuevaExistencia,this.Items[index].quantity);
                    }else{
                        if(typeof(nuevaExistencia)=="undefined")
                            this.Items.splice(this.Items.findIndex(i=>i.id==element.id),1);
                        else{
                            if(nuevaExistencia==0){
                                this.Items.splice(this.Items.findIndex(i=>i.id==element.id),1);
                            }
                            else if(nuevaExistencia<this.Items[index].quantity){
                                this.Items[index].quantity=nuevaExistencia;
                                this.Items[index].existence=nuevaExistencia;
                                this.Items[index].newExistence=this.operationExistence(nuevaExistencia,this.Items[index].quantity);
                            }
                        }       
                    }
                    this.sumQuantity();
                })
                .catch(errors => {
                    console.log(errors);
                    hideLoading();
                });
            });
            if(largo==0)
                hideLoading();
        }
    },
    mounted: function () {
        this.getUrl();
        this.$emit('input');
        this.getBodega();
        this.getSeries();
        
    },
    methods: {
        sumQuantity(){
            this.quantity=0;
            this.amount=0;
            this.Items.forEach(element => {
                this.quantity+=parseFloat(element.quantity);
                this.amount+=parseFloat(element.cost_price)*parseFloat(element.quantity);
            });
            this.amountNumber=this.amount;
            this.amount=formato_moneda(this.amount);
        },
        changeInput(value){
            if(value==='serie')
                this.$refs.serie.focus();
            else if(value==='bodega')
                this.$refs.bodega.focus();
            else if(value==='productos')
                this.$refs.productos.$el.focus();
        },
        getUrl() {
            var ruta = window.location.pathname.split("/");
            if(ruta[2]=="output")
                this.sign='-';
            else
                this.sign='+';
        },
        operationExistence(Existencia,Cantidad){
            var A=parseFloat(Existencia);
            var B=parseFloat(Cantidad);
            if(this.sign=="+")
                return (parseFloat(A+B).toFixed(2));
            else
                return (parseFloat(A-B).toFixed(2));

        },
        read:function(val){
            if((this.bodega=="0" || this.bodega=="") &&this.sign=="-"){
                toastr.error('Seleccione una bodega');
                return;
            }
            this.loading=true;
            let newSign=this.sign=='+'?"Positivo":"Negativo";
            if(val){
                axios.get(`/api/inventory_adjustment/searchItems?bodega=${this.bodega}&item=${val}&estado=${newSign}`)
                .then(response => {
                    this.items=[];
                    this.items = response.data;
                    this.loading=false;
                })
                .catch(errors => {
                    console.log(errors);
                    this.loading=false;
                    this.items=[];
                });
            }else{
                this.loading=false;
            }  
        },
        validate() {
            this.errores = [];
            this.error = false;
            if(this.description.length<10){
                this.errores.push('El largo de la descipción debe ser mayor a 10');
            }
            if(this.description==""){
                this.errores.push('Ingrese una descipción');
            }
            if(this.bodega=="0" || this.bodega==""){
                this.errores.push('Seleccione una bodega');
            }
            if(this.correlativo==""||this.correlativo=="0"){
                this.errores.push('Ingrese un correlativo');
            }
            if(this.serie==""||this.serie=="0"){
                this.errores.push('Seleccione una serie');
            }
            this.Items.forEach((e) => {
                if (e.quantity <= 0)
                    this.errores.push(`El producto ${e.item_name} no puede tener la cantidad de 0 en el ajuste`);
            });
            if(String(document.getElementById('date').value)==null||String(document.getElementById('date').value)=="")
            {
                this.errores.push('Seleccione una fecha');
            }
            if(this.Items.length<1){
                this.errores.push('Ingrese productos para el ajuste');
            }
            if (this.errores.length > 0) {
                this.errores.forEach(element => {
                    toastr.error(element);
                });
                return true;
            }
            else {
                return false;
            }
        },
        getCorrelative(correlativo){
            axios.get(`/api/inventory_adjustment/correlative?serie=${correlativo}`)
                .then(response => {
                    this.correlativo=response.data.correlative;
                })
                .catch(errors => {
                    console.log(errors);
                });
        },
        getBodega() {
            axios.get('/api/inventory_adjustment/selectBodega')
                .then(response => {
                    this.bodegas = response.data;
                })
                .catch(errors => {
                    console.log(errors);
                });
        },
        getSeries() {
            var typeSerie=1;
            if(this.sign=='-')
                typeSerie=0;
            axios.get(`/api/inventory_adjustment/selectSerie?sign=${typeSerie}`)
                .then(response => {
                    this.series = response.data;
                })
                .catch(errors => {
                    console.log(errors);
                });
        },
        getIndex(list, id) {
            return list.findIndex((e) => e.id == id)
        },
        removeItemFromArr(arr, item) {
            var i = arr.indexOf(item);

            if (i !== -1) {
                arr.splice(i, 1);
            }
        },
        removeItem(item) {
            this.removeItemFromArr(this.Items, item);
            this.sumQuantity();
        },
        addItem() {
            this.error = false;
            this.errores = [];
            if (this.item !== "") {
                if (this.verificarExiste() === false) {
                    var newItem = new Object;
                    newItem.id = this.item.id;
                    newItem.cost_price=this.item.cost_price;
                    newItem.upc_ean_isbn = this.item.upc_ean_isbn;
                    newItem.item_name = this.item.item_name;
                    newItem.existence=this.item.quantity;
                    newItem.quantity = 1;
                    newItem.newExistence=this.operationExistence(this.item.quantity,1)
                    if(newItem.existence>0&&this.sign=="-")
                        this.Items.unshift(newItem);
                    else if(this.sign=="+")
                        this.Items.unshift(newItem);
                    else if(newItem.existence==0&&this.sign=="-")
                        toastr.error('No se puede agregar el producto porque no hay existencia');
                    this.item="";
                    this.changeInput('productos');
                    this.items=[];
                    this.sumQuantity();
                } else {
                    this.error = true;
                    toastr.error('El producto ya esta agregado al ajuste');
                    this.item = "";
                    return;
                }
            } else {
                this.error = true;
                toastr.error('No se ha seleccionado ningun producto');
                this.item = "";
                return;
            }
        },
        verificarExiste() {
            let solucion = false;
            this.Items.forEach(element => {
                if (element.id === this.item.id) {
                    solucion = true;
                }
            });
            return solucion;
        },
        nameWithLang({ upc_ean_isbn, item_name }) {
            return `${upc_ean_isbn} — ${item_name}`
        },
        save(){
            if (this.validate() === true) {
                this.error = true;
                return;
            }
            $('#modalDelete').modal('show');
        },
        money: function (value) {
            return formato_moneda(isNaN(value)?0:value);
        },
        saveAll() {
            showLoading();
            var tempSing=[];
            tempSing.push(this.sign);
            axios.post('/inventory_adjustment/save', {
                correlative: this.correlativo,
                serie_id:this.serie,
                comments:this.description,
                sign:this.sign,
                almacen_id:this.bodega,
                total:this.amountNumber,
                cantidad:this.quantity,
                inventory_adjustment_date:String(document.getElementById('date').value),
                items:JSON.parse(JSON.stringify(this.Items))
              })
              .then(function (response) {
                toastr.success('Ajuste agregado');
                console.log(response.data.message);
                hideLoading();
                if(tempSing[0]=='+'){
                    //window.location.href = "/inventory_adjustment/detail/input/"+response.data.message;
                    window.location.href="/inventory_adjustment/index/input";
                }else{
                    //window.location.href = "/inventory_adjustment/detail/output/"+response.data.message;
                    window.location.href="/inventory_adjustment/index/output";
                }
              })
              .catch(function (error) {
                error.response.data.message.forEach(element => {
                    toastr.error(element);
                });
                hideLoading();
              });
            $('#modalDelete').modal('hide');
        },
        sumNuevaExistence(index){
            this.Items[index].quantity=Math.abs(this.Items[index].quantity);
            this.Items[index].newExistence=this.operationExistence(this.Items[index].existence,this.Items[index].quantity);
            if(this.Items[index].newExistence<0)
            {
                this.Items[index].quantity=0;
                this.Items[index].newExistence=this.Items[index].existence;
                toastr.error('La nueva existencia no puede ser negativa');
            }
            this.sumQuantity();
        },
    }
});

