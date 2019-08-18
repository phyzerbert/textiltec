var app = new Vue({
    el: '#app',

    data: {
        order_items: [],
        products: [],
        selected_product: '',
        total: {
            quantity: 0,
            price: 0
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
            id: $('#product_sale_id').val()
        }
    },

    methods:{
        init() {
            axios.get('/get_products')
                .then(response => {
                    let data = response.data
                    
                    for (let i = 0; i < data.length; i++) {
                        const product = data[i];
                        this.products.push({
                            id: product.id,
                            name: product.name,
                            code: product.code,
                            price: product.price,
                        })                   
                    }
                })
                .catch(error => {
                    console.log(error);
                });  
            axios.post('/get_product_sale_data', this.params)
                .then(response => {
                    let data = response.data;
                    // console.log(data)
                    this.date = data.timestamp.substring(0, 16)
                    this.reference_no = data.reference_no
                    this.supplier_id = data.supplier_id
                    this.status = data.status
                    this.shipping_string = data.shipping_string
                    this.discount_string = data.discount_string
                    this.returns = data.returns
                    this.note = data.note

                    let orders = response.data.orders
                    for (let i = 0; i < orders.length; i++) {
                        const order = orders[i];
                        axios.post('/get_product', {id:order.product_id})
                            .then(response1 => {
                                this.order_items.push({
                                    product_id: order.product_id,
                                    product_name_code: response1.data.name + "(" + response1.data.code + ")",
                                    price: order.price,
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
            axios.get('/get_first_product')
                .then(response => {
                    this.order_items.push({
                        product_id: response.data.id,
                        product_name_code: response.data.name + "(" + response.data.code + ")",
                        price: response.data.price,
                        quantity: 1,
                        expiry_date: "",
                        sub_total: response.data.price,
                    })
                })
                .catch(error => {
                    console.log(error);
                });            
        },
        calc_subtotal() {
            data = this.order_items
            let total_quantity = 0;
            let total_price = 0;
            for(let i = 0; i < data.length; i++) {
                this.order_items[i].sub_total = parseInt(data[i].price) * data[i].quantity
                total_quantity += parseInt(data[i].quantity)
                total_price += data[i].sub_total
            }
            this.total.quantity = total_quantity
            this.total.price = total_price
        },
        calc_grand_total() {
            this.grand_total = this.total.price - this.discount - this.shipping - this.returns
        },
        calc_discount_shipping(){
            let reg_patt1 = /^\d+(?:\.\d+)?%$/
            let reg_patt2 = /^\d+$/
            if(reg_patt1.test(this.discount_string)){
                this.discount = this.total.price*parseFloat(this.discount_string)/100
                // console.log(this.discount)
            }else if(reg_patt2.test(this.discount_string)){
                this.discount = this.discount_string
            }else if(this.discount_string == ''){
                this.discount = 0
            }else {
                this.discount_string = '0';
            }

            if(reg_patt1.test(this.shipping_string)){
                this.shipping = this.total.price*parseFloat(this.shipping_string)/100
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
        get_product(i){
            let order_item = this.order_items[i]
            axios.post('/get_product', {id:order_item.product_id})
            .then(response1 => {
                order_item.product_name_code = response1.data.name + "(" + response1.data.code + ")"
                order_item.price = response1.data.price
                order_item.quantity = 1
                order_item.sub_total = response1.data.price
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
        this.calc_discount_shipping()
        this.calc_grand_total()
        // $(".product").autocomplete({
        //     source : function( request, response ) {
        //         axios.post('/get_autocomplete_products', { keyword : request.term })
        //             .then(resp => {
        //                 // response(resp.data);
        //                 response(
        //                     $.map(resp.data, function(item) {
        //                         return {
        //                             label: item.name + "(" + item.code + ")",
        //                             value: item.name + "(" + item.code + ")",
        //                             id: item.id,
        //                             price: item.price,
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
        //         let index = $(".product").index($(this));
        //         app.order_items[index].product_id = ui.item.id
        //         app.order_items[index].product_name_code = ui.item.label
        //         app.order_items[index].price = ui.item.price
        //         app.order_items[index].quantity = 1
        //         app.order_items[index].sub_total = ui.item.price
        //     }
        // });
    }    
});


