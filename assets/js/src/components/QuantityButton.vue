<template>
    <div class="person-quantity">
        <button class="decrease-button" @click.prevent="decrementProduct">
            <svg width="14" height="2" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 1h13.5" stroke="#000"/></svg>
        </button>
        <input class="inputNumber" type="number" min="1" :value="countComponent" @change="updateValue">
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
    }, 
    data() {
        return {
            countComponent: parseInt(this.count)
        };
    },
    methods: {
        incrementProduct() {
            this.countComponent++;
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
                    this.countComponent = Math.abs(newValue)
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