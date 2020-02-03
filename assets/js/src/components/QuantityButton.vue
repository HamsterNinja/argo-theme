<template>
    <div class="person-quantity">
        <button @click.prevent="decrementProduct">
            <svg width="14" height="2" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 1h13.5" stroke="#000"/></svg>
        </button>
        <input class="inputNumber" type="number" min="1" :value="countComponent">
        <button @click.prevent="incrementProduct">
            <svg width="14" height="14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 7h13.5M7 0v13.5" stroke="#000"/></svg>
        </button>
    </div>
</template>

<script>
export default {
    props: {
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
        },
        decrementProduct() {
            if(this.countComponent > 1){
                this.countComponent--;
            }
        },
    }
}
</script>