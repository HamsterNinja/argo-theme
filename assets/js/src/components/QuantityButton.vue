<template>
    <div class="person-quantity">
        <button class="decrease-button" @click.prevent="decrementProduct">
            <svg width="14" height="2" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 1h13.5" stroke="#000"/></svg>
        </button>
        <input :ref="'input_' + refhash" class="inputNumber" type="number" min="1" v-model="countComponent" @change="updateValue" v-on:keyup.enter="enterValue">
        <button class="increase-button" @click.prevent="incrementProduct">
            <svg width="14" height="14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 7h13.5M7 0v13.5" stroke="#000"/></svg>
        </button>
    </div>
</template>

<script>
export default {
    props: {
        // TODO: добавить v-model поддежку
        cart_id: {
            type: Number | String,
        },
        count: {
            type: Number | String,
            default: 1
        },
        maxCount: {
            type: [Number, String],
            default: 100
        }
    }, 
    data() {
        return {
            countComponent: parseInt(this.count),
            refhash: Math.floor(Math.random() * Math.floor(1000))
        };
    },
    methods: {
        enterValue(){
            this.$refs['input_' + this.refhash].focus();
        },
        incrementProduct() {
            if (parseInt(this.maxCount) > this.countComponent) {
                this.countComponent++;
            }
            this.updateValue();
        },
        decrementProduct() {
            if(this.countComponent > 1){
                this.countComponent--;
                this.updateValue();
            }
        },
        updateValue(event) {
            if(event){
                let newValue = parseInt(event.target.value)
                if(isNaN(newValue)){
                    let newValue = 1
                }
                else{
                    if(parseInt(this.maxCount) > Math.abs(newValue)){
                        this.countComponent = Math.abs(newValue)
                    }
                    else{
                        this.countComponent = parseInt(this.maxCount)
                    }
                }
            }
            this.$emit('input', this.countComponent)
            this.$store.commit('updateProductCount', this.countComponent);
            if(SITEDATA.is_cart == 'true'){
                this.updateProductQuantityInCartByCartID(this.cart_id, this.countComponent);
            }
        },
        updateProductQuantityInCartByCartID(cartID, productQuantity) {
            //TODO: убрать jquery
            const self = this;
            $.ajax({
                type: "POST",
                url: `${SITEDATA.url}/wp-admin/admin-ajax.php`,
                data: {
                    'action': 'set_item_from_cart_by_cart_id',
                    'cart_id': cartID,
                    'product_quantity': productQuantity
                },
                success: function (res) {
                    // TODO: обновление остатков можно в mixin запихнуть
                    if (res.success) {
                        $.post(wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'), function (data) {
                            if (data && data.fragments) {
                                $.each(data.fragments, function (key, value) {
                                    $(key).replaceWith(value);
                                });
                                $(document.body).trigger('wc_fragments_refreshed');
                            }
                        });

                        self.$store.commit('updateCartSubtotal', parseFloat(res.data.total))
                    }
                    else{
                        console.log('error update');
                    }
                }
            });
        }
        
    }
}
</script>