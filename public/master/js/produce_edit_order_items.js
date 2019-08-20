var app = new Vue({
    el: '#app',

    data: {
        order_items: [],
        supplies: [],
        total: {
            quantity: 0,
            cost: 0
        },
        date: '',
        reference_no: '',
        product_id: '',
        workshop_id: '',
        product_quantity: '',
        deadline: '',
        responsibility: '',
        description: '',
        supply_cost: 0,
        manufacturing_cost: 0,
        total_cost: 0,
        params: {
            id: $('#order_id').val()
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
            axios.post('/produce_get_data', this.params)
                .then(response => {
                    let data = response.data;
                    // console.log(data)
                    this.date = data.timestamp.substring(0, 16)
                    this.reference_no = data.reference_no
                    this.product_id = data.product_id
                    this.product_quantity = data.quantity
                    this.workshop_id = data.workshop_id
                    this.description = data.description
                    this.responsibility = data.responsibility
                    this.deadline = data.deadline
                    this.supply_cost = data.supply_cost
                    this.manufacturing_cost = data.manufacturing_cost
                    this.total_cost = data.total_cost

                    let orders = response.data.supplies
                    for (let i = 0; i < orders.length; i++) {
                        const order = orders[i];
                        axios.post('/get_supply', {id:order.supply_id})
                            .then(response1 => {
                                this.order_items.push({
                                    supply_id: order.supply_id,
                                    supply_name_code: response1.data.name + "(" + response1.data.code + ")",
                                    cost: order.cost,
                                    quantity: order.quantity,
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
                total_quantity += parseFloat(data[i].quantity)
                total_cost += data[i].sub_total
            }
            // this.total.quantity = total_quantity
            this.total.cost = total_cost
        },        
        calc_total_cost() {
            this.total_cost = parseInt(this.total.cost) + parseInt(this.manufacturing_cost)            
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
    updated: function() {
        this.calc_subtotal()
        this.calc_total_cost()
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


