var app = new Vue({
    el: '#app',

    data: {
        order_items: [],
        supplies: [],
        selected_product: '',
        total: {
            quantity: 0,
            cost: 0
        },
        date: '',
        reference_no: '',
        supplier_id: '',
        status: 1,
        credit_days: 1,
        note: '',
        discount: 0,
        discount_string: 0,
        shipping: '0',
        shipping_string: '0',
        returns: 0,
        grand_total: 0,
        params: {
            id: $('#purchase_id').val()
        }
    },

    methods:{
        init() {
            axios.get('/get_supplies')
                .then(response => {
                    let data = response.data
                    
                    for (let i = 0; i < data.length; i++) {
                        const supply = data[i];
                        this.supplies.push({
                            id: supply.id,
                            name: supply.name,
                            code: supply.code,
                            cost: supply.cost,
                        })                   
                    }
                })
                .catch(error => {
                    console.log(error);
                });  
            axios.post('/get_data', this.params)
                .then(response => {
                    let data = response.data;
                    // console.log(data)
                    this.date = data.timestamp.substring(0, 16)
                    this.reference_no = data.reference_no
                    this.supplier_id = data.supplier_id
                    this.status = data.status
                    this.credit_days = data.credit_days
                    this.shipping_string = data.shipping_string
                    this.discount_string = data.discount_string
                    this.returns = data.returns
                    this.note = data.note

                    let orders = response.data.orders
                    for (let i = 0; i < orders.length; i++) {
                        const order = orders[i];
                        axios.post('/get_supply', {id:order.supply_id})
                            .then(response1 => {
                                this.order_items.push({
                                    supply_id: order.supply_id,
                                    supply_name_code: response1.data.name + "(" + response1.data.code + ")",
                                    cost: order.cost,
                                    quantity: order.quantity,
                                    expiry_date: order.expiry_date,
                                    sub_total: order.subtotal,
                                    order_id: order.id,
                                })
                            })
                            .catch(error => {
                                console.log(error);
                            });                    
                    }
                })
                .catch(error => {
                    console.log(error);
                });                
        },
        add_item() {
            axios.get('/get_first_supply')
                .then(response => {
                    this.order_items.push({
                        supply_id: response.data.id,
                        supply_name_code: response.data.name + "(" + response.data.code + ")",
                        cost: response.data.cost,
                        quantity: 1,
                        expiry_date: "",
                        sub_total: response.data.cost,
                    })
                })
                .catch(error => {
                    console.log(error);
                });            
        },
        calc_subtotal() {
            data = this.order_items
            let total_quantity = 0;
            let total_cost = 0;
            for(let i = 0; i < data.length; i++) {
                this.order_items[i].sub_total = parseInt(data[i].cost) * data[i].quantity
                total_quantity += parseInt(data[i].quantity)
                total_cost += data[i].sub_total
            }
            this.total.quantity = total_quantity
            this.total.cost = total_cost
        },
        calc_grand_total() {
            this.grand_total = this.total.cost - this.discount - this.shipping - this.returns
        },
        calc_discount_shipping(){
            let reg_patt1 = /^\d+(?:\.\d+)?%$/
            let reg_patt2 = /^\d+$/
            if(reg_patt1.test(this.discount_string)){
                this.discount = this.total.cost*parseFloat(this.discount_string)/100
                // console.log(this.discount)
            }else if(reg_patt2.test(this.discount_string)){
                this.discount = this.discount_string
            }else if(this.discount_string == ''){
                this.discount = 0
            }else {
                this.discount_string = '0';
            }

            if(reg_patt1.test(this.shipping_string)){
                this.shipping = this.total.cost*parseFloat(this.shipping_string)/100
                // console.log("percent")
            }else if(reg_patt2.test(this.shipping_string)){
                this.shipping = this.shipping_string
            }else if(this.shipping_string == ''){
                this.shipping = 0
            }else {
                this.shipping_string = '0';
            }

        },
        remove(i) {
            this.order_items.splice(i, 1)
        },
        formatPrice(value) {
            let val = value;
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        get_supply(i){
            let order_item = this.order_items[i]
            axios.post('/get_supply', {id:order_item.supply_id})
            .then(response1 => {
                order_item.supply_name_code = response1.data.name + "(" + response1.data.code + ")"
                order_item.cost = response1.data.cost
                order_item.quantity = 1
                order_item.sub_total = response1.data.cost
            })
            .catch(error => {
                console.log(error);
            }); 
        }
    },

    mounted:function() {
        this.init();
    },
    created: function() {
        var self = this
        $(document).keydown(function(e){
            if(e.keyCode == 21){
                self.add_item()
            }
        });
    },
    updated: function() {
        this.calc_subtotal()
        this.calc_discount_shipping()
        this.calc_grand_total()
        // $(".supply").autocomplete({
        //     source : function( request, response ) {
        //         axios.post('/get_autocomplete_supplies', { keyword : request.term })
        //             .then(resp => {
        //                 // response(resp.data);
        //                 response(
        //                     $.map(resp.data, function(item) {
        //                         return {
        //                             label: item.name + "(" + item.code + ")",
        //                             value: item.name + "(" + item.code + ")",
        //                             id: item.id,
        //                             cost: item.cost,
        //                         }
        //                     })
        //                 );
        //             })
        //             .catch(error => {
        //                 console.log(error);
        //             }
        //         );
        //     }, 
        //     minLength: 1,
        //     select: function( event, ui ) {
        //         let index = $(".supply").index($(this));
        //         app.order_items[index].supply_id = ui.item.id
        //         app.order_items[index].supply_name_code = ui.item.label
        //         app.order_items[index].cost = ui.item.cost
        //         app.order_items[index].quantity = 1
        //         app.order_items[index].sub_total = ui.item.cost
        //     }
        // });
    }    
});


