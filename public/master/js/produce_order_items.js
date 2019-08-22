var app = new Vue({
    el: '#app',

    data: {
        order_items: [],
        total: {
            quantity: 0,
            cost: 0
        },
        product_quantity: 1,
        manufacturing_cost: 0,
        grand_total: 0,
    },

    methods:{
        init() {
            // axios.get('/get_products')
            //     .then(response => {
            //         this.products = response.data;
            //     })
            //     .catch(error => {
            //         console.log(error);
            //     });
        },
        add_item() {
            axios.get('/get_first_supply')
                .then(response => {
                    this.order_items.push({
                        supply_id: response.data.id,
                        supply_name_code: response.data.name + "(" + response.data.code + ")",
                        cost: response.data.cost,
                        unit: response.data.unit,
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
            this.total.quantity = total_quantity
            this.total.cost = total_cost
        },
        calc_grand_total() {
            this.grand_total = parseInt(this.total.cost) + parseInt(this.manufacturing_cost)
        },
        remove(i) {
            this.order_items.splice(i, 1)
        },
        formatPrice(value) {
            let val = value;
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    },

    mounted:function() {
        this.init();
        this.add_item()
        $("#app").css('opacity', 1);
    },
    updated: function() {
        this.calc_subtotal()
        this.calc_grand_total()
        $(".supply").autocomplete({
            source : function( request, response ) {
                axios.post('/get_autocomplete_supplies', { keyword : request.term })
                    .then(resp => {
                        // response(resp.data);
                        response(
                            $.map(resp.data, function(item) {
                                return {
                                    label: item.name + "(" + item.code + ")",
                                    value: item.name + "(" + item.code + ")",
                                    id: item.id,
                                    cost: item.cost,
                                    unit: item.unit,
                                }
                            })
                        );
                    })
                    .catch(error => {
                        console.log(error);
                    }
                );
            }, 
            minLength: 1,
            select: function( event, ui ) {
                let index = $(".supply").index($(this));
                app.order_items[index].supply_id = ui.item.id
                app.order_items[index].supply_name_code = ui.item.label
                app.order_items[index].cost = ui.item.cost
                app.order_items[index].unit = ui.item.unit
                app.order_items[index].quantity = 1
                app.order_items[index].sub_total = ui.item.cost
            }
        });
    },
    created: function() {
        var self = this
        $(document).keydown(function(e){
            if(e.keyCode == 21 || e.keyCode == 17 || e.keyCode == 25){
                self.add_item()
            }
        });
    }
});


